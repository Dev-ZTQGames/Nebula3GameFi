<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$connect = mysqli_connect($DB_INFO['HOST'], $DB_INFO['ID'], $DB_INFO['PASS'], $DB_INFO['NAME']);

$Ajax_return="none";

if ($_POST['email']){
	$Result = preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $_POST['email']);

	if( $Result == true )	 {

	  $accounts_query ="SELECT login_id FROM Accounts WHERE email='".$_POST['email']."'";

	  $que = mysqli_query($connect, $accounts_query);
	  $row = mysqli_fetch_array($que);

	  if($row['login_id']==''){
	   $Ajax_return="true";
	  }else{
	   $Ajax_return="false";
	  }

	} else {
	   $Ajax_return="bad_character";
	}
}
echo $Ajax_return;
?>