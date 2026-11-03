<?php
declare(strict_types=1);

function getDb(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dbDir = __DIR__ . '/database';
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0775, true);
    }

    $pdo = new PDO('sqlite:' . $dbDir . '/bookings.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec('PRAGMA journal_mode = WAL');

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS vehicles (
            id INTEGER PRIMARY KEY,
            slug TEXT UNIQUE NOT NULL,
            name TEXT NOT NULL,
            seats INTEGER NOT NULL,
            daily_price REAL NOT NULL,
            subtitle TEXT,
            features TEXT
        );

        CREATE TABLE IF NOT EXISTS bookings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            reference TEXT UNIQUE NOT NULL,
            vehicle_id INTEGER NOT NULL,
            start_date TEXT NOT NULL,
            end_date TEXT NOT NULL,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT,
            passengers INTEGER NOT NULL,
            notes TEXT,
            total_price REAL NOT NULL,
            status TEXT NOT NULL DEFAULT 'confirmed',
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
        );

        CREATE INDEX IF NOT EXISTS idx_bookings_vehicle_dates
        ON bookings(vehicle_id, start_date, end_date);
    ");

    $insert = $pdo->prepare("
        INSERT OR IGNORE INTO vehicles
        (id, slug, name, seats, daily_price, subtitle, features)
        VALUES (:id, :slug, :name, :seats, :daily_price, :subtitle, :features)
    ");

    $vehicles = [
        [
            'id' => 1,
            'slug' => 'transporter-ocean',
            'name' => 'VW Transporter Ocean',
            'seats' => 8,
            'daily_price' => 119,
            'subtitle' => 'Comfortabel op reis met familie of vrienden',
            'features' => json_encode([
                '8 zitplaatsen',
                'Airconditioning',
                'Cruise control',
                'Veel bagageruimte',
                'Apple CarPlay / Android Auto',
                'Automaat'
            ], JSON_UNESCAPED_UNICODE)
        ],
        [
            'id' => 2,
            'slug' => 'transporter-adventure',
            'name' => 'VW Transporter Adventure',
            'seats' => 8,
            'daily_price' => 129,
            'subtitle' => 'Extra luxe voor langere roadtrips en vakanties',
            'features' => json_encode([
                '8 zitplaatsen',
                'Airconditioning',
                'Adaptieve cruise control',
                'Extra bagageruimte',
                'Apple CarPlay / Android Auto',
                'Automaat'
            ], JSON_UNESCAPED_UNICODE)
        ]
    ];

    foreach ($vehicles as $vehicle) {
        $insert->execute($vehicle);
    }

    return $pdo;
}
