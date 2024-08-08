<?php
//  PSCT 노드 연결 플랫폼 API
//  Last Update : 2023-03-12
//
// mode : 
// - getAddress
// - getBalance
// - MiningStart
//
// return_path : 
// - N/A
//

//
// 구매 진행시 반드시 해당 게임 캐시의 잔액 조회(getCash)를 먼저 실행 후, 게임 캐시가 충분할 경우 구매 처리하는 것을 추천함.

header("Content-Type: application/json");

include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$mode			= escape_string(trim($_REQUEST['mode']));				// mode 메소드 값
$login_id			= escape_string(trim($_REQUEST['login_id']));				// 로그인 아이디 ( 필수값 )
$game_code		= escape_string(trim($_REQUEST['game_code']));			// 게임 코드 ( 필수 값 )

$amount			= escape_string(trim($_REQUEST['amount']));				// 토큰 수량 ( PSCT )

$data			= escape_string(trim($_REQUEST['data']));						// 게임 데이터 -> web2, web3 관련 게임 데이터
$date_from	= escape_string(trim($_REQUEST['from']));				// 게임 데이터 검색 시작 시간
$date_to		= escape_string(trim($_REQUEST['to']));					// 게임 데이터 검색 끝 시간

//$option			= escape_string(trim($_REQUEST['option']));				//  채굴 옵션

//echo $option;

//print_r(explode("|",$option));
//exit;

// 로그 설정
if ( $mode )				$proc1 = $mode;
if ( $login_id )			$proc2 .= "login_id : " . $login_id;
if ( $game_code )		$proc2 .= ", game_code : " . $game_code;

// 게임 서버 호출 IP 화이트 리스트 체크 여부 확인 요망 ( by neoguru )
if ( $mode == "" || $login_id == "" || $game_code == "" )	{
	$array = array("result" => "0", "msg" => "Default required parameter not passed."); // 기본 필수 매개 변수가 전달되지 않았습니다.

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	exit;
}

if( $configuration['mode'] == "development / alpha" )		{ $HOST = $nodeURL[$game_code]["dev"]; $M_PUBLIC = $AH_node[$game_code]["dev"]["public"]; }
if( $configuration['mode'] == "test / debug" )				{ $HOST = $nodeURL[$game_code]["test"]; $M_PUBLIC = $AH_node[$game_code]["test"]["public"]; }
if( $configuration['mode'] == "service / release" )			{ $HOST = $nodeURL[$game_code]["live"]; $M_PUBLIC = $AH_node[$game_code]["live"]["public"]; }


// 호스트 체크 
if ( $HOST == "" )	{
	$array = array("result" => "0", "msg" => "Host information is not set properly due to an invalid game_code."); // 기본 필수 매개 변수가 전달되지 않았습니다.

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	exit;
}


// login_id에 대한 지갑 주소가 있는지 확인하고 없으면 발급해서 전달한다. 
if ( $mode == "setGameData" ) {

	// 지갑 부존재시 생성하여 디비 삽입
	mysqli_query($connect, "INSERT INTO AccountsGameData (m_login_id,m_game_code,m_data,m_date) VALUES ('".$login_id."', '".$game_code."', '".json_encode($data)."','".date("Y-m-d H:i:s")."')");

	$array = array("result" => "1", "msg" => "Request OK, Success.");

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "DATA");

}

// login_id에 대한 지갑 주소가 있는지 확인하고 없으면 발급해서 전달한다. 
if ( $mode == "getGameData" ) {

	// 지갑 유무 확인
	$info_gamedata = array();

	if ( $date_from != "" && $date_to != "" ) {
		$que_gamedata = mysqli_query($connect, "SELECT m_index, m_data, m_date FROM AccountsGameData WHERE m_game_code = '".$game_code."' AND m_login_id='".$login_id."' AND m_date<='".$date_to."' AND m_date>='".$date_from."' ORDER BY m_index DESC");
	//	echo "SELECT m_index, m_data, m_date FROM AccountsGameData WHERE m_game_code = '".$game_code."' AND m_login_id='".$login_id."' AND m_date<='".$date_to."' AND m_date>='".$date_from."' ORDER BY m_index DESC";
	}
	else {
		$que_gamedata = mysqli_query($connect, "SELECT m_index, m_data, m_date FROM AccountsGameData WHERE m_game_code = '".$game_code."' AND m_login_id='".$login_id."' ORDER BY m_index DESC");
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

	$array = array("result" => "1", "msg" => "Request OK, Success.", "count(s)" => sizeof($list_data), "data" => $list_data, "date" => $list_date);

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "DATA");

}

