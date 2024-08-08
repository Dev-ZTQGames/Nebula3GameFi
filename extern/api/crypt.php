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

	output_log($json_result, "CRYPTOOO");
	exit;
?>