<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

// Array ( [state] => OK [code] => 4/vQH_QBI7BYl0s210_aoDd4SkRTgAA7yZv__pcF2yS8lkemtizgcPWVlab-JVz5NXQimJTIgtzd21frAYzAoOF2I [scope] => https://mail.google.com/ )

// 주요 값들.
$client_id = "285816939237-bma9d48nomala55il6qs982qr2pbhj20.apps.googleusercontent.com";
$client_secret = "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";
$redirect_uri = "https://" . $HOST . ".nebula3gamefi.com/extern/sso/login_google_web.php";			
$scope = "https://www.googleapis.com/auth/userinfo.email";
//$scope = "openid email profile";
// $scope = urlencode( $scope );

// $client_id, $redirect_uri & $client_secret come from the settings
// $code is the code passed to the redirect url
function _GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {	
	$url = 'https://oauth2.googleapis.com/token';

	$post_fields = [
	    'code' => $code,	
	    'client_id' => $client_id,
	    'client_secret' => $client_secret,
	    'redirect_uri' => $redirect_uri,
		'grant_type'=> 'authorization_code',
	];
	$ch = curl_init();		
	curl_setopt($ch, CURLOPT_URL, $url);		
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
	curl_setopt($ch, CURLOPT_POST, 1);		
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));	
	$data = json_decode(curl_exec($ch), true);
	$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
	if($http_code != 200) 
		throw new Exception('Error : Failed to receieve access token');
	
	return $data;
}

// $access_token is the access token you got earlier
function GetUserProfileInfo($access_token) {	
	$url = 'https://www.googleapis.com/oauth2/v2/userinfo?fields=name,email,gender,id,picture,verified_email';	
	
	$ch = curl_init();		
	curl_setopt($ch, CURLOPT_URL, $url);		
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
	$data = json_decode(curl_exec($ch), true);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
	if($http_code != 200) 
		throw new Exception('Error : Failed to get user information');
		
	return $data;
}


if(isset($_GET['code'])) {
	try {
		// Get the access token 
		$data = _GetAccessToken($client_id, $redirect_uri, $client_secret, $_GET['code']);

		// Access Token
		$access_token = $data['access_token'];
		
		// Get user information
		$user_info = GetUserProfileInfo($access_token);
	}
	catch(Exception $e) {
		echo $e->getMessage();
		exit();
	}
}


//print_r($user_info);
//$data = json_decode($user_info);
//echo "email : " . $user_info['email'];
//exit;

//======================================================
// 파라미터 정리
// userEmail : 유저 가입에 필요한 소셜 로그인 유니크 값으로 이메일에 해당함
// prefix_id : 소셜별 접두어 ( nv, kk, gg, fb, ap )

$userEmail = $user_info['email'];
$prefix_id = "gg";

// include 처리
include "join_social_check.php";
