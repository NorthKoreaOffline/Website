<?php

include_once '/var/www/html/apps/includes/db.php';

$queryDownloads = 'SELECT downloads FROM counts WHERE id=1';
$downloadsResult = mysqli_query($mysql, $queryDownloads);
$downloadsArray = mysqli_fetch_array($downloadsResult);

$downloads_count = $downloadsArray['downloads'];

$queryonline = 'SELECT count(ip) FROM protesters WHERE tstamp=NOW() - INTERVAL 1 HOUR';
$onlineResult = mysqli_query($mysql, $queryonline);
$onlineArray = mysqli_fetch_array($onlineResult);

$online_count = $onlineArray[0];

$queryonline24 = 'SELECT count(ip) FROM protesters WHERE tstamp=NOW() - INTERVAL 1 DAY';
$online24Result = mysqli_query($mysql, $queryonline24);
$online24Array = mysqli_fetch_array($online24Result);

$online24_count = $online24Array[0];

$querymost = 'SELECT count(ip) FROM protesters';
$mostResult = mysqli_query($mysql, $querymost);
$mostArray = mysqli_fetch_array($mostResult);

$most_count = $mostArray[0];
