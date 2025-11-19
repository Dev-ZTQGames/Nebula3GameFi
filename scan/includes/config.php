<?php
date_default_timezone_set('Asia/Taipei');
ini_set('display_errors', 0);
//ini_set('session.referer_check', 'TRUE');

//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(E_ALL);

session_start(); 

header("Access-Control-Allow-Origin: *");
header("Cache-Control","no-cache");

include "config_servers.php";	
include "class.Curl.php";

$configuration['version'] = "v0.3.251001";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 분기 및 Staging, DB/Image server info

if ( in_array($_SERVER['SERVER_ADDR'], $TEST_Server) ) {
	$configuration['mode'] = "test / debug";		// 테스트
	$region="test";
	$return_url = $_SERVER['HTTP_HOST'];
} elseif ( in_array($_SERVER['SERVER_ADDR'], $DEV_Server) ) {			
	$configuration['mode'] = "development / alpha";		// 개발 
	$region="dev";
	$return_url = $_SERVER['HTTP_HOST'];
} else {
	$configuration['mode'] = "service / release";		// 라이브


	if ( in_array($_SERVER['SERVER_ADDR'], $LIVE_Server['US'] ) ) {
		$region="US";
		$return_url = "www.nebula3gamefi.com";
	}
	else {
		die("System error...");
	}
}

if ( $configuration['mode'] == "test / debug" ) {							// GCP 테스트 서버
	$DB_INFO = array(
		'HOST'  => '10.95.160.3',
		'ID'	=> 'root',
		'PASS'  => 'fnrIDyLxq2BuE1Z6BV5MbA==',
		'NAME'  => 'aurorahunt'
	);

	$dir = 'test';
	$HOST = 'test';	

	$IMAGE_INFO = array(				// Image Server Connection
		'HOST' => '10.140.0.4',
		'ID' => 'neoguru000',
		'PASS' => 'qrwe1423!',
		'PATH' => '/home/aurora/img_remote/'.$dir,
		'MODE' => FTP_BINARY
	);

	$image_mount_endpoint = 'https://test.nebula3gamefi.com';
	$image_mount = '/img_remote/test';

} elseif ( $configuration['mode'] == "development / alpha" ) {		// IDC 개발 서버 
	$DB_INFO = array(				// DEV DB Connection
		'HOST' => '10.10.5.44',
		'ID'   => 'root',
		'PASS' => 'tmvptufvhtm12#$',
		'NAME' => 'ah'
	);

	$dir = 'dev';
	$HOST = 'dev';	

	$IMAGE_INFO = array(				// Image Server Connection
		'HOST' => '10.10.5.57',
		'ID'   => 'neoguru',
		'PASS' => 'qrwe1423',
		'PATH' => '/image/ah_dev',
		'MODE' => FTP_BINARY
	);

	$image_mount_endpoint = 'https://image.aurorahunt.xyz';
	$image_mount = '/img_remote/ah_dev';

} elseif ( $configuration['mode'] == "service / release" ) {		// GCP 라이브 서버 

	$dir = 'live';
	/* Asia
	$IMAGE_INFO = array(				// Image Server Connection
		'HOST' => '10.141.0.2',
		'ID'   => 'neoguru000',
		'PASS' => 'qrwe1423!',
		'PATH' => '/home/aurora/img_remote/'.$dir,
		'MODE' => FTP_BINARY
	);
	*/
	if ( $region=="US" ) {				// US 는 이미지 서버 버킷이 다르므로 별도 셋팅
		$DB_INFO = array(				// LIVE DB Connection
			'HOST' => '10.95.160.5',	/////
			'ID'   => 'root',				//
			'PASS' => 'fnrIDyLxq2BuE1Z6BV5MbA==',		//
			'NAME' => 'aurorahunt'			//
		);

		$IMAGE_INFO = array(				// Image Server Connection
			'HOST' => '10.140.0.4',
			'ID'   => 'neoguru000',
			'PASS' => 'qrwe1423!',
			'PATH' => '/home/aurora/img_remote/'.$dir,
			'MODE' => FTP_BINARY
		);

		$HOST = "www";

	}

	$image_mount_endpoint = 'https://nebula3gamefi.com';
	$image_mount = '/img_remote/live';
}

