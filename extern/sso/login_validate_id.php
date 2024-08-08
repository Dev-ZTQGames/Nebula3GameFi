<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$Ajax_return="none";
if ($_POST['id']){
 if(strlen($_POST['id'])<8){
  $Ajax_return="short";
 }else if(strlen($_POST['id'])>16){
  $Ajax_return="long";
 }else{

	preg_match('/[0-9a-zA-Z]+/', $_POST['id'], $Result);	// 숫자와 영문로만 구성 되어 있는지 확인

	if( $Result[0] == $_POST['id'] )	 {

	  $check_result = 0;
  	  preg_match('/[0-9]+/', $_POST['id'], $Result);	// 숫자로만 구성 되어 있는지 확인
	  if ( $Result[0] != $_POST['id'] ) $check_result++;
	  preg_match('/[a-zA-Z]+/', $_POST['id'], $Result);	// 숫자와 영문로만 구성 되어 있는지 확인
	  if ( $Result[0] != $_POST['id'] ) $check_result++;

	  if  ( $check_result == 2 )		{
		$Ajax_return = "true";
	  }
	  else
		$Ajax_return = "bad_character2"; // 반드시 영어나 숫자를 혼용해서 사용해야 함. 
	}
	else
		$Ajax_return = "bad_character"; // 영숫자 이외의 문자 포함

 }
}

echo $Ajax_return;

?>