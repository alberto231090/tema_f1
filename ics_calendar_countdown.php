<?php
// ICS Proxy – versione con DEBUG

$ics_url = 'https://calendar.google.com/calendar/ical/3b4870bcfcdd9169a4ca7f81e98a5ec6013be9ffccc46bb18636bd973245c@group.calendar.google.com/public/basic.ics';

header('Content-Type: text/plain; charset=utf-8');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ics_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // in caso il server non abbia CA aggiornate
$data = curl_exec($ch);
$error = curl_error($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo ">>> DEBUG ICS PROXY <<<\n";
echo "HTTP CODE: $code\n";
echo "cURL ERROR: $error\n";
echo "\n---\n\n";

if ($code !== 200 || !$data) {
    echo "Errore nel recupero ICS.\n";
} else {
    echo "ICS scaricato correttamente.\n";
    echo substr($data, 0, 300) . '...'; // Mostra solo primi caratteri
}
?>
