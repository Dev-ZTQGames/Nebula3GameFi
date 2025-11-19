<?php
/*
 * source: https://developers.line.biz/en/reference/line-login/#get-user-profile
 */

//header("Content-Type: application/json");

include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use kornrunner\Ethereum\Transaction;

include $_SERVER['DOCUMENT_ROOT'] . "/includes/ethereum.php";

session_start();
//print_r($_REQUEST);

if ($configuration['mode'] != "service / release") {
	$clientId = '2006725882'; // Line Console Provider
	$testMode = true;
} else {
	$clientId = '2006817208';
	$testMode = false;
}

$mode = escape_string(trim($_REQUEST['mode']));
$game_code = escape_string(trim($_REQUEST['game_code']));

$IdToken			 = escape_string(trim($_REQUEST['IdToken']));
$userId				 = escape_string(trim($_REQUEST['userId']));
$address			 = escape_string(trim($_REQUEST['address']));

$login_id			= escape_string(trim($_REQUEST['login_id']));
$hash				= escape_string(trim($_REQUEST['hash']));
$time				= escape_string(trim($_REQUEST['time']));

$partner			= escape_string(trim($_REQUEST['partner']));


$data				= trim($_REQUEST['data']);
$date_from			= escape_string(trim($_REQUEST['from']));
$date_to			= escape_string(trim($_REQUEST['to']));

$amount				= escape_string(trim($_REQUEST['amount']));

$currencyCode		= escape_string(trim($_REQUEST['currencyCode']));
$price				= escape_string(trim($_REQUEST['price']));
$itemName			= escape_string(trim($_REQUEST['itemName']));
$itemIdentifier		= escape_string(trim($_REQUEST['itemIdentifier']));
$imageUrl			= escape_string(trim($_REQUEST['imageUrl']));

$paymentId			= escape_string(trim($_REQUEST['paymentId']));

$type			= escape_string(trim($_REQUEST['type']));
$score			= escape_string(trim($_REQUEST['score']));
$quantity			= escape_string(trim($_REQUEST['quantity']));

$first			= trim($_REQUEST['first']);
$second			= trim($_REQUEST['second']);

$key			= urldecode(trim($_REQUEST['key']));

if ( $mode )				$proc1 = $mode;
if ( $game_code )			$proc2 .= "game_code : " . $game_code;

if ( $IdToken )			$proc2 .= ", IdToken : " . $IdToken;
if ( $userId )			$proc2 .= ", userId : " . $userId;
if ( $address )			$proc2 .= ", address : " . $address;

if ( $login_id )			$proc2 .= ", login_id : " . $login_id;
if ( $hash )			$proc2 .= ", hash : " . $hash;
if ( $time )			$proc2 .= ", time : " . $time;

if ( $partner )			$proc2 .= ", partner : " . $partner;

if ( $data )			$proc2 .= ", data : " . $data;
if ( $date_from )			$date_from .= ", time : " . $date_from;
if ( $date_to )			$proc2 .= ", date_to : " . $date_to;

if ( $amount )			$proc2 .= ", amount : " . $amount;

if ( $currencyCode )			$proc2 .= ", currencyCode : " . $currencyCode;
if ( $price )			$proc2 .= ", price : " . $price;
if ( $itemName )			$proc2 .= ", itemName : " . $itemName;
if ( $itemIdentifier )			$proc2 .= ", itemIdentifier : " . $itemIdentifier;
if ( $imageUrl )			$proc2 .= ", imageUrl : " . $imageUrl;

if ( $type )			$proc2 .= ", type : " . $type;
if ( $score )			$proc2 .= ", score : " . $score;
if ( $quantity )			$proc2 .= ", quantity : " . $quantity;

if ( $first )			$proc2 .= ", first : " . $first;
if ( $second )			$proc2 .= ", second : " . $second;

if ( $key )			$proc2 .= ", key : " . $key;


