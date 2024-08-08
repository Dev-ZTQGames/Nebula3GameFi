<?php

function createEnvelope($method, $path, $body) {
    $now = time();

    // Construct the message
    $message = [
        "request_type" => "call",
        "method_name" => $method,
        "args" => $body,
        "sender" => "anonymous",
        "timestamp" => (string) $now,
    ];

    // Serialize the message to CBOR
    $cborMessage = cb_encode($message);

    // Create the envelope
    $envelope = [
        "content" => $cborMessage,
        "sender_pubkey" => "",
        "sender_sig" => "",
        "ingress_expiry" => (string) ($now + 300), // Ingress expiry time
    ];

    return $envelope;
}

function sendRequest($envelope) {
    // Internet Identity canister URL
    $url = "https://icp-api.io/api/v2/canister/rdmx6-jaaaa-aaaaa-aaadq-cai/read_state";

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($envelope));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/cbor"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if ($response === false) {
        echo "Error: " . curl_error($ch);
        return null;
    }

    // Close cURL session
    curl_close($ch);

    // Decode the response
    $decodedResponse = cb_decode($response);

    return $decodedResponse;
}

// Example usage
$method = "authenticate";
$path = "/";
$body = ["user_identity_here"]; // Replace with actual user identity
$envelope = createEnvelope($method, $path, $body);
$response = sendRequest($envelope);

// Handle the response
if ($response !== null) {
    echo "Response: " . json_encode($response);
} else {
    echo "Error occurred during the request.";
}

?>