// BlockChain 
if ( $configuration['mode'] == "development / alpha" )		$node_stage = "test";
if ( $configuration['mode'] == "test / debug" )				$node_stage = "test";
if ( $configuration['mode'] == "service / release" )			$node_stage = "live";

$Contract["token"]["N3QE"]["test"] = "hyiyv-saaaa-aaaal-asnsq-cai";
$Contract["token"]["N3QE"]["live"] = "hyiyv-saaaa-aaaal-asnsq-cai";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// custom functions start
function base2_64_encode($val) {
    return $val;
}

function escape_str($str, $like = FALSE) {
	$str = $str ?? '';

    if (is_array($str))
    {
        foreach ($str as $key => $val)
        {
            $str[$key] = $this->escape_str($val, $like);
        }

        return $str;
    }

    $str = addslashes($str);

    // escape LIKE condition wildcards
    if ($like === TRUE)
    {
        $str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
    }

    return $str;
}

function escape_string($data) {
	$data = $data ?? '';

    $data = str_replace("`", "", $data);
    $data = str_replace("'", "", $data);
    $data = str_replace("\"", "", $data);
    $data = str_replace(";", "", $data);
    $data = str_replace("\\", "", $data);

    //$data = mysql_escape_string($data);
    $data = escape_str($data);

    return $data;
}

function curl_post($url, $fields)
{
    $post_field_string = http_build_query($fields, '', '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);
    curl_setopt($ch, CURLOPT_POST, true);
    $response = curl_exec($ch);
    curl_close ($ch);
    return $response;
}

function curl_return_json($_url){
 
    $ch     = curl_init();
 
    curl_setopt($ch, CURLOPT_URL, $_url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE); // http header return 삭제
    curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // 텍스트로 받음
 
    $_data     = curl_exec($ch);
 
    curl_close($ch);
 
    return json_decode($_data, TRUE);
}

function get_client_ip()
{
    foreach (array(
                'HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER)) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if ((bool) filter_var($ip, FILTER_VALIDATE_IP,
                                FILTER_FLAG_IPV4 |
                                FILTER_FLAG_NO_PRIV_RANGE |
                                FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
    }
    return null;
}

function checkEmailDomainMX($email) {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $domain = substr(strrchr($email, "@"), 1);

    if (checkdnsrr($domain, "MX")) {
        return true;
    } else {
        return false;
    }
}

function timeAgo($datetimeStr) {
    $target = DateTime::createFromFormat('m/d/Y, H:i:s', $datetimeStr);
    if (!$target) {
        return "Invalid date format";
    }

    $now = new DateTime();
    $diff = $now->getTimestamp() - $target->getTimestamp();
    $isFuture = $diff < 0;
    $diff = abs($diff);

    if ($diff < 60) {
        $value = $diff;
        $unit = "sec";
    } elseif ($diff < 3600) {
        $value = floor($diff / 60);
        $unit = "min";
    } elseif ($diff < 86400) {
        $value = floor($diff / 3600);
        $unit = "hour";
    } elseif ($diff < 2592000) {
        $value = floor($diff / 86400);
        $unit = "day";
    } elseif ($diff < 31536000) {
        $value = floor($diff / 2592000);
        $unit = "month";
    } else {
        $value = floor($diff / 31536000);
        $unit = "year";
    }

    $timeStr = $value . ' ' . $unit . ($value > 1 ? 's' : '');
    return $isFuture ? "in $timeStr" : "$timeStr ago";
}

function formatNumberShort($num, $decimals = 1) {
    if ($num >= 1_000_000_000) {
        return round($num / 1_000_000_000, $decimals) . 'B';
    } elseif ($num >= 1_000_000) {
        return round($num / 1_000_000, $decimals) . 'M';
    } elseif ($num >= 1_000) {
        return round($num / 1_000, $decimals) . 'K';
    } else {
        return (string)$num;
    }
}
// custom functions ends
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// Images
$supported_file_extension = array(
    'jpg',
    'jpeg',
    'png',
    'gif',
    'bmp'
);

// Check Mobile : isMobile
$mAgent = array("iphone","ipod","android","blackberry", "opera mini", "windows ce", "nokia", "sony", "lgtelecom", "skt", "samsung", "AOS", "IOS" );
$isMobile = false;
for($i=0; $i<sizeof($mAgent); $i++){
    if(stripos( strtolower($_SERVER['HTTP_USER_AGENT']), $mAgent[$i] )){
        $isMobile = true;
        break;
    }
}

// Check Android : isAndroid
$isAndroid = (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') !== false);
// Check iOS : isiOS
$isiOS = false;
if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') == true) {
	$isiOS = true;
} elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') == true){
	$isiOS = true;
} elseif(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'ios') == true){
	$isiOS = true;
}

