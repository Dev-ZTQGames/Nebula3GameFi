<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$connect = mysqli_connect($DB_INFO['HOST'], $DB_INFO['ID'], $DB_INFO['PASS'], $DB_INFO['NAME']);

$Ajax_return="none";
if ($_POST['email']){
	$Result = preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $_POST['email']);

	if( $Result == true )	 {

		$que = mysqli_query($connect, "SELECT login_id FROM Accounts WHERE email='".$_POST['email']."'");

		$row = mysqli_fetch_array($que);

		if($row['login_id'] == ''){
			$Ajax_return = 'noId';
		} else {
			$Ajax_return="true";
		} 
	}
}

echo $Ajax_return;
?>