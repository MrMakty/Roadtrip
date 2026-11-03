<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Opstart met matthiue</title>
  <meta name="description" content="Huur een comfortabele 8-persoons VW Transporter voor vakantie, roadtrip of weekend weg.">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="site-header">
    <div class="container nav-wrap">
      <a href="#home" class="brand" aria-label="Roadtrip Verhuur home">
        <span class="brand-mark">RV</span>
        <span>
          <strong>Roadtrip</strong>
          <small>Verhuur</small>
        </span>
      </a>

      <button class="menu-toggle" aria-label="Menu openen" aria-expanded="false">☰</button>

      <nav class="main-nav">
        <a href="#home">Home</a>
        <a href="#bussen">Onze bussen</a>
        <a href="#beschikbaarheid">Beschikbaarheid</a>
        <a href="#werking">Zo werkt het</a>
        <a href="#faq">FAQ</a>
        <a href="#contact">Contact</a>
        <a class="btn btn-primary btn-small" href="#beschikbaarheid">Reserveer</a>
      </nav>
    </div>
  </header>

  <main>
    <section class="hero" id="home">
      <div class="hero-overlay"></div>
      <div class="container hero-content">
        <div class="hero-copy">
          <span class="eyebrow">8-persoons VW Transporters</span>
          <h1>Samen op reis.<br>Alle ruimte voor avontuur.</h1>
          <p>Comfortabel met familie of vrienden op vakantie, een weekend weg of een lange roadtrip door Europa.</p>
          <div class="hero-actions">
            <a class="btn btn-primary" href="#beschikbaarheid">Bekijk beschikbaarheid</a>
            <a class="btn btn-ghost" href="#bussen">Ontdek onze bussen</a>
          </div>
        </div>

        <form class="quick-search card" id="quickSearch">
          <div>
            <label for="quickStart">Ophaaldatum</label>
            <input type="date" id="quickStart" required>
          </div>
          <div>
            <label for="quickEnd">Retourdatum</label>
            <input type="date" id="quickEnd" required>
          </div>
          <button class="btn btn-primary" type="submit">Bekijk beschikbaarheid</button>
        </form>
      </div>
    </section>

    <section class="section intro">
      <div class="container">
        <div class="section-heading centered">
          <span class="eyebrow dark">Onze bussen</span>
          <h2>Kies jouw reisgenoot</h2>
          <p>Twee comfortabele Volkswagen Transporters, allebei geschikt voor maximaal acht personen.</p>
        </div>

        <div class="vehicle-grid" id="bussen">
          <article class="vehicle-card" data-vehicle-card="1">
            <div class="vehicle-image vehicle-image-one">
              <span class="vehicle-badge">Populair</span>
            </div>
            <div class="vehicle-content">
              <div class="vehicle-title-row">
                <div>
                  <h3>VW Transporter Ocean</h3>
                  <p>Comfortabel & compleet</p>
                </div>
                <div class="price"><strong>€119</strong><span>/ dag</span></div>
              </div>
              <div class="feature-row">
                <span>8 personen</span><span>Automaat</span><span>Airco</span>
              </div>
              <button class="btn btn-outline vehicle-book-btn" data-vehicle="1">Bekijk beschikbaarheid</button>
            </div>
          </article>

          <article class="vehicle-card" data-vehicle-card="2">
            <div class="vehicle-image vehicle-image-two">
              <span class="vehicle-badge alt">Extra luxe</span>
            </div>
            <div class="vehicle-content">
              <div class="vehicle-title-row">
                <div>
                  <h3>VW Transporter Adventure</h3>
                  <p>Voor de lange roadtrip</p>
                </div>
                <div class="price"><strong>€129</strong><span>/ dag</span></div>
              </div>
              <div class="feature-row">
                <span>8 personen</span><span>Automaat</span><span>ACC</span>
              </div>
              <button class="btn btn-outline vehicle-book-btn" data-vehicle="2">Bekijk beschikbaarheid</button>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section class="section booking-section" id="beschikbaarheid">
      <div class="container">
        <div class="section-heading">
          <span class="eyebrow dark">Boeken</span>
          <h2>Plan jouw volgende reis</h2>
          <p>Selecteer een bus en kies je periode. Gereserveerde dagen worden automatisch geblokkeerd.</p>
        </div>

        <div class="booking-shell">
          <div class="steps" aria-label="Boekingsstappen">
            <div class="step active" data-step-indicator="1"><b>1</b><span>Periode</span></div>
            <div class="step" data-step-indicator="2"><b>2</b><span>Gegevens</span></div>
            <div class="step" data-step-indicator="3"><b>3</b><span>Controle</span></div>
            <div class="step" data-step-indicator="4"><b>4</b><span>Klaar</span></div>
          </div>

          <div class="booking-layout">
            <div class="booking-main">
              <section class="booking-step active" data-step="1">
                <h3>Kies bus en periode</h3>
                <div class="vehicle-selector">
                  <label class="vehicle-option selected">
                    <input type="radio" name="vehicle" value="1" checked>
                    <span><strong>Transporter Ocean</strong><small>€119 per dag</small></span>
                  </label>
                  <label class="vehicle-option">
                    <input type="radio" name="vehicle" value="2">
                    <span><strong>Transporter Adventure</strong><small>€129 per dag</small></span>
                  </label>
                </div>

                <div class="date-grid">
                  <div>
                    <label for="startDate">Ophaaldatum</label>
                    <input type="date" id="startDate">
                  </div>
                  <div>
                    <label for="endDate">Retourdatum</label>
                    <input type="date" id="endDate">
                  </div>
                </div>

                <div id="availabilityMessage" class="availability-message"></div>

                <div class="calendar-head">
                  <h4>Beschikbaarheidsoverzicht</h4>
                  <div class="calendar-legend">
                    <span><i class="legend-dot free"></i> Beschikbaar</span>
                    <span><i class="legend-dot busy"></i> Bezet</span>
                  </div>
                </div>
                <div class="mini-calendar" id="miniCalendar"></div>

                <div class="step-actions">
                  <span></span>
                  <button class="btn btn-primary" id="toStep2">Verder</button>
                </div>
              </section>

              <section class="booking-step" data-step="2">
                <h3>Jouw gegevens</h3>
                <div class="form-grid">
                  <div>
                    <label for="firstName">Voornaam</label>
                    <input id="firstName" autocomplete="given-name">
                  </div>
                  <div>
                    <label for="lastName">Achternaam</label>
                    <input id="lastName" autocomplete="family-name">
                  </div>
                  <div>
                    <label for="email">E-mailadres</label>
                    <input type="email" id="email" autocomplete="email">
                  </div>
                  <div>
                    <label for="phone">Telefoonnummer</label>
                    <input id="phone" autocomplete="tel">
                  </div>
                  <div>
                    <label for="passengers">Aantal personen</label>
                    <select id="passengers">
                      <option value="1">1 persoon</option>
                      <option value="2">2 personen</option>
                      <option value="3">3 personen</option>
                      <option value="4">4 personen</option>
                      <option value="5">5 personen</option>
                      <option value="6">6 personen</option>
                      <option value="7">7 personen</option>
                      <option value="8" selected>8 personen</option>
                    </select>
                  </div>
                  <div class="full">
                    <label for="notes">Opmerking <span>(optioneel)</span></label>
                    <textarea id="notes" rows="4" placeholder="Bijvoorbeeld aankomsttijd of een vraag over bagage."></textarea>
                  </div>
                </div>

                <div class="step-actions">
                  <button class="btn btn-text back-btn" data-back="1">← Terug</button>
                  <button class="btn btn-primary" id="toStep3">Controleer reservering</button>
                </div>
              </section>

              <section class="booking-step" data-step="3">
                <h3>Controleer je reservering</h3>
                <div class="review-card" id="reviewCard"></div>
                <div class="notice">
                  <strong>Nog geen betaling nodig.</strong>
                  <p>Na bevestiging ontvang je direct een e-mail met je reserveringsgegevens.</p>
                </div>
                <div id="submitError" class="error-box"></div>
                <div class="step-actions">
                  <button class="btn btn-text back-btn" data-back="2">← Wijzigen</button>
                  <button class="btn btn-primary" id="submitBooking">Reservering bevestigen</button>
                </div>
              </section>

              <section class="booking-step" data-step="4">
                <div class="success-state">
                  <div class="success-icon">✓</div>
                  <span class="eyebrow dark">Reservering ontvangen</span>
                  <h3>Je roadtrip kan beginnen.</h3>
                  <p>Je reserveringsnummer is <strong id="bookingReference"></strong>.</p>
                  <p id="mailNotice">De bevestiging is naar je e-mailadres verzonden.</p>
                  <button class="btn btn-outline" id="newBooking">Nieuwe reservering</button>
                </div>
              </section>
            </div>

            <aside class="booking-summary">
              <span class="eyebrow dark">Samenvatting</span>
              <h3 id="summaryVehicle">VW Transporter Ocean</h3>
              <dl>
                <div><dt>Periode</dt><dd id="summaryDates">Nog niet gekozen</dd></div>
                <div><dt>Duur</dt><dd id="summaryDays">—</dd></div>
                <div><dt>Tarief</dt><dd id="summaryRate">€119 / dag</dd></div>
              </dl>
              <div class="summary-total">
                <span>Geschat totaal</span>
                <strong id="summaryTotal">€0</strong>
              </div>
              <small>Definitieve afspraken kunnen later persoonlijk worden bevestigd.</small>
            </aside>
          </div>
        </div>
      </div>
    </section>

    <section class="section benefits" id="werking">
      <div class="container">
        <div class="section-heading centered">
          <span class="eyebrow dark">Eenvoudig geregeld</span>
          <h2>Van plan naar vertrek in vier stappen</h2>
        </div>
        <div class="benefit-grid">
          <article><b>01</b><h3>Kies je periode</h3><p>Bekijk direct wanneer jouw favoriete bus beschikbaar is.</p></article>
          <article><b>02</b><h3>Reserveer online</h3><p>Vul je gegevens in en controleer rustig je aanvraag.</p></article>
          <article><b>03</b><h3>Ontvang bevestiging</h3><p>Je krijgt automatisch een bevestigingsmail met reserveringsnummer.</p></article>
          <article><b>04</b><h3>Stap in & vertrek</h3><p>De laatste praktische details stemmen we persoonlijk met je af.</p></article>
        </div>
      </div>
    </section>

    <section class="section inspiration">
      <div class="container">
        <div class="section-heading">
          <span class="eyebrow dark">Roadtrip inspiratie</span>
          <h2>Waar gaat de volgende reis naartoe?</h2>
        </div>
        <div class="trip-grid">
          <article class="trip-card trip-one"><div><span>Weekend</span><h3>Ardennen</h3></div></article>
          <article class="trip-card trip-two"><div><span>Roadtrip</span><h3>Frankrijk</h3></div></article>
          <article class="trip-card trip-three"><div><span>Zomervakantie</span><h3>Italië</h3></div></article>
        </div>
      </div>
    </section>

    <section class="section faq-section" id="faq">
      <div class="container narrow">
        <div class="section-heading centered">
          <span class="eyebrow dark">Veelgestelde vragen</span>
          <h2>Goed om te weten</h2>
        </div>
        <details>
          <summary>Moet ik direct online betalen?</summary>
          <p>Nee. Op deze demo wordt alleen de reservering vastgelegd. Betaling kan later persoonlijk worden afgesproken.</p>
        </details>
        <details>
          <summary>Hoeveel personen kunnen mee?</summary>
          <p>Beide VW Transporters hebben acht zitplaatsen inclusief bestuurder.</p>
        </details>
        <details>
          <summary>Kan ik zien wanneer een bus al geboekt is?</summary>
          <p>Ja. De kalender haalt actuele reserveringen op uit de SQLite-database en toont bezette dagen.</p>
        </details>
      </div>
    </section>

    <section class="contact-section" id="contact">
      <div class="container contact-inner">
        <div>
          <span class="eyebrow">Persoonlijk contact</span>
          <h2>Een vraag over je reis?</h2>
          <p>Neem gerust contact op. We denken graag mee over bagage, route en praktische zaken.</p>
        </div>
        <div class="contact-card">
          <span>Telefoon</span><strong>06 - 12 34 56 78</strong>
          <span>E-mail</span><strong>info@roadtripverhuur.nl</strong>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <div class="container footer-inner">
      <div class="brand footer-brand">
        <span class="brand-mark">RV</span>
        <span><strong>Roadtrip</strong><small>Verhuur</small></span>
      </div>
      <p>© <span id="year"></span> Roadtrip Verhuur · Demo website</p>
    </div>
  </footer>

  <script src="js/main.js"></script>
  <script src="js/booking.js"></script>
</body>
</html>
