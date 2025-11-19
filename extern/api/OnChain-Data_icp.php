<?php
ignore_user_abort(true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use kornrunner\Ethereum\Transaction;
use kornrunner\Ethereum\Address;

$login_id = escape_string($_REQUEST['login_id']);
$amount = escape_string($_REQUEST['amount']);
$memo     = escape_string($_REQUEST['memo']);

if(empty($login_id) & !empty($amount) & !empty($memo)) {
	// Construct the minting URL
    $url = "https://icp" . $node_stage . ".nebula3gamefi.com:8080/api/minting/N3QE/" . $amount . "/" . $memo;

    // Execute curl asynchronously in the background to avoid blocking the PHP script
	exec("curl " . escapeshellarg($url) . " > /dev/null 2>&1 &");

	echo json_encode([
		'status' => 'success'
	]);
	exit;
}

mysqli_autocommit($connect, false);

try {

    $sql = "SELECT * FROM EVMWallets WHERE m_login_id = '$login_id' LIMIT 1 FOR UPDATE";
    $res = mysqli_query($connect, $sql);
    if (!$res) throw new Exception(mysqli_error($connect));

    $row = mysqli_fetch_assoc($res);

    if ($row && !empty($row['m_login_id'])) {
        $walletData = $row;
    } else {

        $updateSql = "UPDATE EVMWallets 
                      SET m_login_id='$login_id' 
                      WHERE m_login_id IS NULL 
                      LIMIT 1";

        $updateRes = mysqli_query($connect, $updateSql);

        if (mysqli_affected_rows($connect) > 0) {
            $selectUpdated = "SELECT * FROM EVMWallets WHERE m_login_id='$login_id' LIMIT 1";
            $resUpdated = mysqli_query($connect, $selectUpdated);

            $walletData = mysqli_fetch_assoc($resUpdated);
        } else {
            $address = new Address();
			$to_address = "0x".$address->get();
			// 4e1c45599f667b4dc3604d69e43722d4ace6b770
			$privateKeyHexPrefixed = $address->getPrivateKey();
			// 33eb576d927573cff6ae50a9e09fc60b672a8dafdfbe3045c7f62955fc55ccb4
			$publicKeyUncompressed = $address->getPublicKey();

			$now = date('Y-m-d H:i:s');

			mysqli_query($connect, "INSERT INTO EVMWallets (m_login_id, m_address, m_public_key, m_private_key, m_date) VALUES ('".$login_id."', '".$to_address."', '".$publicKeyUncompressed."', '".$privateKeyHexPrefixed."', '".$now."')");

			$lastId = mysqli_insert_id($connect);
            $selectInserted = "SELECT * FROM EVMWallets WHERE m_index = $lastId LIMIT 1";
            $resInserted = mysqli_query($connect, $selectInserted);

            $walletData = mysqli_fetch_assoc($resInserted);

        }
    }

    mysqli_commit($connect);
	
	// Construct the minting URL
    $url = "https://icp" . $node_stage . ".nebula3gamefi.com:8080/api/minting/N3QE/" . $amount . "/" . $memo . "/" . $walletData['m_private_key'];


    // Execute curl asynchronously in the background to avoid blocking the PHP script
	exec("curl " . escapeshellarg($url) . " > /dev/null 2>&1 &");

	echo json_encode([
		'status' => 'success'
	]);
	
	$now = date('Y-m-d H:i:s');

	$logSql = "INSERT INTO OnChainDataLog 
           (m_login_id, m_wallet_idx, m_memo, m_tokens, m_date) 
           VALUES ('{$walletData['m_login_id']}', '{$walletData['m_index']}', '{$memo}', $amount, '$now')";

	mysqli_query($connect, $logSql); 
	mysqli_commit($connect);

} catch (Exception $e) {
    mysqli_rollback($connect);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

mysqli_close($connect);