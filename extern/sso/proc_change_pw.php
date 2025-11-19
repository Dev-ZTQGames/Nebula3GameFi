<?php 
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$new_pw  = escape_string(trim($_REQUEST['new_pw']));

$info = array();
$check_query = 'SELECT *,PASSWORD("'.$new_pw.'") as check_pw FROM Accounts WHERE login_id="'.$_COOKIE['login_id'].'"';
$que = mysqli_query($connect, $check_query);
$row = mysqli_fetch_array($que);

$info = $row; 

if($info['check_pw'] == $info['login_pw']){
	echo 'already';
} else {
	$change_query = 'UPDATE Accounts SET login_pw = PASSWORD("'.$new_pw.'") WHERE login_id = "'.$_COOKIE['login_id'].'"';

	 if(mysqli_query($connect, $change_query)){
		echo 'success';
	 } else {
		echo 'fail';
	 }
}
?>