<?php
// ICS Proxy aggiornato con URL corretto al calendario F1

$ics_url = 'https://calendar.google.com/calendar/ical/57b53871bfaa55670352af9ad2a22daf9ec24d24d9bcfdad35557ef557cfbc77%40group.calendar.google.com/public/basic.ics';

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
