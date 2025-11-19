<?php
////////////////////////////////////////////////////////////////////////////////////
/////
/////	AES 128 비트 암호화 처리 ( 복호화 X )
/////
////////////////////////////////////////////////////////////////////////////////////
	header("Content-Type: application/json");

	include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

	$proc1		= escape_string(trim($_REQUEST['proc1']));		// key
	$proc2		= escape_string(trim($_REQUEST['proc2']));		// data

	if ( $proc1 == "" || $proc2 == "" ) {
		$array = array("result" => "0", "msg" => "empty items");
	} else	{

		$array = array("result" => "1", "return" => urlencode(AES128Encrypt($proc1, $proc2)));
	}	

	$json_result = json_encode($array);
	print($json_result);
	
	$tokenString = explode("|", $proc2);
	foreach ( $tokenString as $key => $value )	{
		$first =  strtok($value,"=");			
		$second = substr($value, strlen($first) + 1, strlen($value) -1 );
		$serial_array[$first] = $second;
	}
	
	//ICP OnChain-Data N3QE token minting  /includes/config.php
	if(!empty($serial_array['id'])) {
		mintN3QEToken("N3_EN_TOKEN", $serial_array['id']);
	}


	output_log($json_result, "CRYPTOOO");
	exit;
?>