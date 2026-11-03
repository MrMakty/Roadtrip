<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

$config = require __DIR__ . '/config.php';
date_default_timezone_set($config['timezone'] ?? 'Europe/Amsterdam');

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/mail.php';

function jsonResponse(array $data, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function validDate(string $date): bool
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function getVehicle(PDO $db, int $id): ?array
{
    $stmt = $db->prepare('SELECT * FROM vehicles WHERE id = ?');
    $stmt->execute([$id]);
    $vehicle = $stmt->fetch();
    return $vehicle ?: null;
}

function isAvailable(PDO $db, int $vehicleId, string $startDate, string $endDate): bool
{
    $stmt = $db->prepare("
        SELECT 1
        FROM bookings
        WHERE vehicle_id = ?
          AND status = 'confirmed'
          AND NOT (end_date <= ? OR start_date >= ?)
        LIMIT 1
    ");
    $stmt->execute([$vehicleId, $startDate, $endDate]);
    return !$stmt->fetchColumn();
}

try {
    $db = getDb();
    $action = $_GET['action'] ?? '';

    if ($action === 'vehicles') {
        $rows = $db->query('SELECT * FROM vehicles ORDER BY id')->fetchAll();

        foreach ($rows as &$row) {
            $row['features'] = json_decode($row['features'] ?? '[]', true);
        }

        jsonResponse($rows);
    }

    if ($action === 'availability') {
        $vehicleId = (int)($_GET['vehicleId'] ?? 0);
        $from = (string)($_GET['from'] ?? '');
        $to = (string)($_GET['to'] ?? '');

        if (!$vehicleId || !validDate($from) || !validDate($to)) {
            jsonResponse(['error' => 'Ongeldige aanvraag.'], 400);
        }

        if (!getVehicle($db, $vehicleId)) {
            jsonResponse(['error' => 'Bus niet gevonden.'], 404);
        }

        $stmt = $db->prepare("
            SELECT start_date, end_date
            FROM bookings
            WHERE vehicle_id = ?
              AND status = 'confirmed'
              AND NOT (end_date <= ? OR start_date >= ?)
            ORDER BY start_date
        ");
        $stmt->execute([$vehicleId, $from, $to]);

        jsonResponse([
            'vehicleId' => $vehicleId,
            'bookings' => $stmt->fetchAll()
        ]);
    }

    if ($action === 'booking' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $payload = json_decode(file_get_contents('php://input'), true);

        if (!is_array($payload)) {
            jsonResponse(['error' => 'Ongeldige invoer.'], 400);
        }

        $vehicleId = (int)($payload['vehicleId'] ?? 0);
        $startDate = trim((string)($payload['startDate'] ?? ''));
        $endDate = trim((string)($payload['endDate'] ?? ''));
        $firstName = trim((string)($payload['firstName'] ?? ''));
        $lastName = trim((string)($payload['lastName'] ?? ''));
        $email = trim((string)($payload['email'] ?? ''));
        $phone = trim((string)($payload['phone'] ?? ''));
        $passengers = (int)($payload['passengers'] ?? 0);
        $notes = trim((string)($payload['notes'] ?? ''));

        $vehicle = getVehicle($db, $vehicleId);

        if (!$vehicle || !validDate($startDate) || !validDate($endDate) || $endDate <= $startDate) {
            jsonResponse(['error' => 'Controleer de bus en gekozen periode.'], 400);
        }

        if ($firstName === '' || $lastName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            jsonResponse(['error' => 'Vul je naam en een geldig e-mailadres in.'], 400);
        }

        if ($passengers < 1 || $passengers > (int)$vehicle['seats']) {
            jsonResponse(['error' => 'Ongeldig aantal personen.'], 400);
        }

        $today = new DateTime('today');
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        if ($start < $today) {
            jsonResponse(['error' => 'De startdatum kan niet in het verleden liggen.'], 400);
        }

        if (!isAvailable($db, $vehicleId, $startDate, $endDate)) {
            jsonResponse(['error' => 'Deze bus is helaas niet meer beschikbaar in de gekozen periode.'], 409);
        }

        $days = (int)$start->diff($end)->days;
        $totalPrice = $days * (float)$vehicle['daily_price'];
        $reference = 'RT-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));

        // Transactie + tweede beschikbaarheidscheck beperkt dubbele boekingen.
        $db->beginTransaction();

        if (!isAvailable($db, $vehicleId, $startDate, $endDate)) {
            $db->rollBack();
            jsonResponse(['error' => 'Deze bus is zojuist door iemand anders gereserveerd.'], 409);
        }

        $stmt = $db->prepare("
            INSERT INTO bookings (
                reference, vehicle_id, start_date, end_date,
                first_name, last_name, email, phone,
                passengers, notes, total_price, status
            ) VALUES (
                :reference, :vehicle_id, :start_date, :end_date,
                :first_name, :last_name, :email, :phone,
                :passengers, :notes, :total_price, 'confirmed'
            )
        ");

        $stmt->execute([
            ':reference' => $reference,
            ':vehicle_id' => $vehicleId,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':email' => $email,
            ':phone' => $phone,
            ':passengers' => $passengers,
            ':notes' => $notes,
            ':total_price' => $totalPrice
        ]);

        $db->commit();

        $booking = [
            'reference' => $reference,
            'vehicle_name' => $vehicle['name'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'passengers' => $passengers,
            'notes' => $notes,
            'total_price' => $totalPrice
        ];

        $mailStatus = sendBookingConfirmation($booking, $config);

        jsonResponse([
            'success' => true,
            'reference' => $reference,
            'mailDemo' => $mailStatus['demo']
        ], 201);
    }

    jsonResponse(['error' => 'Onbekende API-aanvraag.'], 404);

} catch (Throwable $e) {
    if (isset($db) && $db instanceof PDO && $db->inTransaction()) {
        $db->rollBack();
    }

    error_log($e->getMessage());
    jsonResponse(['error' => 'Er ging iets mis op de server.'], 500);
}
