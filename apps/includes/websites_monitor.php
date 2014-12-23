<?php

$websites_list = file_get_contents('/var/www/html/apps/config/websites.list');
$websites_list = explode("\n", $websites_list);

$websites_total = count($websites_list);
$websites_online = 0;
$websites_offline = 0;
$websites_array = array();

foreach($websites_list as $website) {
    if(trim($website) == '')
    {
        continue;
    }

    $website_details = explode(':', $website);
    $online = Visit($website_details[0]);

    echo $website_details[0] . "\n";

    if($online) {
        $websites_online++;
        $website_details[] = '<dtitle style="color:red;">Online</dtitle>';
    }
    else {
        $websites_offline++;
        $website_details[] = '<dtitle style="color:limegreen;">Offline</dtitle>';
    }

    $websites_array[] = $website_details;
}

$websites_offline_percent = round(($websites_offline / $websites_total) * 100);
$websites_offline_nonpercent = 100 - $websites_offline_percent;

function Visit($url){
    if(gethostbyname($url) != "127.0.0.1") {
        if ($socket = @ fsockopen($url, 80, $errno, $errstr, 5)) {
            fclose($socket);
            return true;
        } else {
            return false;
        }
    }
    else {
        return false;
    }
}