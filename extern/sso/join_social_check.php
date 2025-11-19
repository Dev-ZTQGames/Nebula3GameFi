<?php

// 이메일이 빈값이면 로그인 페이지로 강제 이동
if( $_REQUEST['userEmail'] ){
 	$userEmail = $_REQUEST['userEmail'];
	$prefix_id = $_REQUEST['prefix_id'];
	echo "<script>console.log('" . $userEmail . "'); </script>";
}

if ( $userEmail == "" ) {

	echo "<script>alert('" . $lang['Resigter_Failed_noEmail'] . "'); location.href='/sub/login-intro.php';</script>";
	exit;

}
function emailToUniqueId($email, $length = 20) {
    $key = 'nebula3gamefi.com'; 
    $hashed = hash_hmac('sha256', $email, $key);
    return substr($hashed, 0, $length);
}
	$login_id =  $prefix_id . emailToUniqueId($userEmail);		// 아이디의 기본 베이스를 중복되지 않는 이메일 기반으로 간다.

	$email = $userEmail;


// 같은 아이디로 이미 가입되었는지 여부 처리 
$que = mysqli_query($connect, "SELECT id FROM Accounts WHERE login_id='".$login_id."'");
$row = mysqli_fetch_assoc($que);

$already = $row['id'];

mysqli_free_result($que);


if ($already == 0) {	   // 기존 아이디가 없으면 먼저 가입 처리

	// 이메일 중복 검사
	$already = 0;
	$que = mysqli_query($connect, "SELECT count(id) as counter FROM Accounts WHERE email='" . $email . "'");
	$row = mysqli_fetch_assoc($que);
	$already = $row['counter'];

	if ($already > 0) {
		echo "<script>alert('" . $lang['Resigter_Failed_byEmail'] . "'); location.href='/sub/login-intro.php';</script>";
		exit;
	}

	// usn 발급
	$info_usn = array();
	$query_usn = "SELECT max(usn) as usn_m FROM Accounts";
	$que_usn = mysqli_query($connect, $query_usn);
	$row_usn = mysqli_fetch_array($que_usn);
	$info_usn = $row_usn;
	
	$real_usn = $info_usn['usn_m'];
	$real_usn++;		// 맥스값에서 1증가 처리 
	
	$result = mysqli_query($connect, "INSERT INTO Accounts (login_id,login_pw,email,createdAt,usn,lang_code,ip) VALUES ('".$login_id."',PASSWORD('".$login_pw."'),'".$email."', now(),'" . $real_usn . "','".strtoupper($lang_code)."', '".$ip."')");
	if ($result > 0) {
		// 가입 성공
		mysqli_query($connect, "INSERT INTO AccountsLog (m_login_id,m_content,m_date,m_ip) VALUES ('".$login_id."','Register Success!','".date("Y-m-d H:i:s")."','".$ip."')");
		$SocialRegistrationOkay = "true";
	} else {
		// 가입 실패
		//$msg = $lang['Please_Tryagain_Later'];
	}

}

//ICP OnChain-Data N3QE token minting  /includes/config.php
mintN3QEToken("N3_GET_EXP", $login_id);

// 로그인 처리
$info = array();
$que = mysqli_query($connect, "SELECT login_pw, PASSWORD('".$login_pw."') as check_pw, usn, id, email, birth, sex, name, status, grade FROM Accounts WHERE login_id='".$login_id."' COLLATE utf8_bin");		// 향후 ID 검색은 대소문자 구분 위해 COLLATE utf8_bin 추가 요망
$row = mysqli_fetch_array($que);
$info = $row;

//if ( $configuration['mode'] == "test / debug" ) {
//	if ( $info['grade'] <= 50 )	{
//		die("Administrator only can login on this Nebula3GameFi test server.");	
//	}
//}

// 로그인 성공
$_SESSION['sess_id'] = $info['id'];
$_SESSION['sess_login_id'] = $login_id;
$_SESSION['sess_email'] = $info['email'];
$_SESSION['sess_usn'] = $info['usn'];
$_SESSION['sess_sex'] = $info['sex'];
$_SESSION['sess_birth'] = $info['birth'];
$_SESSION['sess_phone'] = $info['phone'];
$_SESSION['sess_name'] = $info['name'];
$_SESSION['sess_status'] = $info['status'];
$_SESSION['sess_grade'] = $info['grade'];

$time = time();
setcookie('login_id', $login_id, [
	'expires' => time() + 60 * 60 * 24,	// Set an expiration time if needed
	'path' => '/',								// Adjust the path if needed
	'domain' => '.nebula3gamefi.com',				// Change to your actual domain
	'secure' => true,							// Send the cookie only over secure (HTTPS) connections
	'samesite' => 'None',						// Set SameSite attribute to None
]);		

setcookie('name', $info['name'], time() + 60 * 60 * 24, "/", ".nebula3gamefi.com");
setcookie('time', $time, time() + 60 * 60 * 24 * 365, "/", ".nebula3gamefi.com");			
setcookie('hash', AES128Encrypt("Aurorahunt", $login_id."|".$time."|CSVersion:221019"), time() + 60 * 60 * 24 * 365, "/", ".nebula3gamefi.com");

$statistics_year = intval(date('Y'));
$statistics_month = intval(date('m'));
$statistics_day = intval(date('d'));
$statistics_hour = intval(date('H'));

$statistics = mysqli_query($connect, "INSERT INTO LoginLog (m_id, m_login_id, m_usn, m_lang_code, m_game_code, m_service_code, m_type, m_year, m_month, m_day, m_hour, m_date, m_success, m_ip) VALUES (".$info['id'].",'".$login_id."', ".$info['usn'].",'".strtoupper($lang_code)."','".$game_code."', '".$service_code."','3'," . $statistics_year . "," . $statistics_month . "," . $statistics_day . "," . $statistics_hour . ",now(), '1','".$ip."' )");
/*
if ( $SocialRegistrationOkay == "true" ) {

	header("Location:join_social.php?id=" . $info['id'] . "&login_id=" . $login_id);
	exit;
}
*/
header("Location:https://" . $HOST . ".nebula3gamefi.com");
