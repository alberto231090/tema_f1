<?php
/*
Template Name: Classifica Unificata F1 + WEC
*/

$host = $_SERVER['HTTP_HOST'];

if (strpos($host, 'wec') !== false) {
    $url = 'https://www.fiawec.com/en/manufacturers-classification/34';
    $title = 'Classifica WEC';
    $verify_ssl = true;
    $is_wec = true;
} else {
    $url = 'https://www.formula1.com/en/results/2025/drivers';
    $title = 'Classifica Piloti F1';
    $verify_ssl = false;
    $is_wec = false;
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
    echo "<p style='text-align:center;'>❌ Errore nel recupero della pagina.</p>";
    return;
}

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

if ($is_wec) {
    $rows = $xpath->query("//table[contains(@class,'table-standing')]/tbody/tr");

    if ($rows->length == 0) {
        echo "<p style='text-align:center;'>⚠️ Nessuna classifica trovata.</p>";
    } else {
        echo "<table class='f1-standings'>";
        echo "<tr><th>Team</th><th>Punti</th></tr>";

        foreach ($rows as $row) {
            $cols = $row->getElementsByTagName('td');
            $team = trim($cols->item(1)->nodeValue);
            $points = trim($cols->item(2)->nodeValue);
            echo "<tr><td>$team</td><td>$points</td></tr>";
        }

        echo "</table>";
    }

} else {
    // F1 PILOTI
    $rows = $xpath->query("//table[contains(@class,'f1-table f1-table-with-data w-full')]/tbody/tr");

    if ($rows->length == 0) {
        echo "<p style='text-align:center;'>⚠️ Nessuna classifica trovata.</p>";
    } else {
        echo "<table class='f1-standings'>";
        echo "<tr><th>Pilota</th><th>Punti</th></tr>";

        foreach ($rows as $row) {
            $cols = $row->getElementsByTagName('td');
            $driver_span = $cols->item(1)->getElementsByTagName('span');
            $driver = trim($driver_span->item(0)->nodeValue).' '.trim($driver_span->item(1)->nodeValue);
            $points = trim($cols->item(4)->nodeValue);
            echo "<tr><td>$driver</td><td>$points</td></tr>";
        }

        echo "</table>";
    }

    // F1 COSTRUTTORI
    echo "<h2 style='text-align:center;'>Classifica Costruttori F1</h2>";

    $url_team = 'https://www.formula1.com/en/results/2025/team';

    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $url_team);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch2, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/5.0');

    $html2 = curl_exec($ch2);
    curl_close($ch2);

    if (!$html2) {
        echo "<p style='text-align:center;'>❌ Errore nel recupero della classifica costruttori.</p>";
    } else {
        $dom2 = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom2->loadHTML($html2);
        $xpath2 = new DOMXPath($dom2);

        $rows2 = $xpath2->query("//table[contains(@class,'f1-table f1-table-with-data w-full')]/tbody/tr");

        if ($rows2->length == 0) {
            echo "<p style='text-align:center;'>⚠️ Nessuna classifica costruttori trovata.</p>";
        } else {
            echo "<table class='f1-standings'>";
            echo "<tr><th>Team</th><th>Punti</th></tr>";

            foreach ($rows2 as $row) {
                $cols = $row->getElementsByTagName('td');
                $team = trim($cols->item(1)->nodeValue);
                $points = trim($cols->item(2)->nodeValue);
                echo "<tr><td>$team</td><td>$points</td></tr>";
            }

            echo "</table>";
        }
    }
}
?>
