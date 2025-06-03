<?php
/*
Template Name: Classifica Piloti WEC (cURL)
*/

?>

<h2 style="text-align:center;">Classifica WEC</h2>

<?php
$url = 'https://www.fiawec.com/en/manufacturers-classification/34';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

$html = curl_exec($ch);

if (curl_errno($ch)) {
    echo "<p style='text-align:center;'>❌ Errore nel recupero della pagina: " . curl_error($ch) . "</p>";
    curl_close($ch);
    return;
}
curl_close($ch);

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

$rows = $xpath->query("//table[contains(@class,'table-standing')]/tbody/tr");

if ($rows->length == 0) {
    echo "<p style='text-align:center;'>⚠️ Nessuna classifica trovata.</p>";
} else {
    echo "<table class='f1-standings'>";
    echo "<tr><th>Pilota</th><th>Punti</th></tr>";

    foreach ($rows as $row) {
        $cols = $row->getElementsByTagName('td');
        
        if ($cols->length > 10) {
            $driver = trim($cols->item(1)->nodeValue);
            $points = trim($cols->item(10)->nodeValue);
            echo "<tr><td>$driver</td><td>$points</td></tr>";
        }
    }

    echo "</table>";
}
?>
