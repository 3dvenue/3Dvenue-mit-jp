<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $id = $_GET['i'];

$venueLogPath = '../que/'.$id.'/venue.log';
$accessLogPath = '../que/'.$id.'/access.log';

$venue = [];
$access = [];

if (file_exists($venueLogPath)) {
    $venue = file($venueLogPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

if (file_exists($accessLogPath)) {
    $access = file($accessLogPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

function parseLog($logLines) {
    $daily = [];
    $hourly = [];

    foreach ($logLines as $line) {
        $parts = explode(' ', $line);
        if (count($parts) < 2) continue;

        $datetime = $parts[0] . ' ' . $parts[1];
        $timestamp = strtotime($datetime);
        if (!$timestamp) continue;

        $date = date('Y-m-d', $timestamp);
        $hour = date('H', $timestamp);

        if (!isset($daily[$date])) $daily[$date] = 0;
        $daily[$date]++;

        if (!isset($hourly[$hour])) $hourly[$hour] = 0;
        $hourly[$hour]++;
    }

    return [
        'daily' => $daily,
        'hourly' => $hourly
    ];
}

$venueParsed  = parseLog($venue);
$accessParsed = parseLog($access);

$venueDailyJson  = json_encode($venueParsed['daily']);
$venueHourlyJson = json_encode($venueParsed['hourly']);

$accessDailyJson  = json_encode($accessParsed['daily']);
$accessHourlyJson = json_encode($accessParsed['hourly']);

}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/analyze.css">
    <link rel="icon" href="../favicon.ico">
    <title>アクセス解析</title>
</head>
<body>
<main>
    <div class="inner">
        <h1>アクセス解析</h1>
        <section>
        <h2>入口解析</h2>

        <div class="kei">合計：<?=count($access)?> 件</div>

        <canvas id="accessDaily"></canvas>
        <canvas id="accessHourly"></canvas>

        <h2>バーチャル会場解析</h2>

        <div class="kei">合計：<?=count($venue)?> 件</div>

        <canvas id="venueDaily"></canvas>
        <canvas id="venueHourly"></canvas>

        </section>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const venueDaily  = <?php echo $venueDailyJson; ?>;
const venueHourly = <?php echo $venueHourlyJson; ?>;

const accessDaily  = <?php echo $accessDailyJson; ?>;
const accessHourly = <?php echo $accessHourlyJson; ?>;

// venue 日別
new Chart(document.getElementById('venueDaily'), {
    type: 'bar',
    data: {
        labels: Object.keys(venueDaily),
        datasets: [{
            label: '会場 日別アクセス',
            data: Object.values(venueDaily),
            backgroundColor: 'rgba(54, 162, 235, 0.6)'
        }]
    }
});

// venue 時間別
new Chart(document.getElementById('venueHourly'), {
    type: 'line',
    data: {
        labels: Object.keys(venueHourly),
        datasets: [{
            label: '会場 時間別アクセス',
            data: Object.values(venueHourly),
            borderColor: 'rgba(255, 159, 64, 0.8)',
            fill: false
        }]
    }
});

// access 日別
new Chart(document.getElementById('accessDaily'), {
    type: 'bar',
    data: {
        labels: Object.keys(accessDaily),
        datasets: [{
            label: '入口 日別アクセス',
            data: Object.values(accessDaily),
            backgroundColor: 'rgba(75, 192, 192, 0.6)'
        }]
    }
});

// access 時間別
new Chart(document.getElementById('accessHourly'), {
    type: 'line',
    data: {
        labels: Object.keys(accessHourly),
        datasets: [{
            label: '入口 時間別アクセス',
            data: Object.values(accessHourly),
            borderColor: 'rgba(153, 102, 255, 0.8)',
            fill: false
        }]
    }
});
</script>

</body>
</html>