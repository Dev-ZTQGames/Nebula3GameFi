<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$connect = mysqli_connect($DB_INFO['HOST'], $DB_INFO['ID'], $DB_INFO['PASS'], $DB_INFO['NAME']);

$Ajax_return="none";

//print_r(mysqli_connect_error());
if ($_POST['name']){
 if(mb_strlen($_POST['name'],'utf-8')>20){
  $Ajax_return="long";
 }else{
	$Result = preg_match("/[`~!@#$%^&*|\\\'\";:\/?^=^+_()<>]/", $_POST['name']);

	if( !$Result )	 {
	
	  $accounts_query ="SELECT login_id FROM Accounts WHERE name='".$_POST['name']."'";

	  $que = mysqli_query($connect, $accounts_query);
	  $row = mysqli_fetch_array($que);

	  if($row['login_id'] == ''){
	   $Ajax_return="true";
	  }else{
	   $Ajax_return="false";
	  }
	}
	else
		$Ajax_return = "bad_character"; 
 }
}
echo $Ajax_return;
?>