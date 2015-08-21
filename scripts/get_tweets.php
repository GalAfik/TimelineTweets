<?php

// Report all PHP errors
error_reporting(-1);

header('Content-Type: application/json');

require_once('../classes/TwitterProxy.class.php');
require_once('json-pretty-print.php');

$auth_filename = '../config/auth.ini';

if (!file_exists($auth_filename)) {
	echo 'Exception: auth.ini file does not exist.';
} else {
	$auth_config = parse_ini_file($auth_filename);

	// Twitter OAuth Config options
	$oauth_access_token = $auth_config['oauth_access_token'];
	$oauth_access_token_secret = $auth_config['oauth_access_token_secret'];
	$consumer_key = $auth_config['consumer_key'];
	$consumer_secret = $auth_config['consumer_secret'];

	$user_id = $_GET['user_id'];
	$count = intval($_GET['count']);

	$twitter_url = 'statuses/user_timeline.json';
	$twitter_url .= '?user_id=' . $user_id;
	$twitter_url .= '&count=' . $count;

	// Create a Twitter Proxy object from our twitter_proxy.php class
	$twitter_proxy = new TwitterProxy(
		$oauth_access_token,			// 'Access token' on https://apps.twitter.com
		$oauth_access_token_secret,		// 'Access token secret' on https://apps.twitter.com
		$consumer_key,					// 'API key' on https://apps.twitter.com
		$consumer_secret,				// 'API secret' on https://apps.twitter.com
		$user_id,						// User id (http://gettwitterid.com/)
		$count							// The number of tweets to pull out
	);

	// Invoke the get method to retrieve results via a cURL request
	$tweets = $twitter_proxy->get($twitter_url);

	// Write filtered tweets to file.
	writeFile('testFile.json', '../data', $tweets);

	// Return original tweets.
	echo $tweets;
}

function writeFile($filename, $dir, $tweetData) {
	if (!file_exists($dir)) {
		mkdir($dir, 0777, true);
	}

	$filepath = "$dir/$filename";

	$filtered_tweets = array_map(function($tweet) {
		$user = $tweet->{'user'};
		return (object) array(
			'userId' => $user->{'id'},
			'user' => $user->{'name'},
			'location' => $user->{'location'},
			'text' => $tweet->{'text'},
			'date' => $tweet->{'created_at'},
			'retweetCount' => $tweet->{'retweet_count'},
			'favoriteCount' => $tweet->{'favorite_count'}

		);
	}, json_decode($tweetData));

	$fh = fopen($filepath, 'w');
	fwrite($fh, json_format($filtered_tweets));
	fclose($fh);
}

?>
