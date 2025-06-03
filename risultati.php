<?php
/*
Template Name: Classifica da URL Formula1.com
*/


$url = get_field('url_classifica');

// Avvia cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

$html = curl_exec($ch);
curl_close($ch);

if (!$html) {
 
//    get_footer();
    return;
}

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

$rows = $xpath->query("//table[contains(@class,'f1-table')]/tbody/tr");

if ($rows->length == 0) {
    echo "<p>⚠️ Nessuna tabella trovata in questa pagina F1.</p>";
} else {
    echo "<h2>Classifica finale</h2><table class='f1-results'>";
    echo "<tr><th>Pos</th><th>Pilota</th><th>Team</th><th>Tempo</th></tr>";

    foreach ($rows as $row) {
        $cols = $row->getElementsByTagName('td');

        // Cattura le colonne in base alla struttura classica: posizione, numero, nome, team, tempo
        $pos = trim($cols->item(0)->nodeValue);
        $pilot_spans = $cols->item(2)->getElementsByTagName('span');
$pilot = trim($pilot_spans->item(0)->nodeValue).' '.trim($pilot_spans->item(1)->nodeValue); // Solo nome completo

        $team = trim($cols->item(3)->nodeValue);
		if(date('w')==0):
        $time = trim($cols->item(5)->nodeValue);
		else:
        $time = trim($cols->item(4)->nodeValue);
endif;
        echo "<tr><td>$pos</td><td>$pilot</td><td>$team</td><td>$time</td></tr>";
    }

    echo "</table>";
}
?>

