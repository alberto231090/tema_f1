<?php
// ICS Proxy unificato in base al dominio

$host = $_SERVER['HTTP_HOST'];

switch ($host) {
    case 'www.formulapaddock.it':
        $ics_url = 'https://calendar.google.com/calendar/ical/3b4870bcfcdd9169a4ca7f871e98a5ec6013be9ffccc466bb18636b6d973245c%40group.calendar.google.com/public/basic.ics';
        break;
    case 'formula2.formulapaddock.it':
        $ics_url = 'https://calendar.google.com/calendar/ical/4b3cdd269e29b6c227a85e2793dc2a92f66c7fe0ba997670868747eadb0f61cf%40group.calendar.google.com/public/basic.ics';
        break;
    case 'wec.formulapaddock.it':
        $ics_url = 'https://calendar.google.com/calendar/ical/57b53871bfaa55670352af9ad2a22daf9ec24d24d9bcfdad35557ef557cfbc77%40group.calendar.google.com/public/basic.ics';
        break;
    default:
        http_response_code(404);
        echo "Calendario non disponibile per questo dominio.";
        exit;
}

header('Content-Type: text/calendar; charset=utf-8');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ics_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

$response = curl_exec($ch);

if (curl_errno($ch)) {
    http_response_code(500);
    echo "Errore nel recupero del calendario: " . curl_error($ch);
} else {
    echo $response;
}

curl_close($ch);
?>
