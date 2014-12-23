<?php

include_once '/var/www/html/apps/includes/websites_monitor.php';
include_once '/var/www/html/apps/includes/hosts_monitor.php';
include_once '/var/www/html/apps/includes/twitter_counts.php';
include_once '/var/www/html/apps/includes/protester_counts.php';

$index = file_get_contents('/var/www/html/templates/index.html');

$index = str_replace('%TAGLINE%', '#TangoDown #MerryChristmas', $index);
$index = str_replace('%WEBSITES_OFFLINE%', $websites_offline_percent, $index);
$index = str_replace('%WEBSITES_OFFLINE_NON%', $websites_offline_nonpercent, $index);
$index = str_replace('%HOSTS_OFFLINE%', $hosts_offline_percent, $index);
$index = str_replace('%HOSTS_OFFLINE_NON%', $hosts_offline_nonpercent, $index);

$tweets_count = json_decode($tweets_json);
$tweets_count = $tweets_count->search_metadata->count;

$index = str_replace('%HASHTAG_COUNT%', $tweets_count, $index);

$followers_count = json_decode($followers);
$followers_count = $followers_count->followers_count;

$index = str_replace('%FOLLOWERS%',  $followers_count, $index);

$index = str_replace('%ONLINE%', $online_count, $index);
$index = str_replace('%24HOURS%', $online24_count, $index);
$index = str_replace('%MOST%', $most_count, $index);
$index = str_replace('%DOWNLOADS%', $downloads_count, $index);

file_put_contents('/var/www/html/index.html', $index);

$website_contents = '<div class="row">';
$columnCounter = 0;
foreach($websites_array as $website) {

    if($columnCounter == 4) {
        $website_contents = $website_contents . '</div><div class="row">';
        $columnCounter = 0;
    }

    $website_contents = $website_contents . '
        <div class="col-sm-3 col-lg-3">
            <div class="trip-unit">
                ' . $website[3] . '
                <div class="info-user">
                    <img src="/webshots/' . $website[2] . '" />
                </div>
                <div class="text" style="text-align: center;">
                    <p><a href="http://' . $website[0] . '/">' . $website[1] . '</a></p>
                </div>
            </div>
        </div>';

    $columnCounter++;
}

$website_contents  = $website_contents . '</div>';

$websites = file_get_contents('/var/www/html/templates/websites.html');

$websites = str_replace('%WEBSITES%', $website_contents, $websites);

file_put_contents('/var/www/html/websites.html', $websites);