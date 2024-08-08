<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$id = escape_string(trim($_REQUEST['id']));
$email = escape_string(trim($_REQUEST['email']));

$que = mysqli_query($connect, "SELECT * FROM Accounts WHERE login_id='".$id."'");

$row = mysqli_fetch_array($que);

$user_mail = $row['email'];

$name = $row['name'];

$pass_pool_a	= array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O', 'P', 'X', 'Y', 'Z');
$pass_pool_1	= array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

$new_pass = '';

for($i = 0; $i < 8; $i++){
	$rand_num	= array_rand($pass_pool_a); 
	$new_pass	.= $pass_pool_a[$rand_num];
}

for($i = 0; $i < 2; $i++){
	$rand_num	= array_rand($pass_pool_1); 
	$new_pass	.= $pass_pool_1[$rand_num];
}

for($i = 0; $i < 2; $i++){
	$rand_num	= array_rand($pass_pool_a); 
	$new_pass	.= $pass_pool_a[$rand_num];
}

mysqli_query($connect, "UPDATE Accounts SET login_pw=PASSWORD('".$new_pass."') WHERE login_id='".$id."'");

$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/mail/mail_05.htm', "r"); 
while( !feof($fp) ) { 
	$content .= fgets($fp); 
} 
fclose($fp);

$content = str_replace("{{title}}", $lang['Guide_Reset_PW'], $content);
$content = str_replace("{{Mail02_Text1}}", $lang['Mail02_Text1'], $content);
$content = str_replace("{{Mail05_Text1}}", $lang['Mail05_Text1'], $content);
$content = str_replace("{{Temporary_PWD}}", $lang['Temporary_PWD'], $content);
$content = str_replace("{{Mail05_Text2}}", $lang['Mail05_Text2'], $content);
$content = str_replace("{{Thank_You}}", $lang['Thank_You'], $content);
$content = str_replace("{{Mail01_Text3}}", $lang['Mail01_Text3'], $content);
$content = str_replace("{{name}}", $name, $content);
$content = str_replace("{{new_pw}}", $new_pass, $content);
$content = str_replace("{{HOST}}", $HOST, $content);
$content = str_replace("{{url}}", 'https://discord.gg/QpVbuEFu6D', $content);

$nameFrom  = $lang['Nebula3_GameFi'];
$mailFrom = "noreply@nebula3gamefi.com";
$mailTo = $email;

$subject = $lang['Guide_Reset_PW'];


$result = curl_post('https://freepassdev.perugiacorp.com/proc_nebula3.php', ['email' => $email, 'subject' => $subject, 'content' => $content, 'from' => $nameFrom, 'to' => $mailFrom, 'lang_code' => 'ko', 'type' => '3', 'platform' => 'nebula3']);
?>