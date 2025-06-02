echo '<h2 class="f1-title">Classifica finale</h2>';
echo '<div class="f1-table-wrapper">';

// Intestazioni
echo '<div class="f1-row f1-header">';
echo '<div class="f1-col">Pos</div>';
echo '<div class="f1-col">Pilota</div>';
echo '<div class="f1-col">Team</div>';
echo '<div class="f1-col">Tempo</div>';
echo '</div>';

// Dati
foreach ($rows as $row) {
    $cols = $row->getElementsByTagName('td');
    if ($cols->length < 5) continue;

    $pos = trim($cols->item(0)->nodeValue);
    $pilot_spans = $cols->item(2)->getElementsByTagName('span');
    $firstName = $pilot_spans->item(0) ? trim($pilot_spans->item(0)->nodeValue) : '';
    $lastName  = $pilot_spans->item(1) ? trim($pilot_spans->item(1)->nodeValue) : '';
    $pilot = "$firstName $lastName";

    $team = trim($cols->item(3)->nodeValue);
    $time = (date('w') == 0) ? trim($cols->item(5)->nodeValue) : trim($cols->item(4)->nodeValue);

    echo '<div class="f1-row">';
    echo "<div class='f1-col'>$pos</div>";
    echo "<div class='f1-col'>$pilot</div>";
    echo "<div class='f1-col'>$team</div>";
    echo "<div class='f1-col'>$time</div>";
    echo '</div>';
}

echo '</div>'; // f1-table-wrapper
