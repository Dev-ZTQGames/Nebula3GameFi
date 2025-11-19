<?php
//  DB 노드 연결 플랫폼 API
//  Last Update : 2023-03-12
//
// mode : 
// - getBalance
// - consumeToken
// - sendToken
//
// return_path : 
// - N/A
//

//
// 구매 진행시 반드시 해당 게임 캐시의 잔액 조회(getCash)를 먼저 실행 후, 게임 캐시가 충분할 경우 구매 처리하는 것을 추천함.

header("Content-Type: application/json");

include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
    $_REQUEST = array_merge($_REQUEST, $data);
}

$mode			= escape_string(trim($_REQUEST['mode']));				// mode 메소드 값
$login_id			= escape_string(trim($_REQUEST['id']));				// 로그인 아이디 ( 필수값 )
$hash				= escape_string(trim($_REQUEST['hash']));
$time				= escape_string(trim($_REQUEST['time']));
$game_code		= escape_string(trim($_REQUEST['gamecode']));			// 게임 코드 ( 필수 값 )

$symbol			= escape_string(trim($_REQUEST['symbol']));
$amount			= escape_string(trim($_REQUEST['amount']));

$event			= escape_string(trim($_REQUEST['event']));	

// 로그 설정
if ( $mode )				$proc1 = $mode;
if ( $login_id )			$proc2 .= "login_id : " . $login_id;
if ( $hash )				$proc2 .= "hash : " . $hash;
if ( $time )				$proc2 .= "time : " . $time;
if ( $game_code )		$proc2 .= ", game_code : " . $game_code;

if ( $symbol )			$proc2 .= ", symbol : " . $symbol;
if ( $amount )		$proc2 .= ", amount : " . $amount;


// 게임 서버 호출 IP 화이트 리스트 체크 여부 확인 요망 ( by neoguru )
if ( $mode == "" || $login_id == "" || $game_code == "" )	{
	$array = [
		"status" => "error",
		"code" => "1001",
		"msg" => "Default required parameter not passed.",
	]; // 기본 필수 매개 변수가 전달되지 않았습니다.

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	exit;
}

$query_account = mysqli_query($connect, "SELECT id FROM Accounts WHERE login_id = '".$login_id."'");
$info_account = mysqli_fetch_array($query_account);
if (  $info_account['id'] == "" )	{
	$array = [
		"status" => "error",
		"code" => "3001",
		"msg" => "User does not exist."
	]; // 기본 필수 매개 변수가 전달되지 않았습니다.

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	exit;
}


$query_game = mysqli_query($connect, "SELECT m_index, m_chain, m_key_value FROM Games WHERE m_game_code = '".$game_code."'");
$info_game = mysqli_fetch_array($query_game);

$headers = array_change_key_case(getallheaders(), CASE_LOWER);

list($type, $token) = explode(" ", $headers['authorization'], 2);

if (isset($token) && $token == $info_game['m_key_value'] && $type == 'Bearer') {
    $auth = $token;
} else {
	$array = [
		"status" => "error",
		"code" => "2002",
		"msg" => "Invalid token."
	];

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	exit;

}


if ( $info_game['m_index'] == "" ) {
	$array = [
		"status" => "error",
		"code" => "1003",
		"message" => "Not allowed game code."
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "NODE");
	exit;
}


if ( $mode == 'login' ) {

	if ( $login_id == "" || $hash == "" || $time == "" )	{
		$array = [
			"status" => "error",
			"code" => "3001",
			"message" => "Default required parameter not passed."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "API");
		exit;
	}

	$query = mysqli_query($connect, "SELECT * FROM Accounts WHERE login_id = '" . $login_id . "'");
	$info = mysqli_fetch_array($query);

	$hash_check = AES128Encrypt("nebula3gamefi", $info["login_id"]."|".$time."|CSVersion:221019");

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
			"code" => "2000",
			"message" => "Login success"
		];
	
	} else {

		$statistics = mysqli_query($connect, "INSERT INTO LoginLog (m_id, m_login_id, m_usn, m_game_code, m_service_code, m_type, m_year, m_month, m_day, m_hour, m_date, m_success, m_ip) VALUES(" . $info['id'] . ",'" . $login_id . "', " . $info['usn'] . ",'" . $game_code . "', '" . $service_code . "','2'," . $statistics_year . "," . $statistics_month . "," . $statistics_day . "," . $statistics_hour . ",now(),'0','".$ip."' )");

		$array = [
			"status" => "error",
			"code" => "1003",
			"message" => "Login failed"
		];
	}

	$response = json_encode($array);
	echo $response;
	
	output_log($response, "API");
	exit;
}

if( $mode == "logout" ) {

	$array = [
		"status" => "success",
		"code" => "2000",
		"message" => "Logout success"
	];

	$response = json_encode($array);
	echo $response;

	output_log($response, "API");
	exit;
}



$chain = $info_game['m_chain'];