// login_id에 대한 지갑 주소가 있는지 확인하고 없으면 발급해서 전달한다. 
if ( $mode == "getAddress" ) {

	// 지갑 유무 확인
	$info_checkWallet = array();
	$que_checkWallet = mysqli_query($connect, "SELECT m_index, m_key_public FROM AccountsPSCTNodeWallet WHERE m_game_code = '".$game_code."' AND m_login_id='".$login_id."'");
	$row_checkWallet = mysqli_fetch_array($que_checkWallet);
	$info_checkWallet = $row_checkWallet;

	$m_index			= $info_checkWallet['m_index'];
	$m_key_public  = $info_checkWallet['m_key_public'];

	if ( $m_index == "" ) {
		$url = $HOST . "/api/account.php?address=getAddress";
		$return_data = curl_return_json($url);

		// 지갑 부존재시 생성하여 디비 삽입
		mysqli_query($connect, "INSERT INTO AccountsPSCTNodeWallet (m_login_id,m_game_code,m_key_private, m_key_public,m_date) VALUES ('".$login_id."', '".$game_code."', '".$return_data["response"]["private_key"]."','".$return_data["response"]["public_key"]."','".date("Y-m-d H:i:s")."')");

	//	$array = array("result" => "1", "msg" => "Request OK, Success.", "private_key" => $return_data["response"]["private_key"], "public_key" => $return_data["response"]["public_key"]);
		$array = array("result" => "1", "msg" => "Request OK, Success.", "public_key" => $return_data["response"]["public_key"]);

		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "NODE");
	}
	else {

		$array = array("result" => "1", "msg" => "Request OK, Success.", "public_key" => $m_key_public);

		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "NODE");

	}

}


//
if ( $mode == "getBalance" ) {


	// 지갑 유무 확인
	$info_checkWallet = array();
	$que_checkWallet = mysqli_query($connect, "SELECT m_token_balance FROM AccountsChain WHERE m_symbol = 'ICP' AND m_login_id='".$login_id."'");
	$row_checkWallet = mysqli_fetch_array($que_checkWallet);
	$info_checkWallet = $row_checkWallet;

//	print_r($return_data);exit;
	$array = array("result" => "1", "msg" => "Request OK, Success.", "balance" => $info_checkWallet["m_token_balance"]);

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	exit;
		
	
/*
	// 지갑 유무 확인
	$info_checkWallet = array();
	$que_checkWallet = mysqli_query($connect, "SELECT m_key_public FROM AccountsPSCTNodeWallet WHERE m_game_code = '".$game_code."' AND m_login_id='".$login_id."'");
	$row_checkWallet = mysqli_fetch_array($que_checkWallet);
	$info_checkWallet = $row_checkWallet;

	$m_key_public  = $info_checkWallet['m_key_public'];

	$url = $HOST . "/api/balance.php?address=" . $m_key_public;
//	echo $url;exit;
	$return_data = curl_return_json($url);

	$array = array("result" => "1", "msg" => "Request OK, Success.", "balance" => $return_data["response"]["balance"]);

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	*/

}


// 
if ( $mode == "MiningStart" ) {

	// 지갑 유무 확인
	$info_checkWallet = array();
	$que_checkWallet = mysqli_query($connect, "SELECT m_key_public, m_NFTs, m_NFT_images FROM AccountsPSCTNodeWallet WHERE m_game_code = '".$game_code."' AND m_login_id='".$login_id."'");
	$row_checkWallet = mysqli_fetch_array($que_checkWallet);
	$info_checkWallet = $row_checkWallet;

	$m_key_public  = $info_checkWallet['m_key_public'];
	$m_NFTs			= $info_checkWallet['m_NFTs'];
	$m_NFT_images	= $info_checkWallet['m_NFT_images'];

	$url = $HOST . "/api/mining.php?address=" . $m_key_public . "&mode=start&option=" .  $m_NFTs;
	$return_data = curl_return_json($url);

	$array = array("result" => "1", "msg" => "Request OK, Success.", "status" => $return_data["response"]["start"], "NFTs" => $m_NFTs, "NFT_images" => $m_NFT_images);

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");

}

// 
if ( $mode == "MiningStatus" ) {

	// 지갑 유무 확인
	$info_checkWallet = array();
	$que_checkWallet = mysqli_query($connect, "SELECT m_key_public FROM AccountsPSCTNodeWallet WHERE m_game_code = '".$game_code."' AND m_login_id='".$login_id."'");
	$row_checkWallet = mysqli_fetch_array($que_checkWallet);
	$info_checkWallet = $row_checkWallet;

	$m_key_public  = $info_checkWallet['m_key_public'];

	$url = $HOST . "/api/mining.php?address=" . $m_key_public . "&mode=status";
	$return_data = curl_return_json($url);

	$array = array("result" => "1", "msg" => "Request OK, Success.", "status" => $return_data["response"]["status"]);

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");

}

