<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$canister["ii"] = "rdmx6-jaaaa-aaaaa-aaadq-cai";


$params = array(
	"method_name" 				=> "http_request",
	"request_type" 				=> "request",
	"canister_id" 				=> $canister["ii"],
	"nonce" 				=> "ba",
	"sender" 				=> "aaaa",
	"ingress_expiry" 				=> "1706993153000",
);

//https://icp-api.io/api/v2/canister

$url = 'https://icp-api.io/api/v2/canister/' . $canister["ii"] . '/query';
$return_data = curl_post($url, $params);

print_r($return_data);
?>