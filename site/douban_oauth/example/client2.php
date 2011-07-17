<?php
require_once("common.inc.php");

$key = "0f6a9dcfd38347412e3fd96f7a89d5c3";
$secret = "5f3fbb0b8ecd35c2";
$token = @$_REQUEST['token'];
$token_secret = @$_REQUEST['token_secret'];
$endpoint = @$_REQUEST['endpoint'];
$action = @$_REQUEST['action'];
$dump_request = @$_REQUEST['dump_request'];
$user_sig_method = @$_REQUEST['sig_method'];
$sig_method = $hmac_method;
if ($user_sig_method) {
  $sig_method = $sig_methods[$user_sig_method];
}

$test_consumer = new OAuthConsumer($key, $secret, NULL);

$test_token = NULL;
if ($token) {
  $test_token = new OAuthConsumer($token, $token_secret);
}


if ($action == "request_token") {
  $parsed = parse_url($endpoint);
  $params = array();
  parse_str($parsed['query'], $params);

  $req_req = OAuthRequest::from_consumer_and_token($test_consumer, NULL, "GET", $endpoint, $params);
  $req_req->sign_request($sig_method, $test_consumer, NULL);
  if ($dump_request) {
    Header('Content-type: text/plain');
    print "request url: " . $req_req->to_url(). "\n";
    print_r($req_req);
    exit;
  }
  Header("Location: $req_req");
} 
else if ($action == "authorize") {
  $callback_url = "$base_url/client.php?key=$key&secret=$secret&token=$token&token_secret=$token_secret&endpoint=" . urlencode($endpoint);
  $auth_url = $endpoint . "?oauth_token=$token&oauth_callback=".urlencode($callback_url);
  if ($dump_request) {
    Header('Content-type: text/plain');
    print("auth_url: " . $auth_url);
    exit;
  }
  Header("Location: $auth_url");
}
else if ($action == "access_token") {
  $parsed = parse_url($endpoint);
  $params = array();
  parse_str($parsed['query'], $params);

  $acc_req = OAuthRequest::from_consumer_and_token($test_consumer, $test_token, "GET", $endpoint, $params);
  $acc_req->sign_request($sig_method, $test_consumer, $test_token);
  if ($dump_request) {
    Header('Content-type: text/plain');
    print "request url: " . $acc_req->to_url() . "\n";
    print_r($acc_req);
    exit;
  }
  Header("Location: $acc_req");
}

?>