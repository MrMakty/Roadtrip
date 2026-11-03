<?php
declare(strict_types=1);

return [
    'site_name' => 'Roadtrip Verhuur',
    'timezone' => 'Europe/Amsterdam',

    // Mailinstellingen.
    // Standaard gebruikt het systeem PHP mail().
    // Op een lokale XAMPP-installatie is mail vaak niet geconfigureerd.
    // In dat geval wordt de bevestigingsmail ook opgeslagen in logs/mail.log.
    'mail' => [
        'from_email' => 'reservering@roadtripverhuur.nl',
        'from_name'  => 'Roadtrip Verhuur',
    ],
];
