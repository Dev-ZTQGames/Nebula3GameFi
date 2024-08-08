<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$login_id			= escape_string(trim($_POST['login_id']));
$login_pw			= escape_string(trim($_POST['login_pw']));
$return_path		= escape_string(trim($_POST['return_path']));
$register			= escape_string(trim($_POST['register']));

if ( $return_path == "" )	{
	$return_path = $return_url;
}

if ( $login_id == "" )	{
	$login_id = $_COOKIE['login_id'];
}
	
$info = array();

$que = mysqli_query($connect, "SELECT *,PASSWORD('".$login_pw."') as check_pw FROM Accounts WHERE login_id='".$login_id."'");
$row = mysqli_fetch_array($que);

$info = $row;

$name = $info['name'];

$time = time();

if($name){

	$que_ban = mysqli_query($connect, "SELECT *,NOW() AS time FROM AccountsBan WHERE m_login_id='".$login_id."' AND m_game_code = 'Platform'");
	if($que_ban){
		$row_ban = mysqli_fetch_array($que_ban);
		$now = strtotime($row_ban['time']);
		$enddate = strtotime($row_ban['m_enddate']);
		$gap = $enddate - $now;
		$hours = floor($gap / 3600);
		$minutes = floor(($gap % 3600) / 60);
		$seconds = $gap % 60;
		if($row_ban['m_ban'] == 1 && $now < $enddate){
			include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';
			echo '
			      <script>
					swal({
						text: "'.$lang['Block_msg'].$hours.":".$minutes.":".$seconds.'",
						buttons: "'.$lang['Confirm'].'",
						closeOnClickOutside: false,
						closeOnEsc: false,
					}).then(function(isConfirm){
						if(isConfirm){
							location.href="login.php";
						}
					})
				  </script>';
			exit;
		} else if($row_ban['m_ban'] == 1 && $now >= $enddate) {
			$result_ban = mysqli_query($connect, "UPDATE AccountsBan SET m_ban = 0 WHERE m_login_id ='".$login_id."' AND m_game_code = 'Platform'");

			$result_banlog = mysqli_query($connect, "INSERT INTO AccountsBanLog (m_login_id,m_content,m_date) VALUES ('".$login_id."','GameCode : Platform, ban : 0, changeby : Time Up',NOW())");

			$result_query_grade = "UPDATE Accounts SET grade = '".$row_ban['m_grade_old']."' WHERE login_id = '".$login_id."'";
			$result_grade = mysqli_query($connect, $result_query_grade);

		}
	}

	//탈퇴 상태면 날짜 체크해서 탈퇴 신청 철회 또는 회원 정보 없음 처리
	if($info['status']=='1'){
		$today = strtotime(date('Y-m-d'));

		//탈퇴 요청일
		$target = strtotime(substr($info['day_ask_remove'],0,10));
		
		
		//유예기간
//		$code_query = mysqli_query($connect, "SELECT * FROM Code WHERE name ='AccountRemoveDay'");
//		$code_row = mysqli_fetch_array($code_query);
//		$code_info = $code_row;
//		$code_day = $code_info['code'];

		//탈퇴 요청일 + 유예기간 (탈퇴 신청 취소 가능 만료일)
		//$plus_day = $target.strtotime('+'.$code_day.' days');
		$plus_day = $target.strtotime('+15days');

		if($today > $plus_day){
			include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';
			echo '
			      <script>
					swal({
					  text: "'.$lang['Not_Exist_Member_Info'].'",
					  buttons: "'.$lang['Confirm'].'",
					}).then(function(isConfirm){
						if(isConfirm){
						location.href="login.php";
						}
					})
				  </script>';
		} else {
			include_once $_SERVER['DOCUMENT_ROOT'].'/includes/header.php';
			echo '
				  <script>
					swal({
					  text: "'.$lang['Want_Withdrawal_Request'].'",
					   buttons: {
							cancel : "'.$lang['Cancel'].'",
							confirm : {
								text : "'.$lang['Confirm'].'",
								value : "catch"
							},
						  },
						}).then(function(value){
							if(value == "catch"){
								$.ajax({
								   type:"POST",        
								   url:"cancel_withdraw.php",     
								   data : ({login_id:"'.$info["login_id"].'"}),
								   timeout : 5000,  
								   cache : false,        
								   success: function whenSuccess(args){
									switch(args.trim()){
										 case("success"):
											swal({
											  text: "'.$lang['Withdrawn'].'",
											   buttons: "'.$lang['Confirm'].'",
											}).then(function(isConfirm){
												if(isConfirm){
												location.href="login.php";
												}
											})
										 break;
										 case("fail"):
										   swal({
											  text: "'.$lang['Try_Again_Later'].'",
											   buttons: "'.$lang['Confirm'].'",
											})
										 break;
									  }
								   },
								   error: function whenError(e){
									//alert("code : " + e.status + "\r\nmessage : " + e.responseText);
								  }
								});
							} else {
								location.href="login.htm";  
							}
						})	
				  </script>';
		}
	} else {
		
		if($register == 'ok'){
		//회원가입 완료 메일 발송
		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/mail/mail_02.htm', "r"); 

		while( !feof($fp) ) { 
			$content .= fgets($fp); 
		} 

		fclose($fp);

		$content = str_replace("{{name}}", $info['name'], $content);
		$content = str_replace("{{email}}", $info['email'], $content);
		$content = str_replace("{{url}}", $return_url, $content);


		$nameFrom  = $lang['AuroraHunt'];
		$mailFrom = "noreply@nebula3gamefi.com";
		$mailTo = $info['email'];

		$subject = $lang['Registration_Complete'];

		$result = curl_post('https://freepassdev.perugiacorp.com/proc_aurorahunt.php', array('email'=>$mailTo, 'subject'=>$subject, 'content'=>$content, 'from'=>$nameFrom, 'to'=>$mailFrom, 'lang_code'=>'ko', 'type'=>'4', 'platform'=>'aurorahunt'));
		
		}
		
		$_SESSION['sess_login_id'] = $login_id;
		$_SESSION['sess_name'] = $info['name'];
		$_SESSION['sess_usn'] = $info['usn'];
		$_SESSION['sess_email'] = $info['email'];
		$_SESSION['sess_change_serial_no'] = $info['change_serial_no'];
		

		//계정정보 쿠키 저장
		//setcookie('id', $info['id'], time() + 60 * 60 * 24 * 365, "/", ".nebula3gamefi.com");
		
		setcookie('login_id', $login_id, [
		'expires' => time() + 60 * 60 * 24,	// Set an expiration time if needed
		'path' => '/',								// Adjust the path if needed
		'domain' => '.nebula3gamefi.com',				// Change to your actual domain
		'secure' => true,							// Send the cookie only over secure (HTTPS) connections
		'samesite' => 'None',						// Set SameSite attribute to None
		]);

		setcookie('name', $info['name'], time() + 60 * 60 * 24, "/", ".nebula3gamefi.com");
		setcookie('time', $time, time() + 60 * 60 * 24, "/", ".nebula3gamefi.com");	
		setcookie('hash', AES128Encrypt("Aurorahunt", $login_id."|".$time."|CSVersion:221019"), time() + 60 * 60 * 24, "/", ".nebula3gamefi.com");	

		$statistics_year = intval(date('Y'));
		$statistics_month = intval(date('m'));
		$statistics_day = intval(date('d'));
		$statistics_hour = intval(date('H'));

		$statistics = mysqli_query($connect, "INSERT INTO LoginLog (m_id, m_login_id, m_usn, m_lang_code, m_game_code, m_service_code, m_type, m_year, m_month, m_day, m_hour, m_date, m_success, m_ip) VALUES (".$info['id'].",'".$login_id."', ".$info['usn'].",'".$language_code."','".$game_code."', '".$service_code."','2'," . $statistics_year . "," . $statistics_month . "," . $statistics_day . "," . $statistics_hour . ",now(), '1','".$ip."' )");
	?>
		<form name="login" method="post" action="https://<?php echo $return_path; ?>">
			<input type='hidden' name='login_id' value='<?php echo $_SESSION['sess_login_id'];?>'>
			<input type='hidden' name='sess_usn' value='<?php echo $_SESSION['sess_usn'];?>'>
			<input type='hidden' name='sess_change_serial_no' value='<?php echo $_SESSION['sess_change_serial_no'];?>'>
			<input type='hidden' name='method' id='method' value='login'>
		</form>

		<script type='text/javascript'>
			document.login.submit();
		</script>
<?php
	}
} else {

	$statistics_year = intval(date('Y'));
	$statistics_month = intval(date('m'));
	$statistics_day = intval(date('d'));
	$statistics_hour = intval(date('H'));

	$statistics = mysqli_query($connect, "INSERT INTO LoginLog (m_id, m_login_id, m_usn, m_lang_code, m_game_code, m_service_code, m_type, m_success, m_year, m_month, m_day, m_hour, m_date, m_ip) VALUES (".$info['id'].",'".$login_id."', ".$info['usn'].",'".$language_code."','".$game_code."', '".$service_code."','1','0'," . $statistics_year . "," . $statistics_month . "," . $statistics_day . "," . $statistics_hour . ",now(),'". $ip ."' )");
}


?>