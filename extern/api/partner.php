<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$mode = escape_string(trim($_REQUEST['mode']));
$partner = escape_string(trim($_REQUEST['partner']));

$address = escape_string(trim($_REQUEST['address']));

if ( $mode )				$proc1 = $mode;
if ( $partner )			$proc2 .= "partner : " . $partner;

if ( $address )			$proc2 .= ", address : " . $address;



if ( $mode == "" || $partner == "" ) {
	$array = [
		"status" => "error",
		"message" => "Default required parameter not passed."
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "PARTNER");
	exit;
}

$partnerList = ["PvZ"];

if ( !in_array($partner, $partnerList) ) {
	$array = [
		"status" => "error",
		"message" => "Not allowed partner."
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "PARTNER");
	exit;
}


if($mode == "check_invited") {
	if ( $address == "" )	{
		$array = [
			"status" => "error", 
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "PARTNER");
		exit;
	}

	$que = mysqli_query($connect, "SELECT * FROM AccountsChain WHERE m_account = '".$address."'");
	$info = mysqli_fetch_array($que);

	if($info['m_index'] == "") {
		$array = [
			"status" => "error", 
			"message" => "The user does not exist."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "PARTNER");
		exit;
	}

	$que_referral = mysqli_query($connect, "SELECT * FROM AccountsReferral WHERE m_login_id = '".$info['m_login_id']."'");
	$info_referral = mysqli_fetch_array($que_referral);

	if($partner == $info_referral['m_referral']) {
		$array = [
			"status" => "success", 
			"message" => "The user has been successfully invited to join."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "PARTNER");
		exit;
	}

	$array = [
		"status" => "error", 
		"message" => "The user has already registered."
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "PARTNER");
	exit;
}