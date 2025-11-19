<?php
//https://www.it-swarm.dev/ko/php/apple-invalidclient%EB%A1%9C-%EB%A1%9C%EA%B7%B8%EC%9D%B8/813705952/
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

//print_r($_REQUEST);

/*
Array ( [state] => 2qtipdo430hwnkm7zv8a9fjl6c5gy1 [code] => c32242a32daf348a2b41bd51ed6bd8459.0.nrsvq.JQTEKBIGxTQlSy2bKqjmUw [id_token] => eyJraWQiOiI4NkQ4OEtmIiwiYWxnIjoiUlMyNTYifQ.eyJpc3MiOiJodHRwczovL2FwcGxlaWQuYXBwbGUuY29tIiwiYXVkIjoicGV0cG9pbnRrci5sYXR0ZXNvZnQuY29tIiwiZXhwIjoxNTkyMjQyNDUwLCJpYXQiOjE1OTIyNDE4NTAsInN1YiI6IjAwMTI1MC44MjlkYzdlZWQxYWU0YjlmODIwYjI3ZjEwNjdmNDM0Yy4xNzI0IiwiY19oYXNoIjoibjdPamx1UmNvcWptZXc4YV9aRnBzZyIsImVtYWlsIjoic3kyb24yNEBuYXZlci5jb20iLCJlbWFpbF92ZXJpZmllZCI6InRydWUiLCJhdXRoX3RpbWUiOjE1OTIyNDE4NTAsIm5vbmNlX3N1cHBvcnRlZCI6dHJ1ZX0.R7G5i23XssS8QSLYDtZFnwmUPVMQWptBY_sSYuQ0UOpAhwe_FhSAjSQJK9HxqHXwzojxarZRGb8JZ3tjYRqjs2xPUBJ37DDB20spWrBTRMh4sun7XuDUD8NAtcaT-Fq57sHfJwdQ5Gd0ebiVGRRnjoiDimWLXwuWZ9MH5T2XN6OpnhPpJVOZqmpenawT08tmJHKsZo8Y36C2WpvSsY07BCmzF2SkSuvJqysRQYVPwrYMyTnoKr5L3_SRLvn1-PRCNTq8nwsE0Hec6kOkMXURoeWFwMXzYtFG7rQTW5l0bqeVYRvCFZmA-Y6mBQoEPL6-Pit6z4Uzt9chhbw1d-StEQ [user] => {"email":"sy2on24@naver.com"} )
*/

if ($_SESSION['apple_state'] != $_REQUEST['state']) {
	// 오류 발생 잘못된 경로로 접근
//	die("ERROR!");
	header("Location:/sub/login-intro.php");
}

$userEmail_temp = json_decode($_REQUEST['user'],true);

// 직접 값을 전달해 주지 않으면 JTW를 파싱해서 이메일을 검색한다.
if ( $userEmail_temp['email'] == "" ) {
	$id_tokens = explode("." , $_REQUEST['id_token']);
	$payload = base64_decode($id_tokens[1]);
	$userEmail_temp = json_decode($payload,true);
	$userEmail_temp = $userEmail_temp['email'];
}
else {
	$userEmail_temp = $userEmail_temp['email'];
}

//======================================================
// 파라미터 정리
// userEmail : 유저 가입에 필요한 소셜 로그인 유니크 값으로 이메일에 해당함
// prefix_id : 소셜별 접두어 ( nv, kk, gg, fb, ap )

$userEmail = $userEmail_temp;
$prefix_id = "ap";

// include 처리
include "join_social_check.php";
?>