if( $mode == "getAddress") {
	$info_checkWallet = array();
	$que_checkWallet = mysqli_query($connect, "SELECT m_account FROM AccountsChain WHERE m_symbol = '".$chain."' AND m_login_id='".$login_id."'");
	$info_checkWallet = mysqli_fetch_array($que_checkWallet);
	
	$address = $info_checkWallet['m_account'];
	
	if($address == "") {
		$array = [
			"status" => "error", 
			"code" => "3001",
			"message" => "Address does not exist."
		];

	} else {
		$array = [
			"status" => "success", 
			"code" => "2000",
			"message" => "Request OK, Success.",
			"address" => $address
		];
	}


	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	exit;
}


//
if ( $mode == "getBalance" ) {

	$query_token = mysqli_query($connect, "SELECT * FROM TokenList WHERE m_game_code = '".$game_code."' AND m_chain = '".$chain."' AND m_token_symbol = '".$symbol."'");
	$info_token = mysqli_fetch_array($query_token);

	if ( $info_token['m_index'] == "" ) {
		$array = [
			"status" => "error",
			"code" => "1003",
			"message" => "Not allowed token."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "NODE");
		exit;
	}

	// 지갑 유무 확인
	$info_checkWallet = array();
	$que_checkWallet = mysqli_query($connect, "SELECT m_token_balance FROM AccountsChainToken WHERE m_chain = '".$chain."' AND m_symbol = '".$symbol."' AND m_login_id='".$login_id."'");
	$row_checkWallet = mysqli_fetch_array($que_checkWallet);
	$info_checkWallet = $row_checkWallet;
	
	$balance = $info_checkWallet["m_token_balance"] ?? 0;

//	print_r($return_data);exit;
	$array = [
		"status" => "success", 
		"code" => "2000",
		"message" => "Request OK, Success.",
		"balance" => $balance
	];

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	exit;
}


// 
if ( $mode == "consumeToken" ) {

	$query_token = mysqli_query($connect, "SELECT * FROM TokenList WHERE m_game_code = '".$game_code."' AND m_chain = '".$chain."' AND m_token_symbol = '".$symbol."'");
	$info_token = mysqli_fetch_array($query_token);

	if ( $info_token['m_index'] == "" ) {
		$array = [
			"status" => "error",
			"code" => "1003",
			"message" => "Not allowed token."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "NODE");
		exit;
	}

	$info_checkChain = array();
	$que_checkChain = mysqli_query($connect, "SELECT m_token_balance FROM AccountsChainToken WHERE m_chain = '".$chain."' AND m_symbol = '".$symbol."' AND m_login_id='".$login_id."'");
	$row_checkChain = mysqli_fetch_array($que_checkChain);
	$info_checkChain = $row_checkChain;

	if ( $info_checkChain['m_token_balance'] < $amount) {
		$array = [
			"status" => "error",
			"code" => "3003",
			"message" => "Insufficient balance."
		];

		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "NODE");
		exit;
	}

	$info_checkWallet = array();
	$que_checkWallet = mysqli_query($connect, "UPDATE AccountsChainToken SET m_token_balance = m_token_balance - ".$amount." WHERE m_chain = '".$chain."' AND m_symbol = '".$symbol."' AND m_login_id = '".$login_id."'");

	if ($que_checkWallet) {
		$array = [
			"status" => "success", 
			"code" => "2000",
			"message" => "Request OK, Success."
		];
	} else {
		$array = [
			"status" => "error",
			"code" => "5001",
			"message" => "Request OK, Failed.",
		];
	}

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	exit;
}



//
if ( $mode == "sendToken" ) {

	$query_token = mysqli_query($connect, "SELECT * FROM TokenList WHERE m_game_code = '".$game_code."' AND m_chain = '".$chain."' AND m_token_symbol = '".$symbol."'");
	$info_token = mysqli_fetch_array($query_token);

	if ( $info_token['m_index'] == "" ) {
		$array = [
			"status" => "error",
			"code" => "1003",
			"message" => "Not allowed token."
		];

		$response = json_encode($array);
		echo $response;

		output_log($response, "NODE");
		exit;
	}

	$query = "INSERT INTO AccountsChainToken (m_login_id, m_chain, m_symbol, m_token_balance) VALUES ('" . $login_id . "', '".$chain."', '".$symbol."', ".$amount.") ON DUPLICATE KEY UPDATE m_token_balance = m_token_balance + ".$amount;
	$que_Wallet = mysqli_query($connect, $query);
	
	if ($que_Wallet) {
		$array = [
			"status" => "success",
			"code" => "2000",
			"message" => "Request OK, Success."
		];
	} else {
		$array = [
			"status" => "error",
			"code" => "5001",
			"message" => "Request OK, Failed."
		];
	}

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	exit;
}

if( $mode == "OnChain-Data") {

	$memo = "N3_".strtoupper($event);
	mintN3QEToken($memo, $login_id);

	$array = [
		"status" => "success",
		"code" => "2000",
		"message" => "Request OK, Success."
	];

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	exit;
}


