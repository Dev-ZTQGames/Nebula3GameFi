<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$pw = $_POST['pw'];	
$forbidden=" !@#$%^&*()_+-={}[]\\|\'\";:/?.>,<`~";


if ( strlen($pw) < 8 || strlen($pw) > 16 || strlen($pw) != strcspn($pw,$forbidden)) {
	echo "bad_pw";
}
?>