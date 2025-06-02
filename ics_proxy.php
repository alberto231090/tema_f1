<?php
// ICS Proxy aggiornato con URL corretto al calendario F1

$ics_url = 'https://calendar.google.com/calendar/ical/3b4870bcfcdd9169a4ca7f871e98a5ec6013be9ffccc466bb18636b6d973245c%40group.calendar.google.com/public/basic.ics';

header('Content-Type: text/calendar; charset=utf-8');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ics_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // evita problemi SSL su hosting limitati
$data = curl_exec($ch);
curl_close($ch);

if (!$data) {
    http_response_code(500);
    echo 'Errore nel recupero del file ICS.';
    exit;
}

echo $data;
?>
