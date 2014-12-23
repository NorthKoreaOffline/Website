<?php
$hosts_list = file_get_contents('/var/www/html/apps/config/ip_ports.list');
$hosts_list = explode("\n", $hosts_list);

$hosts_total = count($hosts_list);
$hosts_online = 0;
$hosts_offline = 0;

foreach($hosts_list as $host) {
    if(trim($host) == '')
    {
        continue;
    }

    $ip = explode(':', $host);
    $port = $ip[1];
    $ip = $ip[0];

    if(checkIpPort($ip, $port)) {
        $hosts_online++;
    }
    else {
        $hosts_offline++;
    }
}

$hosts_offline_percent = round(($hosts_offline / $hosts_total) * 100);
$hosts_offline_nonpercent = 100 - $hosts_offline_percent;

function checkIpPort($ip, $port)
{
    if ($socket = @ fsockopen($ip, $port, $errno, $errstr, 5)) {
        fclose($socket);
        return true;
    } else {
        return false;
    }
}