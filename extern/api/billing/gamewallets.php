<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  게임별 잔액조회 및 구매
//  Last Update : 2023-12-05
//
// mode : 
// - GetGameWallets : getCash
// - CreateGamePurchases : purchase
//
// return_path : 
// - N/A
//
// Example Callback 
// https://twtest-18.aurorahunt.xyz/extern/api/billing/gamewallets.php?mode=getCash&login_id=neoguru0&gamecode=spolive
// https://twtest-18.aurorahunt.xyz/extern/api/billing/gamewallets.php?mode=purchase&login_id=neoguru0&gamecode=spolive&product_name=product1&cash=30
//
// 구매 진행시 반드시 해당 게임 캐시의 잔액 조회(getCash)를 먼저 실행 후, 게임 캐시가 충분할 경우 구매 처리하는 것을 추천함.

//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(E_ALL);

header("Content-Type: application/json");

include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$mode			= escape_string(trim($_REQUEST['mode']));				// 호출 모드 ( 필수값 ) : getCash(게임 캐시 잔액 조회), purchase(게임 캐시 차감[구매] 처리) -> 잔액조회와 구매 처리 두가지 모드 제공
$login_id			= escape_string(trim($_REQUEST['login_id']));				// 로그인 아이디 ( 필수값 ) -> usn 확인 
$game_code		= escape_string(trim($_REQUEST['gamecode']));			// 게임 코드 ( 필수 값 )
$product_name	= escape_string(trim($_REQUEST['product_name']));		// 인게임 상품 명 ( 구매 전용 ) -> 영문 이름
$cash				= escape_string(trim($_REQUEST['cash']));					// 차감 캐시 ( 구매 전용 ) -> 반드시 숫자

$order_number	= escape_string(trim($_REQUEST['order_number']));		// 개발사측 오더 넘버 ( 임시, 필수 아님 )



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//	TO DO LIST ???
//
// 각 충환전 시간 맞지 않음 -> php $today로 전환 해야 함
//



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	CHECKING API INITIAL VALUE

// 로그 설정
if ( $mode )				$proc1 = $mode;
if ( $login_id )			$proc2 .= "login_id : " . $login_id;
if ( $game_code )		$proc2 .= ", game_code : " . $game_code;
if ( $product_name )	$proc2 .= ", product_name : " . $product_name;
// if ( $item_id )			$proc2 .= ", item_id : " . $item_id;
if ( $cash )				$proc2 .= ", cash : " . $cash;

// 게임 서버 호출 IP 화이트 리스트 체크 여부 확인 요망 ( by neoguru )
if ( $mode == "" || $login_id == "" || $game_code == "" )	{
	$array = array("result" => "0", "msg" => "Default required parameter not passed."); // 기본 필수 매개 변수가 전달되지 않았습니다.

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "BILLING");
	exit;
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	SETTING AND DECLARE ENVIRONMENT

if ( $configuration['mode'] == "development / alpha" )	$billing_stage = "test";
if ( $configuration['mode'] == "test / debug" )				$billing_stage = "test";
if ( $configuration['mode'] == "service / release" )			$billing_stage = "live";

$HOST = "billapi";

// SiteCode setting : cosmoinfra.net - 1, petpoint.io - 2, tw.aurorahunt.xyz - 3, ko.aurorahunt.xyz - 4 
// HOST AND item_id setting 
if ( strtoupper($lang_code) == "KO" )	{ 
	$HOST .= "ko";
	$isLocal = "ko";
	$SiteCode = 4;

	if ( strtoupper($game_code) == "CLWMC" ) $item_id = 1005;
}
else {
	$isLocal = "global";
	$SiteCode = 3;

	if ( strtoupper($game_code) == "CLWMC" )	$item_id = 1007;
	if ( strtoupper($game_code) == "MM" )		$item_id = 1009;
	if ( strtoupper($game_code) == "SPOLIVE" )	$item_id = 1011;
}

if ( $billing_stage == "test" ) {
	$HOST .= "test";
}

// billing APPID, APPKEY setting
$APPID = $billingAPPID[$game_code][$billing_stage][$isLocal];
$APPKey = $billingAPPKEY[$game_code][$billing_stage][$isLocal];

//echo $APPID . "<br>";
//echo $APPKey . "<br>";
//echo $HOST . "<br>";

if ( $APPID == "" || $APPKey == "" ) {
	$array = array("result" => "0", "msg" => "Invalid parameter or server error."); // 기본 필수 매개 변수가 전달되지 않았습니다.

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "BILLING");
	exit;
}

//exit;


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	CHECKING USN

// usn 확인
$info = array();
$que = mysqli_query($connect, "SELECT usn FROM Accounts WHERE login_id='".$login_id."'");
$row = mysqli_fetch_array($que);
$info = $row;
$usn = $info['usn'];


if ( $usn == "" )	{
	$array = array("result" => "0", "msg" => "No usn, invalid user information.");

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "BILLING");
	exit;
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	GETCASH 

