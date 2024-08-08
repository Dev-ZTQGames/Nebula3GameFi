<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

session_unset();

$return_url = escape_string(trim($_REQUEST['return_url']));
if ( $return_url == "" ) $return_url = $HOST == "live" ? "nebula3gamefi.com" : $HOST . ".nebula3gamefi.com" ;

$time = time();

if($_COOKIE["login_id"]){

$que = mysqli_query($connect, "SELECT * FROM Accounts WHERE login_id='".$_COOKIE["login_id"]."'");
	if(mysqli_num_rows($que) > 0){
		$row = mysqli_fetch_array($que);

		$info = $row;

		$statistics_year = intval(date('Y'));
		$statistics_month = intval(date('m'));
		$statistics_day = intval(date('d'));
		$statistics_hour = intval(date('H'));

		$statistics = mysqli_query($connect, "INSERT INTO LogoutLog (m_id, m_login_id, m_usn, m_lang_code, m_game_code, m_service_code, m_type, m_year, m_month, m_day, m_hour, m_date,		 m_success, m_ip) VALUES (".$info['id'].",'".$info['login_id']."', ".$info['usn'].",'".$_COOKIE["aurorahunt_lang_code"]."','".$game_code."', '".$service_code."','2',	"	. $statistics_year . "," . $statistics_month . "," . $statistics_day . "," . $statistics_hour . ",now(), '1','".$ip."' )");
	}

setcookie('login_id', '', time()-3600, "/", ".nebula3gamefi.com");
setcookie('name', '', time()-3600, "/", ".nebula3gamefi.com");
setcookie('time', $time, time()-3600, "/", ".nebula3gamefi.com");		
setcookie('id', '', time()-3600, "/",".nebula3gamefi.com");
setcookie('email', '', time()-3600, "/", ".nebula3gamefi.com");
setcookie('hash', '', time()-3600, "/", ".nebula3gamefi.com");

setcookie('m_account', '', time()-3600,"/",".nebula3gamefi.com");
setcookie('m_finalETH', '', time()-3600,"/",".nebula3gamefi.com");
setcookie('m_finalPMW', '', time()-3600,"/",".nebula3gamefi.com");
setcookie('m_countNFT', '', time()-3600,"/",".nebula3gamefi.com");
setcookie('m_NFTs', '', time()-3600,"/",".nebula3gamefi.com");
setcookie('m_NFT_images', '', time()-3600,"/",".nebula3gamefi.com");
}

?>
<script>
location.href="https://<?php echo $return_url; ?>";
/*
  function statusChangeCallback(response) {
    if (response.status === 'connected') {
      testAPI();
    } else {
		location.href="https://<?php echo $return_url; ?>";
    }
  }

  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '623467932234688',
    cookie     : true,  
    xfbml      : true,  
    version    : 'v14.0'
  });

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  function testAPI() {
   FB.logout(function(response) {
  		location.href="https://<?php echo $return_url; ?>";
	});
  }
 */
</script>