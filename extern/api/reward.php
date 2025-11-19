<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use kornrunner\Ethereum\Transaction;

include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

include $_SERVER['DOCUMENT_ROOT'] . "/includes/ethereum.php";

session_start();

$mode = escape_string(trim($_REQUEST['mode']));
$game_code = escape_string(trim($_REQUEST['game_code']));

$login_id = escape_string(trim($_REQUEST['login_id']));

$type = escape_string(trim($_REQUEST['type']));
$transfer_to = escape_string(trim($_REQUEST['transfer_to']));
$transfer_amount = escape_string(trim($_REQUEST['transfer_amount']));
$key = escape_string(trim(urldecode($_REQUEST['key'])));

if ( $mode )				$proc1 = $mode;
if ( $game_code )			$proc2 .= ", game_code : " . $game_code;

if ( $login_id )			$proc2 .= ", login_id : " . $login_id;

if ( $type )			$proc2 .= ", type : " . $type;
if ( $transfer_to )			$proc2 .= ", transfer_to : " . $transfer_to;
if ( $transfer_amount )			$proc2 .= ", transfer_amount : " . $transfer_amount;
if ( $key )			$proc2 .= ", key : " . $key;


if ( $mode == "" )	{
	$array = [
		"status" => "error",
		"message" => "Default required parameter not passed."
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}


if ($mode == "getSTAPBalance") {
	if ( $login_id == "" )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$que = mysqli_query($connect, "SELECT * FROM AccountsChain WHERE m_symbol = 'KAIA' AND m_login_id = '" . $login_id . "'");
	$info = mysqli_fetch_array($que);
	
	if ($info["m_index"] != "") {
		$array = [
			"status" => "success", 
			"message" => "Request OK, Success.", 
			"data" => [ "balance" => $info['m_token_balance'] ]
		];
	} else {
		$array = [
			"status" => "error", 
			"message" => "Data not found. Please verify if the login_id is correct.", 
		];
	}

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
}

if ( $mode == "sendMissionReward") {

	if ( $type == "" || $login_id == "" )	{
		$array = [
			"status" => "error", 
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}




	$query_game = "SELECT * FROM Games WHERE m_game_code = '" . $game_code . "'";
	$que_game = mysqli_query($connect, $query_game);
	$row_game = mysqli_fetch_array($que_game);
	$info_game = $row_game;

	if ( $num_game < 0 )	{		
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$key_value = $info_game['m_key_value'];
	$partner = $info_game["m_partner"];

	$tokenString = AES128Decrypt($key_value, $key);

	$tokenString = explode("|", $tokenString);
	foreach ( $tokenString as $key => $value )	{
		$first =  strtok($value,"=");			
		$second = substr($value, strlen($first) + 1, strlen($value) -1 );
		$serial_array[$first] = $second;
	}

	if ( $serial_array["mode"] != $mode )	{			
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	if ( $serial_array["id"] != $login_id )	{				 
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}
	
	if ( $serial_array["partner"] != $partner )	{				 
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	if ( strlen($serial_array["date"]) != 10 || !is_numeric($serial_array["date"]) )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$gap = abs(time() - $serial_array["date"]);
/*
	if ($gap > 10) {
		$array = [
			"status" => "error", 
			"message" => "The request was invalid or cannot be served."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}
*/
	if ( $serial_array["gamecode"] != $game_code )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$check_data = "type:".$type;

	if ( $serial_array["data"] != $check_data )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}




	$que_chain = mysqli_query($connect, "SELECT * FROM AccountsChain WHERE m_login_id = '".$login_id."'");
	$info_chain = mysqli_fetch_array($que_chain);

	if($info_chain['m_index'] == "") {
		$array = [
			"status" => "error", 
			"message" => "can't find the wallet address."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$que_data = mysqli_query($connect, "SELECT * FROM AccountsRewardData WHERE m_login_id = '".$login_id."' ORDER BY m_index DESC LIMIT 1");
	$info_data = mysqli_fetch_array($que_data);

	if ($info_data['m_index'] == "") {
		$array = [
			"status" => "error", 
			"message" => "The data has not been set yet."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$json = json_decode($info_data['m_data']);
	$result = fixNonStandardJson($json);
	$data = $result['data'];

	switch($type) {
		case "1":
			if ($data['mission1'] == "true") {
				$array = [
					"status" => "error", 
					"message" => "The reward has already been claimed."
				];

				$response = json_encode($array);
				echo $response;

				output_log($response, "API");
				exit;
			}
			$transfer_from = $KAIA_mission1_address_key;
			$transfer_amount = 1;
			$mode = "sendRewardMission1";
			break;
		case "2":
			if ($data['mission2'] == "true") {
				$array = [
					"status" => "error", 
					"message" => "The reward has already been claimed."
				];

				$response = json_encode($array);
				echo $response;

				output_log($response, "API");
				exit;
			}
			exit;
			$transfer_from = $KAIA_mission2_address_key;
			$transfer_amount = 5;
			$mode = "sendRewardMission2";
			break;
		case "3":
			if ($data['mission3'] == "true") {
				$array = [
					"status" => "error", 
					"message" => "The reward has already been claimed."
				];

				$response = json_encode($array);
				echo $response;

				output_log($response, "API");
				exit;
			}
			$transfer_from = $KAIA_mission3_address_key;
			$transfer_amount = 5;
			$mode = "sendRewardMission3";
			break;
	}
	
	$eth = new Ethereum($RPC_Endpoint["kaia"][$node_stage], 443);

	$AH_KAIA_balance = eth_getBalance($transfer_from["public"]);
	if ( $AH_KAIA_balance < $transfer_amount + 0.01 ) {
		$array = [
			"status" => "error", 
			"message" => "Insufficient balance. Please contact the administrator."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}
	
	$transfer_to = $info_chain['m_account'];


//	$result = Kaia_transfer($transfer_from, $transfer_to, $transfer_amount);
	$result = mysqli_query($connect, "INSERT INTO AccountsChainHistory (m_login_id, m_token, m_method, m_amount, m_date) VALUES ('".$login_id."', 'KAIA', '".$mode."', ".$transfer_amount.", '".date("Y-m-d H:i:s")."')");

	if ( !$result ) {  /*$result[0] == "error"*/
		$array = [
			"status" => "error", 
			"message" => "An unexpected error occurred. Please try again later."
		];
	} else {
		$array = [
			"status" => "success", 
			"message" => "Request OK, Success.", 
			"data" => [
			//	"Transaction" => $result,
				"balances" => $AH_KAIA_balance
			]
		];
	}

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}



if ( $mode == "sendRouletteReward") {

	if ( $login_id == "" || $key == "" )	{
		$array = [
			"status" => "error", 
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}




	$query_game = "SELECT * FROM Games WHERE m_game_code = '" . $game_code . "'";
	$que_game = mysqli_query($connect, $query_game);
	$row_game = mysqli_fetch_array($que_game);
	$info_game = $row_game;

	if ( $num_game < 0 )	{		
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$key_value = $info_game['m_key_value'];
	$partner = $info_game["m_partner"];

	$tokenString = AES128Decrypt($key_value, $key);

	$tokenString = explode("|", $tokenString);
	foreach ( $tokenString as $key => $value )	{
		$first =  strtok($value,"=");			
		$second = substr($value, strlen($first) + 1, strlen($value) -1 );
		$serial_array[$first] = $second;
	}

	if ( $serial_array["mode"] != $mode )	{			
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	if ( $serial_array["id"] != $login_id )	{				 
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}
	
	if ( $serial_array["partner"] != $partner )	{				 
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	if ( strlen($serial_array["date"]) != 10 || !is_numeric($serial_array["date"]) )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$gap = abs(time() - $serial_array["date"]);

	if ($gap > 10) {
		$array = [
			"status" => "error", 
			"message" => "The request was invalid or cannot be served."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	if ( $serial_array["gamecode"] != $game_code )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$check_data = "transfer_amount:".$transfer_amount;

	if ( $serial_array["data"] != $check_data )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}




	$que_chain = mysqli_query($connect, "SELECT * FROM AccountsChain WHERE m_login_id = '".$login_id."'");
	$info_chain = mysqli_fetch_array($que_chain);

	if($info_chain['m_index'] == "") {
		$array = [
			"status" => "error", 
			"message" => "can't find the wallet address."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}
	
	$eth = new Ethereum($RPC_Endpoint["kaia"][$node_stage], 443);

	$AH_KAIA_balance = eth_getBalance($KAIA_roulette_address_key["public"]);
	if ( $AH_KAIA_balance < $transfer_amount + 0.01 ) {
		$array = [
			"status" => "error", 
			"message" => "Insufficient balance. Please contact the administrator."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}
	
	$transfer_to = $info_chain['m_account'];

//	$result = Kaia_transfer($KAIA_roulette_address_key, $transfer_to, $transfer_amount);
	$result = mysqli_query($connect, "INSERT INTO AccountsChainHistory (m_login_id, m_token, m_method, m_amount, m_date) VALUES ('".$login_id."', 'KAIA', '".$mode."', ".$transfer_amount.", '".date("Y-m-d H:i:s")."')");
	
	if ( !$result ) { /*$result[0] == "error"*/
		$array = [
			"status" => "error", 
			"message" => "An unexpected error occurred. Please try again later."
		];
	} else {
		$array = [
			"status" => "success", 
			"message" => "Request OK, Success.", 
			"data" => [
			//	"Transaction" => $result,
				"balances" => $AH_KAIA_balance
			]
		];
	}

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}

if ($mode == "getRewardBalance") {
	
	if ( $type == "" )	{
		$array = [
			"status" => "error", 
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}


	switch($type) {
		case "mission1":
			$account = $KAIA_mission1_address_key;
			break;
		case "mission2":
			$account = $KAIA_mission2_address_key;
			break;
		case "mission3":
			$account = $KAIA_mission3_address_key;
			break;
		case "roulette":
			$account = $KAIA_roulette_address_key;
			break;
	}

	$eth = new Ethereum($RPC_Endpoint["kaia"][$node_stage], 443);

	$AH_KAIA_balance = eth_getBalance($account["public"]);

	$array = [
		"status" => "success", 
		"message" => "Request OK, Success.", 
		"data" => [
			"address" => $account["public"],
			"balances" => $AH_KAIA_balance
		]
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}


////////////////////////////////////////////////////////////////////////////////////////////////

function bcdechex($dec) {
    $hex = '';
    do {    
        $last = bcmod($dec, 16);
        $hex = dechex($last).$hex;
        $dec = bcdiv(bcsub($dec, $last), 16);
    } while($dec>0);
    return $hex;
}

function eth_getBalance($address) {
	global $eth, $toWei;

	return ( $eth->eth_getBalance($address, "latest", true) ) / $toWei;
}


function Kaia_transfer($from_address, $to_address, $amount) { 
	global $eth, $toWei, $node_stage;
	
	$nonce = $eth->eth_getTransactionCount($from_address["public"]);
	$nonce = substr($nonce, 2);

	$tokenAmount = $amount;
	$tokenAmountInWei = bcdechex(bcmul($tokenAmount, bcpow("10", "18")));
//	$data = "a9059cbb" . str_pad(substr($to_address, 2), 64, '0', STR_PAD_LEFT) . str_pad($tokenAmountInWei, 64, '0', STR_PAD_LEFT);
	$data = "";

	if ( $node_stage == "test" ) { 
		$chainId  = 1001;
	//	$gasPrice = '30D40';	// 200000, Wei -> source gas fee로 먼저 trying...
	//	$gasLimit = '493E0';	// 300000, Wei MAX -> source gas limit로 먼저 trying...		// test?
		$gasPrice = dechex('25000000000');	// 300000 493E0
		$gasLimit = dechex('400000');  // 400000 61A80
	}
	if ( $node_stage == "live" ) { 
		$chainId  = 8217;
	//	$gasPrice = '37E11D600';	// 15000000000, Wei? -> source gas fee로 먼저 trying...
	//	$gasLimit = '493E0';	// 300000, GWei? MAX -> source gas limit로 먼저 trying...		// test?
	//	$sendUrl = "https://api.etherscan.io/api?module=gastracker&action=gasoracle&apikey=49RC9FF8J4T9UT8P3TJIMI32PIXDJBJ79K";
	//	$return_data = curl_return_json($sendUrl);
	//	$gasPrice = dechex($return_data["result"]["SafeGasPrice"] . "000000000");
	//	$gasLimit = dechex($return_data["result"]["SafeGasPrice"] . "0000");
		$gasPrice = dechex('25000000000');	// 300000 493E0
		$gasLimit = dechex('400000');  // 400000 61A80
	}

//	$to = substr($Contract["token"]["pmw"][$eth_node_stage], 2);
	$to = $to_address;
//	$value = '0';
	$value = $tokenAmountInWei;

	$transaction = new Transaction($nonce, $gasPrice, $gasLimit, $to, $value, $data);
//	var_dump($transaction);

	$rawTx = $transaction->getRaw($from_address["private"], $chainId);
	$msg = new Ethereum_Message(null, $from_address["public"], null, null, null, null, "0x".$rawTx);
	$gas_estimate = $eth->eth_estimateGas($msg);	
	$gasPrice_real = substr($gas_estimate, 2);

//	$gasPrice_real = ceil(hexdec($gasPrice_real) * 1.2);
	$gasPrice_real = hexdec($gasPrice_real);
	if ( $gasPrice_real > hexdec($gasLimit) ) {
		return array('error','BusyNetwork');
	}
	if ( $gasPrice_real < hexdec($gasPrice) ) {
		$gasPrice_real = hexdec($gasPrice);
	}
//	echo $gasPrice_real;
	$gasPrice_real = dechex($gasPrice_real);

	$transaction_real = new Transaction($nonce, $gasPrice_real, $gasLimit, $to, $value, $data);
//	var_dump($transaction_real);
	$rawTx_real = $transaction_real->getRaw($from_address["private"], $chainId);
	
	$receipt = $eth->eth_sendRawTransaction("0x".$rawTx_real);
	
	return $receipt;

}

// Function to fix non-standard JSON string format
function fixNonStandardJson($nonStandardJson) {
    // 1. Use regular expression to wrap all keys with double quotes
    $fixedJson = preg_replace('/(\w+):/', '"$1":', $nonStandardJson);

    // 2. Handle boolean values (false and true) and ensure proper number formatting
    $fixedJson = preg_replace('/"(false|true)"/', '$1', $fixedJson);

    // 3. Handle number-like string values (ensure numeric values stay as numbers)
    $fixedJson = preg_replace_callback('/"([^"]+)":([^,}]+)/', function($matches) {
        // If the value is numeric
        if (is_numeric($matches[2])) {
            return '"' . $matches[1] . '":' . $matches[2];
        }
        // If the value is not numeric, return it as a string with quotes
        return '"' . $matches[1] . '":"' . $matches[2] . '"';
    }, $fixedJson);

    // 4. Decode the fixed JSON string into a PHP array
    $data = json_decode($fixedJson, true);

    // Return both the fixed JSON string and the decoded data
    return [
        'fixedJson' => $fixedJson,
        'data' => $data
    ];
}