const vehicleData = {
  1: { name: "VW Transporter Ocean", rate: 119 },
  2: { name: "VW Transporter Adventure", rate: 129 }
};

let currentStep = 1;
let occupiedRanges = [];

const startDate = document.getElementById("startDate");
const endDate = document.getElementById("endDate");
const availabilityMessage = document.getElementById("availabilityMessage");

function selectedVehicleId() {
  return Number(document.querySelector('input[name="vehicle"]:checked').value);
}

function selectedVehicle() {
  return vehicleData[selectedVehicleId()];
}

function setStep(step) {
  currentStep = step;
  document.querySelectorAll(".booking-step").forEach(el => el.classList.toggle("active", Number(el.dataset.step) === step));
  document.querySelectorAll("[data-step-indicator]").forEach(el => el.classList.toggle("active", Number(el.dataset.stepIndicator) === step));
  document.getElementById("beschikbaarheid").scrollIntoView({ behavior: "smooth", block: "start" });
}

function dateLabel(value) {
  if (!value) return "—";
  return new Intl.DateTimeFormat("nl-NL", { day:"numeric", month:"short", year:"numeric" })
    .format(new Date(value + "T12:00:00"));
}

function calculateDays() {
  if (!startDate.value || !endDate.value) return 0;
  return Math.round((new Date(endDate.value + "T12:00:00") - new Date(startDate.value + "T12:00:00")) / 86400000);
}

function updateSummary() {
  const vehicle = selectedVehicle();
  const days = calculateDays();

  document.getElementById("summaryVehicle").textContent = vehicle.name;
  document.getElementById("summaryRate").textContent = `€${vehicle.rate} / dag`;
  document.getElementById("summaryDates").textContent =
    startDate.value && endDate.value ? `${dateLabel(startDate.value)} – ${dateLabel(endDate.value)}` : "Nog niet gekozen";
  document.getElementById("summaryDays").textContent = days > 0 ? `${days} ${days === 1 ? "dag" : "dagen"}` : "—";
  document.getElementById("summaryTotal").textContent = `€${days > 0 ? days * vehicle.rate : 0}`;
}

async function loadAvailability() {
  updateSummary();
  const id = selectedVehicleId();

  const from = new Date();
  const to = new Date();
  to.setMonth(to.getMonth() + 3);

  const fromStr = from.toISOString().split("T")[0];
  const toStr = to.toISOString().split("T")[0];

  try {
    const res = await fetch(`api.php?action=availability&vehicleId=${id}&from=${fromStr}&to=${toStr}`);
    const data = await res.json();
    occupiedRanges = data.bookings || [];
    renderCalendar();
    checkSelectedDates();
  } catch {
    availabilityMessage.textContent = "Beschikbaarheid kon niet worden geladen.";
    availabilityMessage.className = "availability-message error";
  }
}

function dateIsBusy(dateStr) {
  return occupiedRanges.some(r => dateStr >= r.start_date && dateStr < r.end_date);
}

function renderCalendar() {
  const wrap = document.getElementById("miniCalendar");
  wrap.innerHTML = "";

  ["Ma","Di","Wo","Do","Vr","Za","Zo"].forEach(d => {
    const el = document.createElement("div");
    el.className = "calendar-label";
    el.textContent = d;
    wrap.appendChild(el);
  });

  const now = new Date();
  const year = now.getFullYear();
  const month = now.getMonth();
  const first = new Date(year, month, 1);
  const last = new Date(year, month + 1, 0);
  const mondayIndex = (first.getDay() + 6) % 7;

  for (let i = 0; i < mondayIndex; i++) {
    const blank = document.createElement("div");
    blank.className = "calendar-day muted";
    wrap.appendChild(blank);
  }

  for (let d = 1; d <= last.getDate(); d++) {
    const date = new Date(year, month, d, 12);
    const str = date.toISOString().split("T")[0];
    const el = document.createElement("div");
    el.className = "calendar-day";
    el.textContent = d;
    if (dateIsBusy(str)) el.classList.add("busy");
    if (str === new Date().toISOString().split("T")[0]) el.classList.add("today");
    wrap.appendChild(el);
  }
}