// Check Metamask
$isMetamask = (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'metamask') !== false);

// Check Safari
$isSafari = false;
if (preg_match('/Safari/i',$u_agent)){
	$isSafari = true;
}

// Set IP and URI
if(!empty($_SERVER['HTTP_CLIENT_IP'])){
   $ip = $_SERVER['HTTP_CLIENT_IP'];
}else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
   $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}else{
   $ip= $_SERVER['REMOTE_ADDR'];
}
$ip = explode(":",$ip)[0];

$uri= $_SERVER['REQUEST_URI'];
/*
// Set Language and Host
// -> 전달자가 없으면, 쿠키 체크하여 언어 설정 불러옴. 기본 언어 : 한국어가 아닌 deploy 서버 IP 인식에 따른 region의 lowercase로 처리한다
// 리전은 upper, 언어코드는 lower 케이스로 처리
$lang	= escape_string(trim($_REQUEST['lang']));

$url_lang = substr($_SERVER['HTTP_HOST'], 0, 2);

if($url_lang == 'us'){
	$url_lang = 'en';
}
//언어 리스트 : ko, en, tw, cn, jp, ee, es, vi
$lang_list = array('ko', 'en', 'tw', 'cn', 'jp', 'ee', 'es', 'vi');

if ( $lang != "" )	{
	$lang_code = $lang;
}
else {
	if ( $_COOKIE['aurorahunt_lang_code'] != "" )	{
		$lang_code = $_COOKIE['aurorahunt_lang_code'];
	}
	if ( in_array($url_lang, $lang_list) ) {
		$lang_code = $url_lang;
	}
	else {
		if ( $configuration['mode'] == "service / release" ) {
			$region = "KO"; 
			$lang_code = strtolower($region);
		} else {
			$lang_code = "ko";
		}
		//setcookie('aurorahunt_lang_code', $lang_code, time() + 60 * 60 * 24 * 365, "/", ".aurorahunt.xyz");
	}
	setcookie('aurorahunt_lang_code', $lang_code, time() + 60 * 60 * 24 * 365, "/", ".aurorahunt.xyz");
}

// 라이브 서버 일때는 리전 정보가 언어코드보다 우선 할 수 없다. 
if ( $configuration['mode'] == "service / release" ) {
	$HOST = $lang_code;

	if($lang_code == 'en'){
		$HOST = 'us';
	}

}
else { // 라이브 서버가 아닐때는 선택한 언어 정보가 곧 해당 정보
	if($lang_code == 'en'){
		$HOST = 'us'.$HOST;
	} else {
		$HOST = $lang_code . $HOST;
	}
}

if ( in_array($_SERVER['SERVER_ADDR'], $Server_18 ) ) {
	$isETServer = true;
	$HOST .= "-18";
}
else {
	$isETServer = false;
}
*/
$lang_code = 'en';
// DB connection
$connect = mysqli_connect($DB_INFO['HOST'], $DB_INFO['ID'], $DB_INFO['PASS'], $DB_INFO['NAME']);
//require_once($_SERVER['DOCUMENT_ROOT']. "/lang/" . $lang_code . "/" . $lang_code . ".inc");

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 관리자 개인 언어설정 
$language_code = "ko";