// 
if ( $mode == "NFTInfo" ) {

	// 지갑 유무 확인
	$info_checkWallet = array();
	$que_checkWallet = mysqli_query($connect, "SELECT m_NFTs, m_NFT_images FROM AccountsPSCTNodeWallet WHERE m_game_code = '".$game_code."' AND m_login_id='".$login_id."'");
	$row_checkWallet = mysqli_fetch_array($que_checkWallet);
	$info_checkWallet = $row_checkWallet;

	$m_NFTs			= $info_checkWallet['m_NFTs'];
	$m_NFT_images	= $info_checkWallet['m_NFT_images'];

	$NFT_list = explode('|', $m_NFTs);
	foreach($NFT_list as $token) {
		$que_checkNFT = mysqli_query($connect, "SELECT m_level, m_breeding FROM AccountsNFTData WHERE m_token_id = '" . $token . "'");
		$info_checkNFT = mysqli_fetch_array($que_checkNFT);
		$info_checkNFT['m_level'] = isset($info_checkNFT['m_level']) ? $info_checkNFT['m_level'] : '0';
		$level .= $info_checkNFT['m_level'].'|';

		$info_checkNFT['m_breeding'] = isset($info_checkNFT['m_breeding']) ? $info_checkNFT['m_breeding'] : '5';
		$breeding .= $info_checkNFT['m_breeding'].'|';

		if ($token >= 0 && $token <= 5554) {
		    $rarity .= "Basic|";
		} else if ($token >= 5555 && $token <= 7776) {
		    $rarity .= "Rare|";
		} else if ($token >= 7777 && $token <= 8887) {
		    $rarity .= "Epic|";
		} else if ($token >= 8888 && $token <= 9443) {
		    $rarity .= "Unique|";
		} else if ($token >= 9444 && $token <= 9554) {
		    $rarity .= "Legend|";
		}
	}
	
	$level_list = substr($level, 0, -1);
	$breeding_list = substr($breeding, 0, -1);
	$rarity_list = substr($rarity, 0, -1);

	$array = array("result" => "1", "msg" => "Request OK, Success.", "NFTs" => $m_NFTs, "NFT_images" => $m_NFT_images, "NFT_level" => $level_list, "NFT_breeding" => $breeding_list, "rarity" => $rarity_list);

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");

}

// 
if ( $mode == "Consume" ) {
	switch($game_code) {
		case 'mm':
			$symbol = 'ICP';
			break;
	}
	// 지갑 유무 확인

	$info_checkChain = array();
	$que_checkChain = mysqli_query($connect, "SELECT m_token_balance FROM AccountsChain WHERE m_symbol = '".$symbol."' AND m_login_id='".$login_id."'");
	$row_checkChain = mysqli_fetch_array($que_checkChain);
	$info_checkChain = $row_checkChain;

	if ( $info_checkChain['m_token_balance'] < $amount) {
		$array = array("result" => "1", "msg" => "Request OK, Success.", "status" => "0");

		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "NODE");
	}

	$info_checkWallet = array();
	$que_checkWallet = mysqli_query($connect, "UPDATE AccountsChain SET m_token_balance = m_token_balance - ".$amount." WHERE m_symbol = '".$symbol."' AND m_login_id='".$login_id."'");

	if ($que_checkWallet) {
		$array = array("result" => "1", "msg" => "Request OK, Success.", "status" => "1");
	} else {
		$array = array("result" => "1", "msg" => "Request OK, Success.", "status" => "0");
	}

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");

}

//
if ( $mode == "Reward" ) {
	/*
	switch($game_code) {
		case 'mm':
			$symbol = 'ICP';
			break;
	}
	// 지갑 유무 확인
	$info_checkWallet = array();
	$que_checkWallet = mysqli_query($connect, "UPDATE AccountsChain SET m_token_balance = m_token_balance + " . $amount . " WHERE m_symbol = '".$symbol."' AND m_login_id='".$login_id."'");
//	$row_checkWallet = mysqli_fetch_array($que_checkWallet);
	$info_checkWallet = $row_checkWallet;
	
	if ($que_checkWallet) {
		$array = array("result" => "1", "msg" => "Request OK, Success.", "status" => "1");
	} else {
		$array = array("result" => "1", "msg" => "Request OK, Success.", "status" => "0");
	}

	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "NODE");
	*/

	if ( $game_code == 'mm' ){
		if ( $configuration['mode'] == "service / release" )	{ 
			$HOST = 'live'; 
		} else {
			$HOST = 'test';
		}

		$checkChain_query = "SELECT m_account, m_token_balance FROM AccountsChain WHERE m_login_id = '" . $login_id . "' AND m_symbol = 'ICP'";
		$que_checkChain = mysqli_query($connect, $checkChain_query);
		$row_checkChain = mysqli_fetch_array($que_checkChain);

		$user_account = $row_checkChain['m_account'];
		$balance = $row_checkChain['m_token_balance'];

		$amount *= 100000000;

		$url = "https://icp".$HOST.".nebula3gamefi.com:8080/api/transferOut/".$user_account."/".$amount;
		$response = curl_return_json($url);

		if (isset($response['result']['Ok'])) {
			$array = array("result" => "1", "msg" => "Request OK, Success.", "status" => '1');
			echo json_encode($array);
			exit;
		}

		$array = array("result" => "1", "msg" => "Request OK, Success.", "status" => '0');
		echo json_encode($array);
		exit;
	}
}
?>

