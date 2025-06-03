<?php
/*
Template Name: Classifica Unificata F1 + WEC
*/

function fetch_html($url, $verify_ssl = true) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify_ssl);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/114.0.0.0 Safari/537.36');
    $html = curl_exec($ch);
    curl_close($ch);
    return $html ?: false;
}

function parse_wec_table($html) {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $rows = $xpath->query("//table[contains(@class,'table-standing')]/tbody/tr");
    if ($rows->length === 0) return false;

    $output = "<table class='f1-standings'><tr><th>Team</th><th>Punti</th></tr>";
    foreach ($rows as $row) {
        $cols = $row->getElementsByTagName('td');
        if ($cols->length >= 3) {
            $team = trim($cols->item(1)->nodeValue);
            $points = trim($cols->item(2)->nodeValue);
            if ($team !== '') {
                $output .= "<tr><td>$team</td><td>$points</td></tr>";
            }
        }
    }
    $output .= "</table>";
    return $output;
}

function parse_f1_piloti($html) {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $rows = $xpath->query("//table[contains(@class,'f1-table f1-table-with-data w-full')]/tbody/tr");
    if ($rows->length === 0) return false;

    $output = "<table class='f1-standings'><tr><th>Pilota</th><th>Punti</th></tr>";
    foreach ($rows as $row) {
        $cols = $row->getElementsByTagName('td');
        if ($cols->length >= 5) {
            $driver_span = $cols->item(1)->getElementsByTagName('span');
            if ($driver_span->length >= 2) {
                $driver = trim($driver_span->item(0)->nodeValue) . ' ' . trim($driver_span->item(1)->nodeValue);
                $points = trim($cols->item(4)->nodeValue);
                $output .= "<tr><td>$driver</td><td>$points</td></tr>";
            }
        }
    }
    $output .= "</table>";
    return $output;
}

function parse_f1_team($html) {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $rows = $xpath->query("//table[contains(@class,'f1-table f1-table-with-data w-full')]/tbody/tr");
    if ($rows->length === 0) return false;

    $output = "<table class='f1-standings'><tr><th>Team</th><th>Punti</th></tr>";
    foreach ($rows as $row) {
        $cols = $row->getElementsByTagName('td');
        if ($cols->length >= 3) {
            $team = trim($cols->item(1)->nodeValue);
            $points = trim($cols->item(2)->nodeValue);
            $output .= "<tr><td>$team</td><td>$points</td></tr>";
        }
    }
    $output .= "</table>";
    return $output;
}

// --- Scelta logica WEC o F1 ---
$host = $_SERVER['HTTP_HOST'];
$is_wec = strpos($host, 'wec') !== false;
$host = $_SERVER['HTTP_HOST'];

// Se siamo su formula2.formulapaddock.it, non mostrare nulla
if (strpos($host, 'formula2.formulapaddock.it') !== false) {
    return;
}

if ($is_wec) {
    $url = 'https://www.fiawec.com/en/manufacturers-classification/34';
    $title = 'Classifica WEC';
    $verify_ssl = true;
} else {
    $url = 'https://www.formula1.com/en/results/2025/drivers';
    $title = 'Classifica Piloti F1';
    $verify_ssl = false;
}

// --- Stampa titolo pagina ---
echo "<h2 style='text-align:center;'>$title</h2>";

// --- Recupero e parsing ---
$html = fetch_html($url, $verify_ssl);

if (!$html) {
    echo "<p style='text-align:center;'>❌ Errore nel recupero della pagina.</p>";
    return;
}

if ($is_wec) {
    $wec_output = parse_wec_table($html);
    echo $wec_output ?: "<p style='text-align:center;'>⚠️ Nessuna classifica trovata.</p>";
} else {
    // F1 Piloti
    $piloti_output = parse_f1_piloti($html);
    echo $piloti_output ?: "<p style='text-align:center;'>⚠️ Nessuna classifica piloti trovata.</p>";

    // F1 Team
    echo "<h2 style='text-align:center;'>Classifica Costruttori F1</h2>";
    $team_html = fetch_html('https://www.formula1.com/en/results/2025/team', false);

    if (!$team_html) {
        echo "<p style='text-align:center;'>❌ Errore nel recupero della classifica costruttori.</p>";
    } else {
        $team_output = parse_f1_team($team_html);
        echo $team_output ?: "<p style='text-align:center;'>⚠️ Nessuna classifica costruttori trovata.</p>";
    }
}
?>