function checkSelectedDates() {
  availabilityMessage.textContent = "";
  availabilityMessage.className = "availability-message";

  if (!startDate.value || !endDate.value) return false;
  if (endDate.value <= startDate.value) {
    availabilityMessage.textContent = "De retourdatum moet na de ophaaldatum liggen.";
    availabilityMessage.classList.add("error");
    return false;
  }

  const overlap = occupiedRanges.some(r => !(endDate.value <= r.start_date || startDate.value >= r.end_date));
  if (overlap) {
    availabilityMessage.textContent = "Deze periode overlapt met een bestaande reservering.";
    availabilityMessage.classList.add("error");
    return false;
  }

  availabilityMessage.textContent = "Deze bus lijkt beschikbaar in de gekozen periode.";
  availabilityMessage.classList.add("ok");
  return true;
}

document.querySelectorAll('input[name="vehicle"]').forEach(radio => {
  radio.addEventListener("change", () => {
    document.querySelectorAll(".vehicle-option").forEach(label => {
      label.classList.toggle("selected", label.contains(radio) && radio.checked);
    });
    loadAvailability();
  });
});

[startDate, endDate].forEach(el => {
  el.addEventListener("change", () => {
    updateSummary();
    checkSelectedDates();
  });
});

document.getElementById("toStep2").addEventListener("click", () => {
  if (!startDate.value || !endDate.value) {
    availabilityMessage.textContent = "Kies eerst een ophaal- en retourdatum.";
    availabilityMessage.className = "availability-message error";
    return;
  }
  if (!checkSelectedDates()) return;
  setStep(2);
});

document.getElementById("toStep3").addEventListener("click", () => {
  const firstName = document.getElementById("firstName").value.trim();
  const lastName = document.getElementById("lastName").value.trim();
  const email = document.getElementById("email").value.trim();

  if (!firstName || !lastName || !/^\S+@\S+\.\S+$/.test(email)) {
    alert("Vul je voornaam, achternaam en een geldig e-mailadres in.");
    return;
  }

  const vehicle = selectedVehicle();
  const days = calculateDays();
  const rows = [
    ["Bus", vehicle.name],
    ["Periode", `${dateLabel(startDate.value)} – ${dateLabel(endDate.value)}`],
    ["Duur", `${days} ${days === 1 ? "dag" : "dagen"}`],
    ["Naam", `${firstName} ${lastName}`],
    ["E-mail", email],
    ["Aantal personen", document.getElementById("passengers").value],
    ["Totaal", `€${days * vehicle.rate}`]
  ];

  document.getElementById("reviewCard").innerHTML = rows.map(([k,v]) =>
    `<div class="review-row"><span>${k}</span><strong>${v}</strong></div>`
  ).join("");

  setStep(3);
});

document.querySelectorAll(".back-btn").forEach(btn => {
  btn.addEventListener("click", () => setStep(Number(btn.dataset.back)));
});

document.getElementById("submitBooking").addEventListener("click", async () => {
  const button = document.getElementById("submitBooking");
  const error = document.getElementById("submitError");
  error.classList.remove("show");
  button.disabled = true;
  button.textContent = "Even verwerken...";

  const payload = {
    vehicleId: selectedVehicleId(),
    startDate: startDate.value,
    endDate: endDate.value,
    firstName: document.getElementById("firstName").value.trim(),
    lastName: document.getElementById("lastName").value.trim(),
    email: document.getElementById("email").value.trim(),
    phone: document.getElementById("phone").value.trim(),
    passengers: Number(document.getElementById("passengers").value),
    notes: document.getElementById("notes").value.trim()
  };

  try {
    const res = await fetch("api.php?action=booking", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });
    const data = await res.json();

    if (!res.ok) throw new Error(data.error || "Reservering kon niet worden opgeslagen.");

    document.getElementById("bookingReference").textContent = data.reference;
    if (data.mailDemo) {
      document.getElementById("mailNotice").textContent =
        "De reservering is opgeslagen. Omdat SMTP nog niet is ingesteld, is de bevestigingsmail als demo in de serverterminal weergegeven.";
    }
    setStep(4);
  } catch (e) {
    error.textContent = e.message;
    error.classList.add("show");
  } finally {
    button.disabled = false;
    button.textContent = "Reservering bevestigen";
  }
});

document.getElementById("newBooking").addEventListener("click", () => {
  document.querySelectorAll("#firstName,#lastName,#email,#phone,#notes").forEach(el => el.value = "");
  startDate.value = "";
  endDate.value = "";
  updateSummary();
  loadAvailability();
  setStep(1);
});

updateSummary();
loadAvailability();
