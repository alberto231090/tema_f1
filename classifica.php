<?php
/*
Template Name: Classifica Piloti Unificata (cURL) con Stile Personalizzato
*/

$host = $_SERVER['HTTP_HOST'];

switch ($host) {
    case 'www.formulapaddock.it':
        $url = 'https://www.formula1.com/en/results/2025/drivers';
        $title = 'Classifica Piloti F1';
        $verify_ssl = false;
        break;
    case 'wec.formulapaddock.it':
        $url = 'https://www.fiawec.com/en/manufacturers-classification/34';
        $title = 'Classifica WEC';
        $verify_ssl = true;
        break;
    default:
        echo 'Classifica non disponibile per questo dominio.';
        exit;
}

echo "<div class='driver-standings-wrapper'>";
echo "<h2>$title</h2>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify_ssl);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

$html = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Errore nella richiesta: ' . curl_error($ch);
} else {
    echo $html;
}
curl_close($ch);

echo "</div>";
?>

<style>
.driver-standings-wrapper {
    max-width: 900px;
    margin: 2rem auto;
    padding: 1rem;
    background-color: #000;
    color: #fff;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
    font-family: 'Arial', sans-serif;
}

.driver-standings-wrapper h2 {
    text-align: center;
    color: #f1c40f;
    font-size: 2rem;
}

.driver-standings-wrapper table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.driver-standings-wrapper th,
.driver-standings-wrapper td {
    border: 1px solid #444;
    padding: 10px;
    text-align: left;
}

.driver-standings-wrapper th {
    background-color: #e10600;
    color: #fff;
    font-weight: bold;
}

.driver-standings-wrapper tr:nth-child(even) {
    background-color: #111;
}

.driver-standings-wrapper tr:hover {
    background-color: #222;
}
</style>
