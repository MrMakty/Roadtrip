document.getElementById("year").textContent = new Date().getFullYear();

const toggle = document.querySelector(".menu-toggle");
const nav = document.querySelector(".main-nav");

toggle.addEventListener("click", () => {
  const open = nav.classList.toggle("open");
  toggle.setAttribute("aria-expanded", String(open));
});

nav.querySelectorAll("a").forEach(a => a.addEventListener("click", () => nav.classList.remove("open")));

const todayString = new Date().toISOString().split("T")[0];
["quickStart", "quickEnd", "startDate", "endDate"].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.min = todayString;
});

document.getElementById("quickSearch").addEventListener("submit", (e) => {
  e.preventDefault();
  document.getElementById("startDate").value = document.getElementById("quickStart").value;
  document.getElementById("endDate").value = document.getElementById("quickEnd").value;
  document.getElementById("beschikbaarheid").scrollIntoView({ behavior: "smooth" });
  document.getElementById("startDate").dispatchEvent(new Event("change"));
});

document.querySelectorAll(".vehicle-book-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    const id = btn.dataset.vehicle;
    const radio = document.querySelector(`input[name="vehicle"][value="${id}"]`);
    radio.checked = true;
    radio.dispatchEvent(new Event("change"));
    document.getElementById("beschikbaarheid").scrollIntoView({ behavior: "smooth" });
  });
});
