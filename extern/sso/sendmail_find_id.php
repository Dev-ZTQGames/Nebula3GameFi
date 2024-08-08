<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$email = escape_string(trim($_REQUEST['email']));

$que = mysqli_query($connect, "SELECT * FROM Accounts WHERE email='".$email."'");

$row = mysqli_fetch_array($que);

$id = $row['login_id'];	
$name = $row['name'];	

$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/mail/mail_04.htm', "r"); 
while( !feof($fp) ) { 
	$content .= fgets($fp); 
} 
fclose($fp);

$content = str_replace("{{title}}", $lang['Guide_Find_ID'], $content);
$content = str_replace("{{Mail02_Text1}}", $lang['Mail02_Text1'], $content);
$content = str_replace("{{Mail04_Text1}}", $lang['Mail04_Text1'], $content);
$content = str_replace("{{Target_ID}}", $lang['Target_ID'], $content);
$content = str_replace("{{Thank_You}}", $lang['Thank_You'], $content);
$content = str_replace("{{Mail01_Text3}}", $lang['Mail01_Text3'], $content);
$content = str_replace("{{name}}", $name, $content);
$content = str_replace("{{id}}", $id, $content);
$content = str_replace("{{email}}", $email, $content);
$content = str_replace("{{HOST}}", $HOST, $content);
$content = str_replace("{{url}}", 'https://discord.gg/QpVbuEFu6D', $content);

$nameFrom  = $lang['Nebula3_GameFi'];
$mailFrom = "noreply@nebula3gamefi.com";
$mailTo = $email;

$subject = $lang['Guide_Find_ID'];

$result = curl_post('https://freepassdev.perugiacorp.com/proc_nebula3.php', array('email'=>$email, 'subject'=>$subject, 'content'=>$content, 'from'=>$nameFrom, 'to'=>$mailFrom, 'lang_code'=>'ko', 'type'=>'2', 'platform'=>'nebula3'));


?>