// 게임 캐시 잔액 조회
if ( $mode == "getCash" ) {	
	// 잔액 확인 처리, GET 방식으로 처리
	$szMethod = "GET";

	//게임별 잔액 조회
	$url = "http://" . $HOST . ".aurorahunt.xyz/billing/".$SiteCode."/users/".$usn."/gamewallets?gameCode=".$game_code;	// test url
//	echo $url . "<br>";

	// 서버 시각 기준으로 전달
	$unix_timestamp = time();
	$nonce = time() - 88888888;
	$url_encoding = strtolower(urlencode($url));
	$request_string = $APPID . strtoupper($szMethod) . $url_encoding . $unix_timestamp . $nonce;
	$signature  = base64_encode(hash_hmac("sha256", $request_string, base64_decode($APPKey), true)); // APPKey값은 바이너리로 변환한 후에 키 값으로 사용
	$pltoken = "PLTOKEN " . $APPID . ":" . $signature . ":" . $nonce . ":" . $unix_timestamp;

	// 헤더 설정
	$headers = array(
		"Authorization" => $pltoken, 
		"Content-Type" => "application/json"
	);

	$curl = new Curl();
	
	$curl->set_headers($headers);
	$res = $curl->get($url);
	$status_code = $res->headers["Status-Code"];

	if ($status_code == "200") {	// 성공 : OK

		$billing_data_structure = json_decode($res->body);
		$totalGameCash = $billing_data_structure->data->gamewallets[0]->totalGameCash;
		$array = array("result" => "1", "msg" => "Request OK, Success.", "totalGameCash" => $totalGameCash);

		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "BILLING");
	} 
	else {	// 실패 : ERROR

		$array = array("result" => "0", "msg" => "HTTP Status Code ( $status_code ), Failure.");

		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "BILLING");
	}

	//	echo $res;
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	PURCHASE

// 게임 캐시 구매 처리
if ( $mode == "purchase" ) {	

	if ( $product_name == "" || $item_id == "" || $cash == "" )	{
		$array = array("result" => "0", "msg" => "Purchase related parameters for game cash deduction have not been delivered."); // 게임 캐시 차감을 위한 구매 관련 파라미터가 전달되지 않았습니다.

		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "BILLING");
		exit;
	}

	// 구매(캐시 차감) 처리, POST 방식으로 처리
	$szMethod = "POST";

	$url = "http://" . $HOST . ".aurorahunt.xyz/billing/".$SiteCode."/users/".$usn."/purchases?gameCode=".$game_code;	// test url

	// 서버 시각 기준으로 전달
	$unix_timestamp = time();
	$nonce = time() - 88888880;
	$url_encoding = strtolower(urlencode($url));
	$base64_sha256 = base64_encode(hash("sha256", $req_content, true)); // 인자값을 넘겨줘야 결과를 바이너리로 리턴 (안 그럴 경우 Hexa인코딩한 결과를 넘겨줌)

	$params = array(
		"siteCode" 				=> "$SiteCode", 
		"userNo" 				=> $usn, 
		"gameCode" 			=> "$game_code", 
		"userId" 					=> "$login_id", 
		"userName" 			=> "$login_id", 
		"itemId" 					=> "$item_id", 
		"gameItemId" 			=> $product_name . " / " . $cash, 
		"productName" 		=> "$product_name", 
		"productCount" 		=> 1, 
		"chargeAmount" 		=> $cash, 
		"purchaseLocation" 	=> 2, 		// 1: Web(웹상점), 2: Game(인게임)
		"ipAddr" 		=> $_SERVER['REMOTE_ADDR']
	);

	$req_content = json_encode($params, JSON_UNESCAPED_UNICODE);
	$base64_sha256 = base64_encode(mhash(MHASH_SHA256, $req_content));
	$request_string = $APPID . strtoupper($szMethod) . $url_encoding . $unix_timestamp . $nonce. $base64_sha256;
	$signature  = base64_encode(hash_hmac("sha256", $request_string, base64_decode($APPKey), true)); // APPKey값은 바이너리로 변환한 후에 키 값으로 사용
	$pltoken = "PLTOKEN " . $APPID . ":" . $signature . ":" . $nonce . ":" . $unix_timestamp;

	// 헤더 설정
	$headers = array(
		"Authorization" => $pltoken,
		"Content-Type" => "application/json"
	);

	$curl = new Curl();
	$curl->set_headers($headers);
	$res =  $curl->post_json($url,$req_content);

//	var_dump($res);

	$status_code = $res->headers["Status-Code"];
	$body = $res->body;
	$body = json_decode($res->body);
	$body = $body->data;

//	print_r($body);
//	var_dump($body);
//	echo $body;
//	echo $res;
//	print_r($data);
//	exit;

	if ($status_code == "200") {	// 성공 : OK
		
		$array = array("result" => "1", "msg" => "Request OK, Success.", "data" => $body, "order_number" => $order_number);
		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "BILLING");
	}
	else {	// 실패 : ERROR

		$array = array("result" => "0", "msg" => "HTTP Status Code - $status_code, Failure.");
		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "BILLING");
	}

//	echo $res;
}
?>

