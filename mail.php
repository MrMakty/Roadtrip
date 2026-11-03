<?php
declare(strict_types=1);

function sendBookingConfirmation(array $booking, array $config): array
{
    $subject = 'Bevestiging reservering ' . $booking['reference'];

    $message = "Beste {$booking['first_name']},\n\n";
    $message .= "Bedankt voor je reservering.\n\n";
    $message .= "Reserveringsnummer: {$booking['reference']}\n";
    $message .= "Bus: {$booking['vehicle_name']}\n";
    $message .= "Van: {$booking['start_date']}\n";
    $message .= "Tot: {$booking['end_date']}\n";
    $message .= "Aantal personen: {$booking['passengers']}\n";
    $message .= "Totaal: € " . number_format((float)$booking['total_price'], 2, ',', '.') . "\n\n";
    $message .= "Er hoeft op dit moment nog niet online betaald te worden.\n";
    $message .= "Wij nemen indien nodig persoonlijk contact met je op.\n\n";
    $message .= "Met vriendelijke groet,\n";
    $message .= $config['site_name'] . "\n";

    $fromEmail = $config['mail']['from_email'];
    $fromName  = $config['mail']['from_name'];

    $headers = [];
    $headers[] = 'From: ' . $fromName . ' <' . $fromEmail . '>';
    $headers[] = 'Reply-To: ' . $fromEmail;
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';

    $sent = false;

    // Onderdrukt waarschuwingen op lokale XAMPP-installaties zonder mailserver.
    try {
        $sent = @mail(
            $booking['email'],
            '=?UTF-8?B?' . base64_encode($subject) . '?=',
            $message,
            implode("\r\n", $headers)
        );
    } catch (Throwable $e) {
        $sent = false;
    }

    // Altijd loggen, zodat je tijdens lokaal testen kunt controleren wat verstuurd zou zijn.
    $logDir = __DIR__ . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0775, true);
    }

    $log = "[" . date('Y-m-d H:i:s') . "]\n";
    $log .= "Aan: {$booking['email']}\n";
    $log .= "Onderwerp: {$subject}\n";
    $log .= $message . "\n";
    $log .= "PHP mail(): " . ($sent ? 'VERZONDEN' : 'NIET VERZONDEN / LOKALE DEMO') . "\n";
    $log .= str_repeat('-', 60) . "\n";

    file_put_contents($logDir . '/mail.log', $log, FILE_APPEND);

    return [
        'sent' => $sent,
        'demo' => !$sent
    ];
}
