# Roadtrip Verhuur – PHP/XAMPP versie

Deze versie is bedoeld om rechtstreeks in de `htdocs` map van XAMPP te plaatsen.

## 1. Installeren

Pak de map bijvoorbeeld uit naar:

```text
C:\xampp\htdocs\roadtrip-verhuur
```

Start in het XAMPP Control Panel:

- Apache

Daarna open je:

```text
http://localhost/roadtrip-verhuur/
```

## 2. SQLite

De database wordt automatisch aangemaakt bij het eerste bezoek / de eerste API-aanroep:

```text
database/bookings.sqlite
```

Je hoeft dus geen MySQL-server te starten.

### Benodigde PHP-extensies

Voor SQLite moeten deze extensies actief zijn:

```ini
extension=pdo_sqlite
extension=sqlite3
```

In recente XAMPP-installaties staan die meestal al aan.

Als dat niet zo is:

1. Open `C:\xampp\php\php.ini`
2. Zoek naar `pdo_sqlite` en `sqlite3`
3. Verwijder eventueel de `;` ervoor
4. Herstart Apache

## 3. Boekingen testen

Maak via de website een reservering.

De reservering komt in:

```text
database/bookings.sqlite
```

De beschikbaarheidskalender leest de reserveringen vervolgens rechtstreeks uit deze database.

## 4. E-mail op localhost

De site gebruikt standaard PHP `mail()`.

Op een standaard lokale XAMPP-installatie is mail meestal nog niet gekoppeld aan een echte SMTP-server. Daarom wordt elke bevestigingsmail ook gelogd in:

```text
logs/mail.log
```

Zo kun je lokaal het hele boekingsproces testen zonder eerst e-mail te configureren.

Voor productie raad ik SMTP aan via bijvoorbeeld PHPMailer. Dat kunnen we in een volgende stap toevoegen.

## 5. Bestanden

```text
roadtrip-verhuur/
├── index.php
├── api.php
├── config.php
├── database.php
├── mail.php
├── .htaccess
├── css/
│   └── style.css
├── js/
│   ├── main.js
│   └── booking.js
├── database/
└── logs/
```

## 6. Schrijfrechten

Apache/PHP moet schrijfrechten hebben op:

```text
database/
logs/
```

Op Windows/XAMPP is dit lokaal meestal automatisch in orde.

## 7. Belangrijk voor live gebruik

Voor een echte publieke website moeten we daarna nog onder andere toevoegen:

- SMTP via PHPMailer
- beheeromgeving voor boekingen
- prijzen / borg / kilometerbeleid
- algemene voorwaarden en privacyverklaring
- spambeveiliging / rate limiting
- echte foto's van de bussen
- eventueel handmatige blokkeringen in de kalender
- annuleren / wijzigen van reserveringen
