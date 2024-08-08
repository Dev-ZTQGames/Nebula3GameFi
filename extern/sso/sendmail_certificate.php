<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$email		 = escape_string(trim($_REQUEST['email']));
$lang_code	 = escape_string(trim($_REQUEST['lang_code']));
$num	 = escape_string(trim($_REQUEST['num']));

//$num = sprintf('%06d',mt_rand(000000,999999));

$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/mail/mail_01.htm', "r"); 

while( !feof($fp) ) { 
	$content .= fgets($fp); 
} 

$content = str_replace("{{title}}", $lang['Info_Verification_Code'], $content);
$content = str_replace("{{Mail01_Text1}}", $lang['Mail01_Text1'], $content);
$content = str_replace("{{Mail01_Text2}}", $lang['Mail01_Text2'], $content);
$content = str_replace("{{Auth_Code_Num}}", $lang['Auth_Code_Num'], $content);
$content = str_replace("{{Thank_You}}", $lang['Thank_You'], $content);
$content = str_replace("{{Mail01_Text3}}", $lang['Mail01_Text3'], $content);
$content = str_replace("{{name}}", $name, $content);
$content = str_replace("{{number}}", $num, $content);
$content = str_replace("{{HOST}}", $HOST, $content);
$content = str_replace("{{url}}", 'https://discord.gg/QpVbuEFu6D', $content);

$nameFrom  = $lang['Nebula3_GameFi'];
$mailFrom = "noreply@nebula3gamefi.com";
$mailTo = $email;

$subject = $lang['Info_Verification_Code'];

$result = curl_post('https://freepassdev.perugiacorp.com/proc_nebula3.php', array('email'=>$email, 'subject'=>$subject, 'content'=>$content, 'from'=>$nameFrom, 'to'=>$mailFrom, 'lang_code'=>'ko', 'type'=>'1', 'platform'=>'nebula3'));

if($result == 'success'){
	echo ($result.$num);
} 

fclose($fp);

?>