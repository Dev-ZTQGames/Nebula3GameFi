<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

// https://nicgoon.tistory.com/208

// 주요 값들.
$client_id = "285816939237-bma9d48nomala55il6qs982qr2pbhj20.apps.googleusercontent.com";
$client_secret = "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";
$redirection_url = "https://" . $HOST . ".nebula3gamefi.com/extern/sso/login_google_web.php";		

$scope = 'https://www.googleapis.com/auth/userinfo.email';

// 인증 코드 주소 생성.
$reqAddr = "https://accounts.google.com/o/oauth2/v2/auth"
. "?client_id=" . $client_id
. "&redirect_uri=" . urlencode( $redirection_url )
. "&scope=" . urlencode( $scope )
. "&access_type=online"
. "&response_type=code"
. "&prompt=select_account"
;

// 지정한 주소로 리디렉션 시키기.
header( 'Location: ' . $reqAddr );
