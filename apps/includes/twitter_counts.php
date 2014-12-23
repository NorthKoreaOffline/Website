<?php

include_once '/var/www/html/toauth/app_only.php';

// lets run a search.
$bearer_token = get_bearer_token(); // get the bearer token
$tweets_json = search_for_a_term($bearer_token, "#NorthKoreaOffline"); //  search for the work 'test'
$followers = get_followers_count($bearer_token);
invalidate_bearer_token($bearer_token); // invalidate the token


