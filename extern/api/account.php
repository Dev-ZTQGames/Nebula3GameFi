<?php
	header("Content-Type: application/json");

//	$status = "debug";

	include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

	$proc1		= escape_string(trim($_REQUEST['proc1']));		// get proc string	( always )
	$proc2		= escape_string(trim($_REQUEST['proc2']));

	
	if ( $status == "debug" )	{
		echo "status : " . $status . "<br><br>";
		print_r($_REQUEST);
		echo "<br><br>";
	}

	// 키 생성 모드
	if ( $proc1 == "mkKey" ) {
		if ( $proc2 ) {
			$array = array("result" => "1", "msg" => urlencode(AES128Encrypt($proc2, $proc2)));	// 성공, proc2를 seed로 여겨 key 값을 msg로 출력한다.
		}
		else {
			$array = array("result" => "0", "msg" => "-10");	// 실패, invalid proc2, 유효한 proc2값이 아닙니다. 
		}
		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "API");
		exit;
	}


	// 게임 등록 정보 로딩
	$info_game = array();	
	$query_game = "SELECT * FROM Games WHERE m_key_value = '" . $proc2 . "'";
	$que_game = mysqli_query($connect, $query_game);
	$num_game = mysqli_num_rows($que_game);
	$row_game = mysqli_fetch_array($que_game);
	$info_game = $row_game;

	// 게임 등록 정보가 DB에 있으면 key 값 연산과 파트너 식별, 없으면 오류
	if ( $num_game > 0 )	{		
		if ( $info_game["m_seed"] == AES128Decrypt($info_game["m_seed"], $proc2) ) {		// 성공, key 값 설정, 파트너 코드 설정
			$key_value = $info_game["m_key_value"];						
			$partner = $info_game["m_partner"];
		}
	}
	else	{
		$array = array("result" => "0", "msg" => "-10");	// 실패, invalid proc2, 유효한 proc2값이 아닙니다. 
		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "API");
		exit;
	}

	// 전송된값 디코딩 및 시리얼라이징으로 변수값들 설정
	$tokenString = AES128Decrypt($key_value, $proc1);	
	
	// 전송된 값 디코딩 
	$tokenString = explode("|", $tokenString);
	//print_r($tokenString);
	foreach ( $tokenString as $key => $value )	{					// 시리얼라이징
		$first =  strtok($value,"=");			
		$second = substr($value, strlen($first) + 1, strlen($value) -1 );
		$serial_array[$first] = $second;
	}

	// 전달된 값중에 파트너 코드가 DB의 내용과 일치하지 않으면 에러를 표시한다.
	if ( $serial_array["partner"] != $partner )	{				 
		$array = array("result" => "0", "msg" => "-20");	// invalid partner code
		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "API");
		exit;
	}

	// 시간 전달값이 12자리가 아니거나 숫자가 아니면 에러 처리
	if ( strlen($serial_array["date"]) != 12 || !is_numeric($serial_array["date"]) )	{
		$array = array("result" => "0", "msg" => "-30");	// invalid date type
		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "API");
		exit;
	}


	// 여기서부터 mode 처리 ( 리팩토링 요망 )
	// login mode
	if ( $serial_array["mode"] == "login" )	{

		$info = array();		
		$query = "SELECT login_pw , PASSWORD('" . $serial_array["pw"] . "') as check_pw, usn, id, grade FROM Accounts WHERE login_id='" . $serial_array["id"] . "'";
		$que = mysqli_query($connect, $query);
		$row = mysqli_fetch_array($que);
		$info = $row;

		if ($info['login_pw'] == '' || $info['login_pw'] != $info['check_pw']) {
			$array = array("result" => "0", "msg" => "-40");	// login failure : no match id or pw
			$json_result = json_encode($array);
			print($json_result);

			output_log($json_result, "API");
			exit;
		} else {
			
			// Ban Check
			if ( $info['grade'] < 50 ) {
				$array = array("result" => "0", "msg" => "-80");	//user in banlist, login failure ( platform )
				$json_result = json_encode($array);
				print($json_result);

				output_log($json_result, "API");
				exit;
			}
			else {
				$info_ban = array();
				$query = "SELECT m_ban, m_enddate, NOW() AS time FROM AccountsBan WHERE m_login_id='" . $serial_array["id"] . "' and m_game_code='" . $serial_array['gamecode'] . "'";
				$que = mysqli_query($connect, $query);
				$row_ban = mysqli_fetch_array($que);
				$info_ban = $row_ban;
				if( $info_ban > 0 ){
					$enddate = strtotime($info_ban['m_enddate']);
					$now = strtotime($info_ban['time']);
					if($info_ban['m_ban']=="1" && $enddate >= $now){
						$array = array("result" => "0", "msg" => "-70");	//user in banlist, login failure ( gamecode )
						$json_result = json_encode($array);
						print($json_result);

						output_log($json_result, "API");
						exit;
					}
				}
			}			
			
			// usn 생성
			if ( $info['usn'] == 0 )	{

				$info_usn = array();
				$query = "SELECT max(usn) as usn_m FROM Accounts";		// 이 부분 아래 쿼리와 합쳐야 함 ( 한줄 쿼리로 작성 요망 - 그 사이에 usn 추가로 인해 겹치는 usn 발생 때문 )
				$que = mysqli_query($connect, $query);
				$row_usn = mysqli_fetch_array($que);
				$info_usn = $row_usn;
				
				$real_usn = $info_usn['usn_m'];
				$real_usn++;		// 2억 이상에서 1증가 처리 

				$query = "UPDATE Accounts SET usn = '" . $real_usn . "' WHERE login_id = '" . $serial_array["id"] . "'";
				$que = mysqli_query($connect, $query);

			}	
			else {
				$real_usn = $info['usn'];
			}
			
			$lang_code = $info_game["m_lang_code"];
			$game_code = $info_game["m_game_code"];

			$statistics_year = intval(date('Y'));
			$statistics_month = intval(date('m'));
			$statistics_day = intval(date('d'));
			$statistics_hour = intval(date('H'));

			$statistics = mysqli_query($connect, "INSERT INTO LoginLog (m_id, m_login_id, m_usn, m_lang_code, m_game_code, m_service_code, m_type, m_year, m_month, m_day, m_hour, m_date) VALUES (".$info['id'].",'".$serial_array['id']."', ".$real_usn.",'".strtoupper($lang_code)."','".$game_code."', '',2," . $statistics_year . "," . $statistics_month . "," . $statistics_day . "," . $statistics_hour . ",now() )");

			$array = array("result" => "1", "usn" => "$real_usn", "msg" => "1010");	// login success

			$json_result = json_encode($array);
			print($json_result);

			output_log($json_result, "API");
			exit;
		}

		// 로그인 시간을 date 값으로 보내왔으나 이를 DB에 활용하지 못하고 있음 -> 차후 확인 바람
	}


	// logout mode
	if ( $serial_array["mode"] == "logout" )	{				
		$array = array("result" => "1", "msg" => "1020");	// logout success
		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "API");
		exit;

		// 로그아웃시 회원 정보 사항에서 로그아웃 상황 또는 시간 업데이트 여부 향후 수정 요망 => 로그아웃 로그 작성해야 함
	}


	// checkid mode
	if ( $serial_array["mode"] == "checkid" )	{				

		$info = array();		
		$query = "SELECT email, usn, lang_code, name FROM Accounts WHERE login_id='" . $serial_array["id"] . "'";
		$que = mysqli_query($connect, $query);
		$count = mysqli_num_rows($que);
		$row = mysqli_fetch_array($que);

		$info = $row;

		if ( $count > 0 )	{
			// id is existed
			$temp_usn		= $info["usn"];
			$temp_login_id	= $serial_array["id"];
			$temp_name		= $info["name"];
			$temp_email		= $info["email"];
			$temp_lang_code	= $info["lang_code"];

			// 국가 코드 땡겨와야 함
			// 가입시 국가 코드 입력 부분 별도 확인 요망
			$array = array("result" => "1", "msg" => "1030", "usn" => "$temp_usn", "login_id" => "$temp_login_id", "name" => "$temp_name", "email" => "$temp_email", "lang_code" => "$temp_lang_code");		
			$json_result = json_encode($array);
			$json_result = urldecode($json_result);

			setcookie('login_id', $temp_login_id, time() + 60 * 60 * 24, "/", ".aurorahunt.xyz");
			print($json_result);
			output_log($json_result, "API");
			exit;
		} else {			
			$array = array("result" => "0", "msg" => "-50");		// not existed id
		}

		$json_result = json_encode($array);
		print($json_result);

		output_log($json_result, "API");
		exit;
	}

	// 부존재 mode 정의 오류
	$array = array("result" => "0", "msg" => "-60");	// invalid mode
	$json_result = json_encode($array);
	print($json_result);

	output_log($json_result, "API");
	exit;
?>