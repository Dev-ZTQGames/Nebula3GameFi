<?php
/*
 * this is for liff
 */
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    exit('Invalid JSON data');
}

if (!isset($data['paymentId']) || !isset($data['status'])) {
    exit('Missing required fields');
}

$paymentId = $data['paymentId'];
$status = $data['status'];

//$paymentId = escape_string(trim($_REQUEST['paymentId']));
//$status = escape_string(trim($_REQUEST['status']));

if ( $paymentId )			$proc1 .= "paymentId : " . $paymentId;
if ( $status )			$proc2 .= "status : " . $status;

output_log("Data received successfully", "WEBHOOK");

$que = mysqli_query($connect, "SELECT * FROM GamesOrder WHERE m_payment_id = '".$paymentId."'");
$info = mysqli_fetch_array($que);

if($info['m_index'] != "" && $info["m_status"] != "FINALIZED" && $info["m_status"] != "CANCELED") {
	
	$url = "https://payment.dappportal.io/api/payment-v1/payment/status?id=" . $paymentId;

	$result = curl_return_json($url);

	$data = $result['status'];

	if($result['status'] != $status) exit;

	if($status == "CONFIRMED"){
		
		$data = [
			"id" => $paymentId
		];

		$response = curl_post_liff("https://payment.dappportal.io/api/payment-v1/payment/finalize", $data);
		
		//ICP OnChain-Data N3QE token minting  /includes/config.php
		mintN3QEToken("N3_RECEIVE_PAYMENT_STATUS", $info['m_login_id']);

		if($info['m_item_name'] == "roulette") {
			$que_rouletter = mysqli_query($connect, "SELECT * FROM GamesRoulette WHERE m_login_id = '".$info['m_login_id']."' AND m_game_code = '".$info['m_game_code']."'");
			$rowCount = mysqli_num_rows($que_rouletter);

			if( $rowCount <= 0) {
				$query = "INSERT INTO GamesRoulette (m_login_id, m_game_code, m_remaining_spins) VALUES ('".$info['m_login_id']."', '".$info['m_game_code']."', ".$info['m_item_price'].")";
			} else {
				$query = "UPDATE GamesRoulette SET m_remaining_spins = m_remaining_spins + ".$info['m_item_price']." WHERE m_login_id = '".$info['m_login_id']."' AND m_game_code = '".$info['m_game_code']."'";
			}

			mysqli_query($connect, $query);

			$reason = "PaymentId: ".$info['m_payment_id'];
			mysqli_query($connect, "INSERT INTO GamesRouletteLog (m_login_id, m_game_code, m_type, m_amount, m_reason, m_date) VALUES ('".$info['m_login_id']."', '".$info['m_game_code']."', 'buy', ".$info['m_item_price'].", '".$reason."', '".date("Y-m-d H:i:s")."')");

		}

	}

	$result = mysqli_query($connect, "UPDATE GamesOrder SET m_status = '".$status."' WHERE m_payment_id = '".$paymentId."'");

	if ($result) {
		http_response_code(200);
	}
}


//////////////////////////////////////////////////////////////////////////////////////////////////

function curl_post_liff($url, $fields) {

    $ch = curl_init();

	$header = [
		'Content-Type: application/json'
	];

	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close ($ch);
    return $response;
}