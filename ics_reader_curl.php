<?php
/*
Template Name: Lettore ICS Server (cURL)
*/
get_header();
?>
<style>
.ics-output {
    max-width: 900px;
    margin: 40px auto;
    font-family: monospace;
    background: #f0f0f0;
    padding: 20px;
    border-radius: 10px;
    white-space: pre-wrap;
}
</style>

<div class="ics-output">
    <h2>Eventi futuri dal file ICS (cURL)</h2>
    <?php
    $ics_url = 'https://calendar.google.com/calendar/ical/3b4870bcfcdd9169a4ca7f81e98a5ec6013be9ffccc46bb18636bd973245c@group.calendar.google.com/public/basic.ics';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ics_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $ics = curl_exec($ch);
    curl_close($ch);

    if (!$ics) {
        echo "❌ Impossibile scaricare il file ICS (via cURL).";
    } else {
        preg_match_all('/BEGIN:VEVENT(.*?)END:VEVENT/s', $ics, $matches);
        $now = time();
        $events = [];

        foreach ($matches[1] as $event_block) {
            preg_match('/SUMMARY:(.*)/', $event_block, $summary);
            preg_match('/DTSTART.*:(\d+)/', $event_block, $dtstart);

            if (!empty($dtstart[1])) {
                $timestamp = strtotime($dtstart[1]);
                if ($timestamp === false && strlen($dtstart[1]) >= 15) {
                    $timestamp = DateTime::createFromFormat('Ymd\THis', $dtstart[1])->getTimestamp();
                }
                if ($timestamp > $now) {
                    $title = isset($summary[1]) ? $summary[1] : '(senza titolo)';
                    $date = date('Y-m-d H:i', $timestamp);
                    $events[] = "📅 $date → $title";
                }
            }
        }

        if (count($events) > 0) {
            echo implode("<br>", $events);
        } else {
            echo "❌ Nessun evento futuro trovato.";
        }
    }
    ?>
</div>

<?php get_footer(); ?>
