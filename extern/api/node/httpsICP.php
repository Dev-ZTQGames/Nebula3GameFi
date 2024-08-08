<?php
include($_SERVER['DOCUMENT_ROOT'] . "/vendor/2tvenom/cborencode/CBOREncoder.php");
include($_SERVER['DOCUMENT_ROOT'] . "/vendor/2tvenom/cborencode/CBORExceptions.php");
include($_SERVER['DOCUMENT_ROOT'] . "/vendor/2tvenom/cborencode/Types/CBORByteString.php");

include("principal.php");

function createEnvelope($method, $body, $sender) {
    $now = time();
	
//	$canister_id = "sddoy-iyaaa-aaaam-aclfq-cai";

	$canister_id = Principal::fromText("sddoy-iyaaa-aaaam-aclfq-cai");
	$canister_id = $canister_id->toUint8Array();
//	print_r($canister_id);

//	$canister_id = [0, 0, 0, 0, 1, 128, 18, 203, 1, 1];
	$canister_id = pack('C*', ...$canister_id);

    // Construct the message
    $message = [
		"request_type" => "call",
		"sender" => $sender, //Principal
        "canister_id" => $canister_id,
        "method_name" => $method,
        "ingress_expiry" => ($now + 60), // Ingress expiry time
		"arg" => $body,
    ];

    // Create the envelope
    $envelope = [
        "content" => $message,
		"sender_pubkey" => "",
		"sender_sig" => "",
    ];

	// Serialize the Envelope to CBOR
	$cborEnvelope = \CBOR\CBOREncoder::encode($envelope);

/*
//	use https://cbor.me/ check
	$byte_arr = unpack("C*", $cborEnvelope);
	echo "Byte hex map = " . implode(" ", array_map(function($byte){
        return strtoupper(dechex($byte));
    }, $byte_arr)) . PHP_EOL;
	
	echo "<br>";
	$decodedEnvelope = \CBOR\CBOREncoder::decode($cborEnvelope);
	print_r($decodedEnvelope);
	echo "<br>";
*/
    return $cborEnvelope;
}

function sendRequest($envelope) {
    // Internet Identity canister URL
    $url = "https://icp-api.io/api/v2/canister/sddoy-iyaaa-aaaam-aclfq-cai/call";

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $envelope);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/cbor"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request
    $response = curl_exec($ch);
	echo $response . "<br>";
    // Check for errors
    if ($response === false) {
        echo "Error: " . curl_error($ch);
        return null;
    }

    // Close cURL session
    curl_close($ch);

    // Decode the response
	$decodedResponse = \CBOR\CBOREncoder::decode($response);

    return $decodedResponse;
}

// Example usage
$method = "icrc1_name";
$sender = "kafqf-lyqbp-sjswf-2tdvu-gz3ae-6h4b5-zlb3n-e3agj-2nfux-q42xe-vae";
//$sender = Principal::fromText("kafqf-lyqbp-sjswf-2tdvu-gz3ae-6h4b5-zlb3n-e3agj-2nfux-q42xe-vae");

//$sender = [16, 11, 228, 153, 88, 186, 152, 235, 67, 103, 96, 39, 143, 192, 247, 43, 14, 218, 77, 128, 201, 211, 75, 75, 195, 154, 185, 42, 2];
//$senderbinaryData = unpack('C*', ...$sender);

$body = [];
$binaryData = pack('C*', ...$body);

$envelope = createEnvelope($method, $binaryData, $sender);
$response = sendRequest($envelope);

// Handle the response
if ($response !== null) {
    echo "Response: " . $response;
} else {
    echo "Error occurred during the request.";
}