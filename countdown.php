<?php
/*
Template Name: Countdown F1 ICS (evento più vicino)
*/

?>

<style>
.countdown-container {
    text-align: center;
    padding: 40px 20px;

}
.countdown-box {
    background-color: #fff;
    color: #c00;
    display: inline-block;
    padding: 30px;
    border-radius: 12px;
    font-size: 1.6rem;
}
</style>

<div class="countdown-container">
    <h2>Countdown al prossimo GP F1</h2>
    <div id="countdown" class="countdown-box">⏳ Caricamento...</div>
</div>

<script>
fetch('<?php echo get_template_directory_uri(); ?>/ics_proxy.php')
    .then(response => response.text())
    .then(data => {
        const lines = data.split(/\r?\n/);
        const events = [];
        let event = {};

        for (let line of lines) {
            if (line.startsWith('BEGIN:VEVENT')) {
                event = {};
            } else if (line.startsWith('SUMMARY:')) {
                event.title = line.replace('SUMMARY:', '').trim();
            } else if (line.startsWith('DTSTART')) {
                let dt = line.split(':')[1].trim();
                let year = parseInt(dt.substring(0,4));
                let month = parseInt(dt.substring(4,6)) - 1;
                let day = parseInt(dt.substring(6,8));
                let hour = dt.length > 9 ? parseInt(dt.substring(9,11)) : 12;
                let minute = dt.length > 11 ? parseInt(dt.substring(11,13)) : 0;
                event.date = new Date(Date.UTC(year, month, day, hour, minute));
            } else if (line.startsWith('END:VEVENT')) {
                if (event.date && event.date > new Date()) {
                    events.push(event);
                }
            }
        }

        if (events.length > 0) {
            events.sort((a, b) => a.date - b.date);
            const next = events[0];
            const title = next.title;
            const targetDate = next.date.getTime();

            const countdownEl = document.getElementById('countdown');

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = targetDate - now;

                if (distance <= 0) {
                    countdownEl.innerHTML = "🏁 È il giorno del GP!";
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownEl.innerHTML = "🏁 <strong>" + title + "</strong><br>⏳ Countdown: " + days + "g " + hours + "h " + minutes + "m " + seconds + "s";
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        } else {
            document.getElementById('countdown').innerHTML = "⚠️ Nessun evento futuro trovato.";
        }
    })
    .catch(err => {
        document.getElementById('countdown').innerHTML = "❌ Errore nel caricamento del calendario.";
    });
</script>


