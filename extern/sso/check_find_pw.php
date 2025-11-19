<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$id = escape_string(trim($_REQUEST['id']));
$email = escape_string(trim($_REQUEST['email']));

$que = mysqli_query($connect, "SELECT * FROM Accounts WHERE login_id='".$id."'");

$row = mysqli_fetch_array($que);

$user_mail = $row['email'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("../../vendor/PHPMailer/src/PHPMailer.php");   
require_once("../../vendor/PHPMailer/src/SMTP.php");
require_once("../../vendor/PHPMailer/src/Exception.php");

if($user_mail == ''){
	$msg = 'fail';
	echo $msg; 
} else if($user_mail != $email) {
	$msg = 'incorrect';
	echo $msg; 
} else {
	$msg = 'ok';
	echo $msg;
}
?>
