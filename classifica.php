<?php
/*
Template Name: Classifica Piloti Unificata (con stile personalizzato)
*/

$host = $_SERVER['HTTP_HOST'];

switch ($host) {
    case 'www.formulapaddock.it':
        $url = 'https://www.formula1.com/en/results/2025/drivers';
        $title = 'Classifica Piloti F1';
        $verify_ssl = false;
        $xpath_query = "//table[contains(@class,'f1-table f1-table-with-data w-full')]/tbody/tr";
        break;
    case 'wec.formulapaddock.it':
        $url = 'https://www.fiawec.com/en/manufacturers-classification/34';
        $title = 'Classifica WEC';
        $verify_ssl = true;
        $xpath_query = "//table[contains(@class,'table-standing')]/tbody/tr";
        break;
    default:
        echo "<p>Classifica non disponibile per questo dominio.</p>";
        exit;
}

echo "<h2 style='text-align:center;'>$title</h2>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify_ssl);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

$html = curl_exec($ch);
curl_close($ch);

if (!$html) {
    echo "<p style='text-align:center;'>❌ Errore nel recupero della classifica.</p>";
    return;
}

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

$rows = $xpath->query($xpath_query);

if ($rows->length == 0) {
    echo "<p style='text-align:center;'>⚠️ Nessuna classifica trovata.</p>";
} else {
    echo "<table class='f1-standings' style='margin: 0 auto; width: 90%; border-collapse: collapse;'>";
    echo "<tr style='background:#e10600;color:#fff;'><th>Pos</th><th>Driver</th><th>Nationality</th><th>Car</th><th>Pts</th></tr>";

    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row->childNodes as $cell) {
            if ($cell->nodeType === XML_ELEMENT_NODE) {
                echo "<td style='padding:8px;border-bottom:1px solid #ccc;text-align:center;'>" . $dom->saveHTML($cell) . "</td>";
            }
        }
        echo "</tr>";
    }

    echo "</table>";
}
?>
