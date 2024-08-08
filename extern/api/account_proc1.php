<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
/////	[ 애니멀 프랜즈 ] 익스텐션 게임 서버 예제
/////
/////    - 게임 로그인 처리 중계
/////
/////    * 본 파일은 게임의 서버 사이드에서 구동되는 게임 서버의 일부분
/////    * Server to Server로 방화벽 오픈하여 특정 IP를 직접 연결하여 사용(보안이슈)
/////    * 따라서 어느정도 안정성이나 일정 수준의 네트워크 속도를 보장하는 Pull안에 서버가 deploy되어 있어야 함
/////    * 상기 내용을 위해서 hosts 파일 수정 또는 사설 연결도 고려 요망
/////
/////	 * account.php가 연동하기 어려워 개발사에 제공하기 위해 만들어진 간소화된 버전
/////	 * gameServer_animalfriends.php의 상위 버전으로써, 본 파일로 통합되면 gameServer_animalfriends.php 파일은 삭제 바람
/////
/////
/////	 * to do list - IP 화이트리스트 기능 추가 해야 함
/////
/////   last update : 2023-09-25
/////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	header("Content-Type: application/json");

	include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

	$mode		= escape_string(trim($_REQUEST['mode']));
	$login_id		= escape_string(trim($_REQUEST['id']));
	$login_pw	= escape_string(trim($_REQUEST['pw']));
	$game_code	= escape_string(trim($_REQUEST['gamecode']));

	$info_game = array();	
	$query_game = "SELECT * FROM Games WHERE m_game_code = '" . $game_code . "'";
	$que_game = mysqli_query($connect, $query_game);
	$num_game = mysqli_num_rows($que_game);
	$row_game = mysqli_fetch_array($que_game);
	$info_game = $row_game;

	// 로그인 처리 체크
	if ( $mode == "login" ) {
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 예) https://test-18.aurorahunt.xyz/extern/api/account_proc1.php?mode=login&id=neoguru0&pw=wpqkfskf12&gamecode=spolive
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// 토큰 생성 ( 형식은 연동 규격서 참고 )
		$token4crypt = "mode=login|id=" . $login_id . "|pw=" . $login_pw . "|partner=" . $info_game['m_partner'] . "|date=" . DATE("ymdHis",time()) . "|ip=210.59.144.44";
		// print($token4crypt . "\n");

		// key는 url인코딩 되어서 보내야 함
		// api 연동 위해 먼저 crypt로 암호화 처리 진행
		$url = "https://".$HOST.".aurorahunt.xyz/extern/api/crypt.php?proc1=" . urlencode($info_game['m_key_value']) . "&proc2=" . urlencode($token4crypt);
		$data = curl_return_json($url);
		$result = $data["return"];

		//  위 암호화 한 값을 다시 복호화 하는 예제
		//  복호화 할때는 이미 url 인코딩되어 있는 값들을 기반으로 다루기 때문에 url 디코딩을 해서 AES 복호화 처리를 해야 한다.
		// crypt는 암호화를 위해 키를 항상 proc1으로 보내지만, api는 연동을 위해 키를 항상 proc2로 보내야만 한다. ( 결국은 보안상의 이유로 proc1와 proc2를 교차시켜 호출하도록 유도 )
		$url = "https://".$HOST.".aurorahunt.xyz/extern/api/account.php?proc1=" . $result . "&proc2=" . urlencode($info_game['m_key_value']);
		$return_data = curl_return_json($url);		

		if ( $return_data["result"] == 1 ) {
			print(json_encode(array("result"=>"Success")));
		}
		else {
			print(json_encode(array("result"=>"Failed")));
		}

	}


	// 로그아웃 처리 체크
	if ( $mode == "logout" ) {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 예) https://test-18.aurorahunt.xyz/extern/api/account_proc1.php?mode=logout&id=neoguru0&gamecode=spolive
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$token4crypt = "mode=logout|id=" . $login_id . "|partner=" . $info_game['m_partner'] . "|date=" . DATE("ymdHis",time()) . "|ip=210.59.144.44";

		//$url = "https://dev.aurorahunt.xyz/extern/api/crypt.php?proc1=u15ju%2B49A%2Bj9GRRmPhjKZDaJN5awLEqe03q72qsxHoU%3D&proc2=" . urlencode($token4crypt);
		$url = "https://".$HOST.".aurorahunt.xyz/extern/api/crypt.php?proc1=" . urlencode($info_game['m_key_value']) . "&proc2=" . urlencode($token4crypt);
		$data = curl_return_json($url);
		$result = $data["return"];

		//$url = "https://dev.aurorahunt.xyz/extern/api/account.php?proc1=" . $result . "&proc2=u15ju%2B49A%2Bj9GRRmPhjKZDaJN5awLEqe03q72qsxHoU%3D";
		$url = "https://".$HOST.".aurorahunt.xyz/extern/api/account.php?proc1=" . $result . "&proc2=" . urlencode($info_game['m_key_value']);
		$return_data = curl_return_json($url);		

		if ( $return_data["result"] == 1 ) {
			print(json_encode(array("result"=>"Success")));
		}
		else {
			print(json_encode(array("result"=>"Failed")));
		}

	}


	// 아이디체크 처리 체크
	if ( $mode == "checkid" ) {
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 예) https://test-18.aurorahunt.xyz/extern/api/account_proc1.php?mode=checkid&id=neoguru0&gamecode=spolive
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$token4crypt = "mode=checkid|id=" . $login_id . "|partner=" . $info_game['m_partner'] . "|date=" . DATE("ymdHis",time()) . "|ip=210.59.144.44";

		//$url = "https://dev.aurorahunt.xyz/extern/api/crypt.php?proc1=u15ju%2B49A%2Bj9GRRmPhjKZDaJN5awLEqe03q72qsxHoU%3D&proc2=" . urlencode($token4crypt);
		$url = "https://".$HOST.".aurorahunt.xyz/extern/api/crypt.php?proc1=" . urlencode($info_game['m_key_value']) . "&proc2=" . urlencode($token4crypt);
		$data = curl_return_json($url);
		$result = $data["return"];

		//$url = "https://dev.aurorahunt.xyz/extern/api/account.php?proc1=" . $result . "&proc2=u15ju%2B49A%2Bj9GRRmPhjKZDaJN5awLEqe03q72qsxHoU%3D";
		$url = "https://".$HOST.".aurorahunt.xyz/extern/api/account.php?proc1=" . $result . "&proc2=" . urlencode($info_game['m_key_value']);
		$return_data = curl_return_json($url);		

		if ( $return_data["result"] == 1 ) {
			print(json_encode(array("result"=>"Success")));
		}
		else {
			print(json_encode(array("result"=>"Failed")));
		}
	}
?>