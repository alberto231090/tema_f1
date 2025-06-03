<?php
/*
Template Name: Classifica Piloti Unificata (cURL)
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

echo "<h2 style=\"text-align:center;\">$title</h2>";

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
?>
