<?php

// Host: 31.220.29.131 ()	Ports: 25/open/tcp//smtp //Postfix smtpd/, 80/open/tcp//http //Apache httpd 2.2.22 ((Debian))/, 443/open/tcp//https ///

$port_scan_results = file_get_contents('northkorea2.nmap.gnmap');
$port_scan_results = explode("\n", $port_scan_results);
unlink('/var/www/html/ip_ports.list');

foreach($port_scan_results as $port_scan) {

    if(strpos($port_scan,'#') !== 0 && trim($port_scan) != '' && strpos($port_scan, 'Ports') > 0) {
        $port_scan = str_replace('Ports:', '!', $port_scan);
        $port_scan = explode('!', $port_scan);

        $host_array = trim(str_replace('Host:', '', $port_scan[0]));
        $host_array = explode(' ', $host_array);

        $ip = $host_array[0];
        $hostname = str_replace('(', '', str_replace(')', '', $host_array[1]));

        $ports_array = trim($port_scan[1]);
        $ports_array = explode(',', $ports_array);

        $ports = '';
        foreach ($ports_array as $port_details) {
            $port_details = explode('/', $port_details);
            $port = $port_details[0];
            $port_proto = $port_details[4];
            $port_vendor = $port_details[6];

            $ports = $ports . ',' . $port . ':' . trim($port_proto) . ':' . $port_vendor;
        }

        file_put_contents('/var/www/html/ip_ports.list', $ip . ':' . $hostname . '!' . $ports . "\n", FILE_APPEND);
    }
}