if ( $mode == "" || $game_code == "" )	{
	$array = [
		"status" => "error",
		"message" => "Default required parameter not passed."
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}

$isLogin = ($mode == "join_liff" || $mode == "join_web" || $mode == "login");
/*
if ($_SESSION['sess_login_id'] == "" && !$isLogin) {
	$array = [
		"status" => "error",
		"message" => "Default required parameter not passed."
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}
*/

$gameList = ["tfsf"];

if ( !in_array($game_code, $gameList) ) {
		$array = [
		"status" => "error",
		"message" => "Not allowed game code."
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}

$partnerList = ["PvZ"];

if ( $partner != "" && !in_array($partner, $partnerList) ) {
	$array = [
		"status" => "error",
		"message" => "Not allowed partner."
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "PARTNER");
	exit;
}


/*
 * Join Liff API
 */


if ( $mode == 'join_liff' ) {

	if ( $IdToken == "" || $userId == "" )	{
		$array = [
			"status" => "error", 
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}
	
	$data = [
		'id_token' => $IdToken,
	    'client_id' => $clientId
	];
	$resultJson = curl_post('https://api.line.me/oauth2/v2.1/verify',$data);
	/*
	 *{
	 *  "iss": "https://access.line.me",
	 *  "sub": "U3f2d20829b94b4e964eab64fef6c2637",		user Id
	 *  "aud": "2006725882",							channel Id
	 *  "exp": 1737224567,
	 *  "iat": 1737220967,
	 *  "amr": [
	 *    "linesso"
	 *  ],
	 *  "name": "Shawn",
	 *  "picture": "https://profile.line-scdn.net/0huvvf6DsuKllLHzu8x-tVDndaJDQ8MSwRMy03bGwdfDoxKzlbcitjOW4bc2xvJ21fcXBjN2cddGgx"
	 *}
	 */
	$result = json_decode($resultJson, true);

	if ($result["sub"] != $userId) {
		$response = [
		    "status" => "error",
		    "message" => "Invalid request",
		];
		echo json_encode($response);

		output_log($response, "API");
		exit;
	}

	$login_id = "ln_" . $result["sub"];
	$user_name = $result["name"];
	$user_pw = '';
	$user_email = '';

	$query = mysqli_query($connect, "SELECT * FROM Accounts WHERE login_id = '".$login_id."'");
	$already = mysqli_num_rows($query);

	if ($already == 0) {
		
		$info_usn = array();
		$query_usn = "SELECT max(usn) as usn_m FROM Accounts";
		$que_usn = mysqli_query($connect, $query_usn);
		$row_usn = mysqli_fetch_array($que_usn);
		$info_usn = $row_usn;
	
		$real_usn = $info_usn["usn_m"];
		$real_usn++; 
		
		$now = date("Y-m-d H:i:s");
		$join_query = "INSERT INTO Accounts (login_id, login_pw, email, email_cert, createdAt, usn, name, ip) VALUES ('" . $login_id . "',PASSWORD('" . $user_pw . "'),'" . $user_email . "','n', '".$now."', " . $real_usn . ", '" . $user_name . "', '" . $ip . "')";
		mysqli_query($connect, $join_query);

		if ($partner != "") {
			$referral_query = "INSERT INTO AccountsReferral (m_login_id, m_referral, m_date) VALUES ('".$login_id."', '".$partner."', '".$now."')";
			mysqli_query($connect, $referral_query);
		}

	}

	$query = mysqli_query($connect, "SELECT * FROM Accounts WHERE login_id = '" . $login_id . "'");
	$info = mysqli_fetch_array($query);

	$statistics_year = intval(date('Y'));
	$statistics_month = intval(date('m'));
	$statistics_day = intval(date('d'));
	$statistics_hour = intval(date('H'));

	$statistics = mysqli_query($connect, "INSERT INTO LoginLog (m_id, m_login_id, m_usn, m_game_code, m_service_code, m_type, m_year, m_month, m_day, m_hour, m_date, m_success, m_ip) VALUES(" . $info['id'] . ",'" . $login_id . "', " . $info['usn'] . ",'" . $game_code . "', '" . $service_code . "','2'," . $statistics_year . "," . $statistics_month . "," . $statistics_day . "," . $statistics_hour . ",now(),'1','".$ip."' )");

	$_SESSION['sess_login_id'] = $login_id;

	if ($info['name'] != $user_name) {
		$name_query = mysqli_query($connect, "UPDATE Accouts SET name = '" . $user_name . "' WHERE login_id = '" . $login_id . "'");	
	}

	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_JOIN_LINE_GAME", $login_id);

	$array = [
	    "status" => "success",
	    "message" => "Request was successful",
	    "data" => [
			"login_id" => $info["login_id"],
	        "hash" => AES128Encrypt("nebula3", $info["login_id"]."|".time()."|CSVersion:221019"),
			"time" => time()
	    ]
	];
	$response = json_encode($array);
	echo $response;
	
	output_log($response, "API");
	exit;

}


/*
 * Join API
 */


if ( $mode == 'join_web' ) {

	if ( $address == "" )	{
		$array = [
			"status" => "error", 
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$login_id = "lnw_" . $address;
	$user_name = "";
	$user_pw = '';
	$user_email = '';

	$query = mysqli_query($connect, "SELECT * FROM Accounts WHERE login_id = '".$login_id."'");
	$already = mysqli_num_rows($query);

	if ($already == 0) {
		
		$info_usn = array();
		$query_usn = "SELECT max(usn) as usn_m FROM Accounts";
		$que_usn = mysqli_query($connect, $query_usn);
		$row_usn = mysqli_fetch_array($que_usn);
		$info_usn = $row_usn;
	
		$real_usn = $info_usn["usn_m"];
		$real_usn++; 
		
		$now = date("Y-m-d H:i:s");
		$join_query = "INSERT INTO Accounts (login_id, login_pw, email, email_cert, createdAt, usn, name, ip) VALUES ('" . $login_id . "',PASSWORD('" . $user_pw . "'),'" . $user_email . "','n', '".$now."', " . $real_usn . ", '" . $user_name . "', '" . $ip . "')";
		mysqli_query($connect, $join_query);

		if ($partner != "") {
			$referral_query = "INSERT INTO AccountsReferral (m_login_id, m_referral, m_date) VALUES ('".$login_id."', '".$partner."', '".$now."')";
			mysqli_query($connect, $referral_query);
		}

	}

	$query = mysqli_query($connect, "SELECT * FROM Accounts WHERE login_id = '" . $login_id . "'");
	$info = mysqli_fetch_array($query);

	$statistics_year = intval(date('Y'));
	$statistics_month = intval(date('m'));
	$statistics_day = intval(date('d'));
	$statistics_hour = intval(date('H'));

	$statistics = mysqli_query($connect, "INSERT INTO LoginLog (m_id, m_login_id, m_usn, m_game_code, m_service_code, m_type, m_year, m_month, m_day, m_hour, m_date, m_success, m_ip) VALUES(" . $info['id'] . ",'" . $login_id . "', " . $info['usn'] . ",'" . $game_code . "', '" . $service_code . "','2'," . $statistics_year . "," . $statistics_month . "," . $statistics_day . "," . $statistics_hour . ",now(),'1','".$ip."' )");

	$_SESSION['sess_login_id'] = $login_id;

	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_JOIN_WEB_GAME", $login_id);

	$array = [
	    "status" => "success",
	    "message" => "Request was successful",
	    "data" => [
			"login_id" => $info["login_id"],
	        "hash" => AES128Encrypt("nebula3", $info["login_id"]."|".time()."|CSVersion:221019"),
			"time" => time()
	    ]
	];
	$response = json_encode($array);
	echo $response;
	
	output_log($response, "API");
	exit;

}



/*
 * connect wallet API
 */


if ( $mode == 'connectWallet' ) {

	if ( $login_id == "" || $address == "" )	{
		$array = [
			"status" => "error", 
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$query = mysqli_query($connect, "SELECT * FROM AccountsChain WHERE m_login_id = '".$login_id."'");
	$already = mysqli_num_rows($query);

	if ($already <= 0) {
		//ICP OnChain-Data N3QE token minting  /includes/config.php
		mintN3QEToken("N3_SYNC_WALLET_DAPP_PORTAL", $login_id);

		$chain_query = "INSERT INTO AccountsChain (m_login_id, m_account, m_symbol, m_date) VALUES ('" . $login_id . "', '" . $address . "', 'KAIA', '" . date("Y-m-d H:i:s") . "')";
		mysqli_query($connect, $chain_query);
	}

	$array = [
		"status" => "success",
		"message" => "Connect was successful"
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;

}


/*
 * getAddress API
 */


if ( $mode == "getAddress" ) {
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

	$query = mysqli_query($connect, "SELECT * FROM AccountsChain WHERE m_login_id = '".$login_id."'");
	$info = mysqli_fetch_array($query);

	if( $info['m_index'] != "" ) {
		$array = [
			"status" => "success",
			"message" => "Request OK, Success.",
			"data" => [ "address" => $info['m_account'] ]
		];
	} else {
		$array = [
			"status" => "error",
			"message" => "The wallet has not been connected yet.",
		];
	}

	$response = json_encode($array);
	echo $response;
	
	output_log($response, "API");
	exit;
}


/*
 * Login API
 */


if ( $mode == 'login' ) {

	if ( $login_id == "" || $hash == "" || $time == "" )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$gap = time() - $time;

	if ($gap < 0 || $gap > 3600) {
		$array = [
			"status" => "error", 
			"message" => "The request was invalid or cannot be served."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$query = mysqli_query($connect, "SELECT * FROM Accounts WHERE login_id = '" . $login_id . "'");
	$info = mysqli_fetch_array($query);

	$hash_check = AES128Encrypt("nebula3", $info["login_id"]."|".$time."|CSVersion:221019");

	$statistics_year = intval(date('Y'));
	$statistics_month = intval(date('m'));
	$statistics_day = intval(date('d'));
	$statistics_hour = intval(date('H'));

	if ( $hash == $hash_check ) {
		//ICP OnChain-Data N3QE token minting  /includes/config.php
		mintN3QEToken("N3_GET_EXP", $login_id);

		$statistics = mysqli_query($connect, "INSERT INTO LoginLog (m_id, m_login_id, m_usn, m_game_code, m_service_code, m_type, m_year, m_month, m_day, m_hour, m_date, m_success, m_ip) VALUES(" . $info['id'] . ",'" . $login_id . "', " . $info['usn'] . ",'" . $game_code . "', '" . $service_code . "','2'," . $statistics_year . "," . $statistics_month . "," . $statistics_day . "," . $statistics_hour . ",now(),'1','".$ip."' )");

		$_SESSION['sess_login_id'] = $login_id;

		$array = [
			"status" => "success",
			"message" => "Login was successful"
		];
	
	} else {

		$statistics = mysqli_query($connect, "INSERT INTO LoginLog (m_id, m_login_id, m_usn, m_game_code, m_service_code, m_type, m_year, m_month, m_day, m_hour, m_date, m_success, m_ip) VALUES(" . $info['id'] . ",'" . $login_id . "', " . $info['usn'] . ",'" . $game_code . "', '" . $service_code . "','2'," . $statistics_year . "," . $statistics_month . "," . $statistics_day . "," . $statistics_hour . ",now(),'0','".$ip."' )");

		$array = [
			"status" => "error",
			"message" => "Login was unsuccessful"
		];
	}

	$response = json_encode($array);
	echo $response;
	
	output_log($response, "API");
	exit;
}


/*
 * setGameData API
 */


if ( $mode == "setGameData" ) {

	if ( $login_id == "" || $data == "" || $key == "" ) {
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}


	$data = gzdecode(base64_decode(urldecode($data)));


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
/*
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

	$check_data = $data;

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

	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_SET_GAME_DATA", $login_id);


	$query = "INSERT INTO AccountsGameData (m_login_id,m_game_code,m_data,m_date) VALUES ( ?, ?, ?, ?)";
	$stmt = $connect->prepare($query);

	$stmt->bind_param(
		"ssss", $login_id, $game_code, json_encode($data), date("Y-m-d H:i:s")
	);

	$result = $stmt->execute();

//	$result = mysqli_query($connect, "INSERT INTO AccountsGameData (m_login_id,m_game_code,m_data,m_date) VALUES ('".$login_id."', '".$game_code."', '".json_encode($data)."','".date("Y-m-d H:i:s")."')");
	
	if ($result) {
		$array = [
			"status" => "success", 
			"message" => "Request OK, Success."
		];
	} else {
		$array = [
			"status" => "error", 
			"message" => "Request OK, Failure."
		];
	}

	$response = json_encode($array);
	echo $response;

	output_log($response, "DATA");
	exit;
}


/*
 * getGameData API
 */


if ( $mode == "getGameData" ) {

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

	$info_gamedata = array();

	if ( $date_from != "" && $date_to != "" ) {
		$que_gamedata = mysqli_query($connect, "SELECT m_index, m_data, m_date FROM AccountsGameData WHERE m_game_code = '".$game_code."' AND m_login_id='".$login_id."' AND m_date<='".$date_to."' AND m_date>='".$date_from."' ORDER BY m_index DESC");
	}
	else {
		$que_gamedata = mysqli_query($connect, "SELECT m_index, m_data, m_date FROM AccountsGameData WHERE m_game_code = '".$game_code."' AND m_login_id='".$login_id."' ORDER BY m_index DESC LIMIT 1");
	}
	
	$list_data = array();
	$list_date = array();
	while($row_gamedata = mysqli_fetch_array($que_gamedata)) {
		$info_gamedata = $row_gamedata;

		$m_data  = $info_gamedata['m_data'];
		$m_date  = $info_gamedata['m_date'];

		array_push($list_data, json_decode($m_data));
		array_push($list_date, $m_date);
	}
	
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_GET_GAME_DATA", $login_id);

	$array = [
		"status" => "success", 
		"message" => "Request OK, Success.", 
		"count" => sizeof($list_data), 
		"data" => $list_data, 
		"date" => $list_date
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "DATA");
	exit;
}


/*
 * setRewardData API
 */


if ( $mode == "setRewardData" ) {

	if ( $login_id == "" || $data == "" ) {
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}


	$data = escape_string($data);


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

	$check_data = $data;

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


	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_SET_REWARD_DATA", $login_id);


	$result = mysqli_query($connect, "INSERT INTO AccountsRewardData (m_login_id,m_game_code,m_data,m_date) VALUES ('".$login_id."', '".$game_code."', '".json_encode($data)."','".date("Y-m-d H:i:s")."')");
	
	if ($result) {
		$array = [
			"status" => "success", 
			"message" => "Request OK, Success."
		];
	} else {
		$array = [
			"status" => "error", 
			"message" => "Request OK, Failure."
		];
	}

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}


/*
 * getRewardData API
 */


if ( $mode == "getRewardData" ) {

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

	$info_gamedata = array();

	if ( $date_from != "" && $date_to != "" ) {
		$que_gamedata = mysqli_query($connect, "SELECT m_index, m_data, m_date FROM AccountsRewardData WHERE m_game_code = '".$game_code."' AND m_login_id='".$login_id."' AND m_date<='".$date_to."' AND m_date>='".$date_from."' ORDER BY m_index DESC");
	}
	else {
		$que_gamedata = mysqli_query($connect, "SELECT m_index, m_data, m_date FROM AccountsRewardData WHERE m_game_code = '".$game_code."' AND m_login_id='".$login_id."' ORDER BY m_index DESC LIMIT 1");
	}
	
	$list_data = array();
	$list_date = array();
	while($row_gamedata = mysqli_fetch_array($que_gamedata)) {
		$info_gamedata = $row_gamedata;

		$m_data  = $info_gamedata['m_data'];
		$m_date  = $info_gamedata['m_date'];

		array_push($list_data, json_decode($m_data));
		array_push($list_date, $m_date);
	}
	
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_GET_REWARD_DATA", $login_id);

	$array = [
		"status" => "success", 
		"message" => "Request OK, Success.", 
		"count" => sizeof($list_data), 
		"data" => $list_data, 
		"date" => $list_date
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}


/*
 * setScore API
 */


if ( $mode == "setScore" ) {

	if ( $login_id == "" || $type == "" || $score == "" )	{
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

	$chech_data = "type:".$type.",score:".$score; 

	if ( $serial_array["data"] != $chech_data )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}





	$que = mysqli_query($connect, "SELECT * FROM GamesScorebored WHERE m_login_id = '" . $login_id . "' AND m_type = '" . $type . "'");
	$info = mysqli_fetch_array($que);

	if( $info['m_index'] == "" ) {
		$result = mysqli_query($connect, "INSERT INTO GamesScorebored (m_login_id, m_game_code, m_type, m_score, m_date) VALUES ('" .$login_id. "', '" .$game_code. "', '" .$type. "', " .$score. ", '".date("Y-m-d H:i:s")."')");
	} else {
		$result = mysqli_query($connect, "UPDATE GamesScorebored SET m_score = " .$score. " WHERE m_login_id = '" .$login_id. "' AND m_game_code = '" .$game_code. "' AND m_type = '" .$type. "'");
	}

	$que = mysqli_query($connect, "SELECT * FROM GamesScorebored WHERE m_login_id = '" . $login_id . "' AND m_type = '" . $type . "'");
	$info = mysqli_fetch_array($que);
	
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_SET_SCORE", $login_id);

	if ($result) {
		$array = [
			"status" => "success", 
			"message" => "Request OK, Success.",
			"data" => [
				"login_id" => $info['m_login_id'],
				"score" => $info['m_score']	
			]
		];
	} else {
		$array = [
			"status" => "error", 
			"message" => "Request OK, Failure."
		];
	}

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}


/*
 * getRanking API
 */


if ( $mode == "getRanking" ) {

	if ( $type == "" || $quantity == "" )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$ranking = array();

	if($type == "stap" || $type == "STAP") {
		$query = "SELECT m_login_id, m_token_balance AS m_score FROM AccountsChainToken WHERE m_chain = 'KAIA' AND m_symbol = 'STAP' ORDER BY m_token_balance DESC LIMIT " .$quantity;
	} else {
		$query = "SELECT * FROM GamesScorebored WHERE m_game_code = '" .$game_code. "' AND m_type = '" .$type. "' ORDER BY m_score DESC LIMIT " .$quantity;
	}

	$result = mysqli_query($connect, $query);
	while( $row = mysqli_fetch_array($result) ) {
		$info = [ 
			"login_id" => $row['m_login_id'],
			"score" => floor($row['m_score'])
			];

		array_push($ranking, $info);
	}
	
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_GET_ALL_RANKING", $login_id);

	if ($result) {
		$array = [
			"status" => "success", 
			"message" => "Request OK, Success.",
			"data" => $ranking
		];
	} else {
		$array = [
			"status" => "error", 
			"message" => "Request OK, Failure."
		];
	}

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;

}


/*
 * getRanking API
 */


if ( $mode == "getRanking_by_LoginId" ) {

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

	$ranking = array();

	if($type == "stap" || $type == "STAP") {
		$query = "SELECT p1.m_login_id, p1.m_token_balance AS m_score, (SELECT COUNT(*) FROM AccountsChainToken AS p2 WHERE p2.m_token_balance > p1.m_token_balance AND p2.m_chain = 'KAIA' AND p2.m_symbol = 'STAP') + 1 AS rank FROM AccountsChainToken p1 WHERE m_chain = 'KAIA' AND m_symbol = 'STAP' AND p1.m_login_id = '" .$login_id. "'";

	} else {
		$query = "SELECT p1.m_login_id, p1.m_score, (SELECT COUNT(*) FROM GamesScorebored AS p2 WHERE p2.m_score > p1.m_score AND p2.m_type = '" .$type. "') + 1 AS rank FROM GamesScorebored p1 WHERE p1.m_game_code = '" .$game_code. "' AND p1.m_type = '" .$type. "' AND p1.m_login_id = '" .$login_id. "'";
	}

	$result = mysqli_query($connect, $query);
	while( $row = mysqli_fetch_array($result) ) {
		$info = [ 
			"login_id" => $row['m_login_id'],
			"ranking" => $row['rank'],
			"score" => floor($row['m_score'])
			];

		array_push($ranking, $info);
	}
	
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_GET_USER_RANKING", $login_id);

	if ($result) {
		$array = [
			"status" => "success", 
			"message" => "Request OK, Success.",
			"data" => $ranking
		];
	} else {
		$array = [
			"status" => "error", 
			"message" => "Request OK, Failure."
		];
	}

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;

}


/*
 * getSTAPBalance API
 */


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

	$que = mysqli_query($connect, "SELECT * FROM AccountsChainToken WHERE m_chain = 'KAIA' AND m_symbol = 'STAP' AND m_login_id = '" . $login_id . "'");
	$info = mysqli_fetch_array($que);
	
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_GET_STAP", $login_id);
	
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
	exit;
}


/*
 * sendSTAP API
 */


if ($mode == "sendSTAP") {
	if ( $login_id == "" || $amount == "" )	{
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

	$check_data = "amount:".$amount;

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





	$que = mysqli_query($connect, "SELECT * FROM AccountsChain WHERE m_symbol = 'KAIA' AND m_login_id = '" . $login_id . "'");
	$info = mysqli_fetch_array($que);
	
	if ($info["m_index"] != "") {
		$que_check = mysqli_query($connect, "SELECT m_index FROM AccountsChainToken WHERE m_login_id = '".$login_id."' AND m_chain = 'KAIA' AND m_symbol = 'STAP'");
		$info_check = mysqli_fetch_array($que_check);
		
		if($info_check['m_index'] != "") {
			mysqli_query($connect, "UPDATE AccountsChainToken SET m_token_balance = m_token_balance + " . $amount . " WHERE m_chain = 'KAIA' AND m_symbol = 'STAP' AND m_login_id = '" . $login_id . "'");
		} else {
			mysqli_query($connect, "INSERT INTO AccountsChainToken (m_login_id, m_chain, m_symbol, m_token_balance) VALUES ('" . $login_id . "', 'KAIA', 'STAP', ".$amount.")");
		}
		
		//ICP OnChain-Data N3QE token minting  /includes/config.php
		mintN3QEToken("N3_SET_STAP", $login_id);
		
		mysqli_query($connect, "INSERT INTO AccountsChainHistory (m_login_id, m_token, m_method, m_amount, m_date) VALUES ('".$login_id."', 'STAP', '".$mode."', ".$amount.", '".date("Y-m-d H:i:s")."')");

		$array = [
			"status" => "success", 
			"message" => "Request OK, Success.", 
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
	exit;
}


/*
 * getNFTTokenId API
 */


if ($mode == 'getNFTTokenId') {

	if ( $address == "" || $type == "" ) {
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
		case "1star" :
			$tokenAddress = "0xca287e5ec6280619b3e2c72052f3c89cd10da5b6";
			break;
		case "2star" :
			$tokenAddress = "0x5825e35fe15ca508e4a9f8c67e51f8290efb6611";
			break;
		case "3star" :
			$tokenAddress = "0x74e382d328d0c03a4c11b4a1a14a021dc240615d";
			break;
	}

	$url = "https://mainnet-oapi.kaiascan.io/api/v1/nfts/".$tokenAddress."/inventories";
	
//	if ($address != "") {
		$data = [
			"keyword" => $address,
			"size" => 2000
		];
//	} else {
//		$data = [
//			"size" => 2000
//		];
//	}

	$response = curl_post_kaiascan($url, $data);
	$response = json_decode($response, true);
	
	$tokenList = array();
	foreach($response["results"] as $row) {
	//	echo "tokenId: ".$row['token_id'];
	//	echo "<br>";
		$tokenList[] = $row['token_id'];
	}
	
	$que = mysqli_query($connect, "SELECT m_login_id FROM AccountsChain WHERE m_address = '".$address."'");
	$info = mysqli_fetch_assoc($que);

	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_GET_USER_NFT", $info['m_login_id']);

	$array = [
		"status" => "success", 
		"message" => "Request OK, Success.",
		"data" => [
			"tokenId" => $tokenList,
			"count" => count($tokenList)
		]
	];
	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}


/*
 * getPaymentId API
 */


if ($mode == 'getPaymentId') {

	if ( $address == "" || $currencyCode == "" || $price == "" || $itemIdentifier == "" || $itemName == "" || $imageUrl == "" )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	if ($currencyCode != "KAIA") {
		$pgType = "STRIPE";
	} else {
		$pgType = "CRYPTO";
	}

	$item_price = $price;
	if ($currencyCode == 'USD' || $currencyCode == 'THB' || $currencyCode == 'TWD' ) {
		$item_price = $price * 100;
	}

	$data = [
	    "buyerDappPortalAddress" => $address,
	    "pgType" => $pgType,
	    "currencyCode" => $currencyCode,
	    "price" => $item_price,
	    "paymentStatusChangeCallbackUrl" => "https://".$HOST.".nebula3gamefi.com/extern/api/dapp_payment_callback.php",
	    "items" => [
			[
	            "itemIdentifier" => $itemIdentifier,
	            "name" => $itemName,
	            "imageUrl" => $imageUrl,
	            "price" => $item_price,
	            "currencyCode" => $currencyCode
			]
	    ],
	    "testMode" => $testMode
	];
	$result = curl_post_liff('https://payment.dappportal.io/api/payment-v1/payment/create', $data);

	$now = date("Y-m-d H:i:s");

	$data = json_decode($result, true);

	$que = mysqli_query($connect, "INSERT INTO GamesOrder (m_login_id, m_game_code, m_item_id, m_item_name, m_item_price, m_currency, m_date, m_status, m_payment_id) VALUES ('".$_SESSION['sess_login_id']."', 'tfsf', '".$itemIdentifier."', '".$itemName."', ".$price.", '".$currencyCode."', '".$now."', 'CREATED', '".$data['id']."')");
	

	$que = mysqli_query($connect, "SELECT m_login_id FROM AccountsChain WHERE m_address = '".$address."'");
	$info = mysqli_fetch_assoc($que);
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_GET_PAYMENT_ID", $info['m_login_id']);
	
	$array = [
		"status" => "success", 
		"message" => "Request OK, Success.", 
		"data" => json_decode($result)
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "BILLING");
	exit;
}


/*
 * getPaymentStatus API
 */


if ($mode == "getPaymentStatus") {
	if ( $paymentId == "" )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$url = "https://payment.dappportal.io/api/payment-v1/payment/status?id=" . $paymentId;

	$result = curl_return_json($url);

//	$que = mysqli_query($connect, "UPDATE GamesOrder SET m_status = '".$result['status']."' WHERE m_payment_id = '".$paymentId."'");
	
	$que = mysqli_query($connect, "SELECT m_login_id FROM GamesOrder WHERE m_payment_id = '".$paymentId."'");
	$info = mysqli_fetch_assoc($que);
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_GET_PAYMENT_STATUS", $info['m_login_id']);
	
	$array = [
		"status" => "success", 
		"message" => "Request OK, Success.", 
		"data" => $result
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "BILLING");
	exit;
}


/*
 * setSpinTimes API
 */


if ($mode == "setSpinTimes") {
	
	if ( $login_id == "" || stripos($login_id, "lnw_") !== false || $type == "" || $key == "" )	{
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

	$key_value = $info_game["m_key_value"];
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

	$typeList = ["daily", "invited", "ads"];

	if(!in_array($type, $typeList)) {
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}


	$que = mysqli_query($connect, "SELECT * FROM GamesRouletteLog WHERE m_login_id = '".$login_id."' AND m_game_code = '".$game_code."' AND m_reason = '".$type."' ORDER BY m_index DESC LIMIT 1");
	$info = mysqli_fetch_array($que);
	/*
	$date = $info['m_date'] ? $info['m_date'] : '2025-02-07';

	$currentDate = new DateTime();
	$targetDate = new DateTime($date);

	$interval = $currentDate->diff($targetDate);
	$day = $interval->days;
	*/
	$timestamp = strtotime($info['m_date']);
	$date = date("Y-m-d", $timestamp);
	$now = date("Y-m-d");

	if($date == $now) {
		$array = [
			"status" => "error",
			"message" => "Less than a day since the last time."
		];
		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}


	$que_rouletter = mysqli_query($connect, "SELECT * FROM GamesRoulette WHERE m_login_id = '".$login_id."' AND m_game_code = '".$game_code."'");
	$rowCount = mysqli_num_rows($que_rouletter);
	if( $rowCount <= 0) {
		$query = "INSERT INTO GamesRoulette (m_login_id, m_game_code, m_remaining_spins) VALUES ('".$login_id."', '".$game_code."', 1)";
	} else {
		$query = "UPDATE GamesRoulette SET m_remaining_spins = m_remaining_spins + 1 WHERE m_login_id = '".$login_id."' AND m_game_code = '".$game_code."'";
	}
	
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_SET_SPIN_TIMES", $login_id);
	
	mysqli_query($connect, $query);

	mysqli_query($connect, "INSERT INTO GamesRouletteLog (m_login_id, m_game_code, m_type, m_amount, m_reason, m_date) VALUES ('".$login_id."', '".$game_code."', 'add', 1, '".$type."', '".date("Y-m-d H:i:s")."')");
	
	$array = [
		"status" => "success",
		"message" => "Request OK, Success."
	];
	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;


}


/*
 * getSpinTimes API
 */


if ($mode == "getSpinTimes") {
	
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

	$que = mysqli_query($connect, "SELECT * FROM GamesRoulette WHERE m_login_id = '".$login_id."' AND m_game_code = '".$game_code."'");
	$info = mysqli_fetch_array($que);

	if($info['m_index'] == "") {
		$info['m_remaining_spins'] = 0;
	}
	
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_GET_SPIN_TIMES", $login_id);

	$array = [
		"status" => "success",
		"message" => "Request OK, Success.",
		"data" => [
			"spins" => $info['m_remaining_spins']
		]
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;

}


/*
 * startSpin API
 */


if ($mode == "startSpin") {
	
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

	$que = mysqli_query($connect, "SELECT * FROM GamesRoulette WHERE m_login_id = '".$login_id."' AND m_game_code = '".$game_code."'");
	$info = mysqli_fetch_array($que);

	if($info['m_remaining_spins'] < 1) {
		$array = [
			"status" => "error",
			"message" => "Not enough spins remaining on the wheel."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$item = [
		["item" => "gold", "amount"	=> 400],
		["item" => "rose", "amount"	=> 1],
		["item" => "point", "amount"=> 300],
		["item" => "point", "amount" => 500],
	//	["item" => "kaia", "amount"	=> 0.1],
	//	["item" => "kaia", "amount"	=> 1],
	//	["item" => "kaia", "amount"	=> 30],
	//	["item" => "kaia", "amount"	=> 100],
	];

	$weights = [
		45000,
		40000,
		5000,
		3000,
	//	1500,
	//	450,
	//	45,
	//	5
	];

	$index = normalized_weighted_random($weights);

	$que_rouletter = mysqli_query($connect, "UPDATE GamesRoulette SET m_remaining_spins = m_remaining_spins - 1 WHERE m_login_id = '".$login_id."' AND m_game_code = '".$game_code."'");
	$reason = "Spin the wheel";
	$que_log = mysqli_query($connect, "INSERT INTO GamesRouletteLog (m_login_id, m_game_code, m_type, m_amount, m_spin_result, m_reason, m_date) VALUES ('".$login_id."', '".$game_code."', 'use', 1, '".json_encode($item[$index])."', '".$reason."', '".date("Y-m-d H:i:s")."')");
	
	$item_name = $item[$index]["item"];

	if($item[$index]["item"] == "kaia") {
		$eth = new Ethereum($RPC_Endpoint["kaia"][$node_stage], 443);
		
		$que_chain = mysqli_query($connect, "SELECT * FROM AccountsChain WHERE m_login_id = '".$login_id."'");
		$info_chain = mysqli_fetch_array($que_chain);
		$transfer_to = $info_chain['m_account'];
		$transfer_amount = $item[$index]["amount"];

	//	$result = Kaia_transfer($KAIA_roulette_address_key, $transfer_to, $transfer_amount);
		$data = ["receipt:" => $result];
		$result = mysqli_query($connect, "INSERT INTO AccountsChainHistory (m_login_id, m_token, m_method, m_amount, m_data, m_date) VALUES ('".$login_id."', 'KAIA', '".$mode."', ".$transfer_amount.", '".json_encode($data)."',  '".date("Y-m-d H:i:s")."')");
	}
	
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	mintN3QEToken("N3_PLAY_ROULETTE", $login_id);

	$array = [
		"status" => "success",
		"message" => "Request OK, Success.",
		"data" => $item[$index]
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;

	
}


/*
 * encode API
 */


if ($mode == "encode") {

	if ( $first == "" || $second == "" )	{
		$array = [
			"status" => "error",
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$result = AES128Encrypt($first, $second);
	
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	//mintN3QEToken("N3_EN_TOKEN");

	$array = [
		"status" => "success", 
		"message" => "Request OK, Success.", 
		"data" => urlencode($result)
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}
////////////////////////////////////////////////////////////////////////////////////////////////

function curl_post_liff($url, $fields) {
//    $post_field_string = http_build_query($fields, '', '&');
    $ch = curl_init();

	$header = [
		'X-Client-Id: 63985678-56ab-4f2a-8f36-9a273e2158d3',
		'X-Client-Secret: 9d129656-e59d-44ee-bf14-f71ef5e2d40c',
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

function curl_post_kaiascan($url, $fields) {
    $post_field_string = http_build_query($fields, '', '&');
	$url .= "?" . $post_field_string;
    $ch = curl_init();

	$header = [
		'Accept: */*',
		'Authorization: Bearer e8c464a3-f0d3-4e31-91f0-a4f135e6c03d'
	];

	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close ($ch);
    return $response;
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

function normalized_weighted_random($weights) {
    // 1. Calculate the total weight of all events
    $totalWeight = array_sum($weights);
    
    // 2. Normalize weights: Divide each weight by the total weight to convert to a ratio
    $normalizedWeights = array_map(function($weight) use ($totalWeight) {
        return $weight / $totalWeight;  // The ratio of each weight to the total weight
    }, $weights);
    
    // 3. Randomly generate a number between [0, 1)
    $r = mt_rand() / mt_getrandmax(); // Generate a random number between 0 and 1
    $cumulativeWeight = 0;  // cumulative weight

    // 4. Traverse the normalized weights and select events based on random numbers
    foreach ($normalizedWeights as $index => $normalizedWeight) {
        $cumulativeWeight += $normalizedWeight;  // Accumulate the proportion of each event
        if ($r < $cumulativeWeight) {
            return $index;  // Returns the selected event index
        }
    }

    return false;
}





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