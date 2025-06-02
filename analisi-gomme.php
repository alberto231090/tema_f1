<style>

</style>
<?php
// Recupera dati da ACF
$anno = get_field('anno');
$circuit = get_field('paese');
$sessione = get_field('sessione');

function get_openf1_json($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        echo "<p>Errore CURL: " . curl_error($ch) . "</p>";
        curl_close($ch);
        return [];
    }
    curl_close($ch);
    return json_decode($output, true);
}

$sessions_url = "https://api.openf1.org/v1/sessions?year=$anno&circuit_short_name=$circuit&session_name=$sessione";
$sessions = get_openf1_json($sessions_url);
if (!$sessions || empty($sessions[0]['session_key'])) {
    
    return;
}
$session_key = $sessions[0]['session_key'];

$drivers_url = "https://api.openf1.org/v1/drivers?session_key=$session_key";
$drivers_data = get_openf1_json($drivers_url);
$pilot_names = [];
foreach ($drivers_data as $drv) {
    $pilot_names[$drv['driver_number']] = $drv['full_name'];
}

$tyre_url = "https://api.openf1.org/v1/stints?session_key=$session_key";
$tyres = get_openf1_json($tyre_url);
$compound_by_lap_driver = [];
foreach ($tyres as $stint) {
    $driver = $stint['driver_number'];
    $compound = strtolower($stint['compound']);
    for ($lap = $stint['lap_start']; $lap <= $stint['lap_end']; $lap++) {
        $compound_by_lap_driver[$driver][$lap] = $compound;
    }
}

// TABELLE TEMPI MIGLIORI
$laps_url = "https://api.openf1.org/v1/laps?session_key=$session_key";
$laps = get_openf1_json($laps_url);
$best_laps = [];
foreach ($laps as $lap) {
    $driver = $lap['driver_number'];
    $lap_number = $lap['lap_number'];
    $duration = $lap['lap_duration'];
    if (!$duration || !isset($compound_by_lap_driver[$driver][$lap_number])) continue;
    $compound = $compound_by_lap_driver[$driver][$lap_number];
    $name = $pilot_names[$driver] ?? $driver;
    if (!isset($best_laps[$compound][$name]) || $duration < $best_laps[$compound][$name]) {
        $best_laps[$compound][$name] = $duration;
    }
}

$compound_colors_map = [
    "soft" => "#e10600",
    "medium" => "#ffd12e",
    "hard" => "#f0f0f0",
    "intermediate" => "#3bb273",
    "wet" => "#1e90ff"
];

$compound_data = [];
$pilot_compounds = [];
foreach ($tyres as $stint) {
    $laps = $stint['lap_end'] - $stint['lap_start'] + 1;
    $compound = strtolower($stint['compound']);
    $driver = $stint['driver_number'];
    $name = $pilot_names[$driver] ?? $driver;
    $compound_data[$compound] = ($compound_data[$compound] ?? 0) + $laps;
    if (!isset($pilot_compounds[$name])) $pilot_compounds[$name] = [];
    $pilot_compounds[$name][$compound] = ($pilot_compounds[$name][$compound] ?? 0) + $laps;
}
$drivers = array_keys($pilot_compounds);
$all_compounds = array_keys($compound_data);
$dataset = [];
foreach ($all_compounds as $compound) {
    $row = [
        'label' => ucfirst($compound),
        'backgroundColor' => $compound_colors_map[$compound] ?? "#aaa",
        'data' => []
    ];
    foreach ($drivers as $drv) {
        $row['data'][] = $pilot_compounds[$drv][$compound] ?? 0;
    }
    $dataset[] = $row;
}
$background_colors = [];
foreach ($compound_data as $compound => $_) {
    $compound_lc = strtolower($compound);
    $background_colors[] = $compound_colors_map[$compound_lc] ?? '#aaa';
}

// OUTPUT HTML

echo "<h3>Classifica Miglior Tempo per Pilota per Compound</h3>";
echo "<div style='display:flex; flex-wrap:wrap; gap:20px; margin-bottom: 3rem;'>";
foreach ($best_laps as $compound => $driver_times) {
    echo "<div style='flex:1; min-width:250px;'>";
    echo "<h4>" . ucfirst($compound) . "</h4><table border='1' cellpadding='6'><tr><th>Pilota</th><th>Tempo</th></tr>";
    asort($driver_times);
    foreach ($driver_times as $name => $time) {
        $minutes = floor($time / 60);
        $seconds = $time - ($minutes * 60);
        $formatted_time = sprintf("%02d:%06.3f", $minutes, $seconds);
        echo "<tr><td>$name</td><td>$formatted_time</td></tr>";
    }
    echo "</table></div>";
}
echo "</div>";

// GRAFICI
echo '<canvas id="chartCompound" width="400" height="200"></canvas>';
echo '<canvas id="chartCompoundByDriver" width="400" height="300" style="margin-top: 2rem;"></canvas>';
echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
echo "

<script>

window.tyreChartData = {

  labels: " . json_encode(array_map('ucfirst', array_keys($compound_data))) . ",
  datasets: [{
    label: 'Giri totali per compound',
    data: " . json_encode(array_values($compound_data)) . ",
    backgroundColor: " . json_encode($background_colors) . "
  }]
};

window.tyreByDriverData = {
  labels: " . json_encode($drivers) . ",
  datasets: " . json_encode($dataset) . "
};

document.addEventListener('DOMContentLoaded', function () {
  new Chart(document.getElementById('chartCompound'), {
    type: 'pie',
    data: {
      labels: window.tyreChartData.labels,
      datasets: window.tyreChartData.datasets
    },
    options: {
      responsive: true,
      plugins: {
        title: { display: true, text: 'Totale Giri per Compound' }
      }
    }
  });

  new Chart(document.getElementById('chartCompoundByDriver'), {
    type: 'bar',
    data: {
      labels: window.tyreByDriverData.labels,
      datasets: window.tyreByDriverData.datasets
    },
    options: {
      responsive: true,
      plugins: {
        title: { display: true, text: 'Giri per Compound per Pilota' }
      },
      scales: {
        x: { stacked: true },
        y: { stacked: true }
      }
    }
  });
});
</script>";
?>