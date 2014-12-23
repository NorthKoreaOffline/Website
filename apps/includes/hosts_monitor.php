<?php

if(file_exists('port_scan.lock')) {
    exit;
}

file_put_contents('port_scan.lock', '');

$hosts_list = file_get_contents('/var/www/html/apps/config/ip_ports.list');
$hosts_list = explode("\n", $hosts_list);

$hosts_total = count($hosts_list);
$hosts_online = 0;
$hosts_offline = 0;
$hosts = '<div class="row">';
$hosts_counter = 0;

foreach($hosts_list as $host) {
    if(trim($host) == '')
    {
        continue;
    }

    $scan_results = explode('!', $host);

    $ip = explode(':', $scan_results[0]);
    $hostname = $ip[1];
    $ip = $ip[0];

    $widget = '<div class="col-sm-4 col-lg-4"><h4 style="color:white;"><strong>' . $ip . '</strong>&nbsp;&nbsp;&nbsp;(' . $hostname . ')</h4><table class="display"><thead><tr><th>Port</th><th>Protocol</th><th>Vendor</th><th>Status</th></tr></thead><tbody>';

    $ports = explode(',', $scan_results[1]);
    $results = '';
    $x = 0;

    foreach($ports as $port_details) {
        $port_details = explode(':', trim($port_details));

        $port = $port_details[0];
        $proto = $port_details[1];
        $vendor = $port_details[2];

        if (checkIpPort($ip, $port)) {
            $hosts_online++;
            $online = 'red';
            $offline = "ONLINE";
        } else {
            $online = 'green';
            $offline = "OFFLINE";
            $hosts_offline++;
        }

        $oddeven = 'odd';
        if($x == 1) {
            $oddeven = 'even';
            $x = 0;
        }
        else {
            $x++;
        }

        $port_results = '<tr class="' . $oddeven . '"><td>' . $port . '</td><td>' . $proto . '</td><td>' . $vendor . '</td><td style="color:' . $online . '">' . $offline . '</td></tr>';

        $widget = $widget . $port_results;
    }

    $widget = $widget . '</tbody></table></div>';

    $hosts = $hosts . $widget;

    if($hosts_counter == 2) {
        $hosts = $hosts . '</div><div class="row">';
        $hosts_counter = 0;
    }

    $hosts_counter++;
}

$hosts = $hosts . '</div>';

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
date_default_timezone_set("UTC");

file_put_contents('scan_results.log', $hosts_offline_percent . ';' . $hosts_offline_nonpercent . ';' . date("Y-m-d H:i:s", time()));

$hosts_template = file_get_contents('/var/www/html/templates/hosts.html');

$hosts_template = str_replace('%HOSTS%', $hosts, $hosts_template);

file_put_contents('/var/www/html/hosts.html', $hosts_template);

unlink('port_scan.lock');