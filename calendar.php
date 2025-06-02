<?php
/*
Template Name: Calendario Gare
*/
echo do_shortcode('[calendar id="4401"]')?>
<style>
.calendar-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 40px 20px;
}
.calendar-container table {
    width: 100%;
    border-collapse: collapse;
}
.calendar-container th, .calendar-container td {
    padding: 12px;
    border-bottom: 1px solid #ccc;
    text-align: left;
}
.calendar-container th {
    background: #e10600;
    color: #fff;
}
</style>
<div class="calendar-container">
    <h1>Calendario GP 2025</h1>
    <table>
        <tr><th>Data</th><th>Gran Premio</th><th>Luogo</th></tr>
        <tr><td>26 Maggio</td><td>GP di Monaco</td><td>Monte Carlo</td></tr>
        <tr><td>9 Giugno</td><td>GP del Canada</td><td>Montréal</td></tr>
        <tr><td>23 Giugno</td><td>GP di Spagna</td><td>Barcellona</td></tr>
        <tr><td>30 Giugno</td><td>GP d'Austria</td><td>Spielberg</td></tr>
    </table>
</div>
<?php get_footer(); ?>
