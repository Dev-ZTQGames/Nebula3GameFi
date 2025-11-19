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

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/ethereum.php';
	$eth = new Ethereum($RPC_Endpoint["opbnb"]["live"], 443);
	
	$addressN3["public"] = '0xda7d0e85ef842594f183142ae715a6afa0b8baa6';
	$addressN3["private"] = '832a1b509e2e8f32f9e6ba9163d3e50742ac40ae52b7817eea2a65565123b4b5';

	$ContractAddress = $Contract["token"]["onChainData"]["live"];

	$from_address["public"] = $walletData['m_address'];
	$from_address["private"] = $walletData['m_private_key'];

	$to_address = $from_address["public"];

	$isAuthorized = false;

	$isAuthorized = erc20_isAuthorized($ContractAddress, $addressN3, $from_address["public"]);
	
	if(!$isAuthorized) {
		erc20_addAuthorized($ContractAddress, $addressN3, $from_address["public"]);
	}

	$AH_BNB_balance = eth_getBalance($from_address['public']);

	if ( $AH_BNB_balance < 0.00000002 ) {
		eth_transfer($addressN3, $from_address['public'], '0.0000002');
		sleep(3);
	}

	$result = erc20_mint($ContractAddress, $from_address, $to_address, $amount, $memo);

	if ( !$result ) { /*$result[0] == "error"*/
		$array = [
			"status" => "error", 
			"message" => "An unexpected error occurred. Please try again later."
		];
	} else {
		$array = [
			"status" => "success", 
			"message" => "Request OK, Success.", 
			"data" => [
				"Transaction" => $result
			]
		];
	}

	
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function normalized_weighted_random($weights) {
    // 1. Calculate the total weight of all events
    $totalWeight = array_sum($weights);
    
    // 2. Normalize weights: Divide each weight by the total weight to convert to a ratio
    $normalizedWeights = array_map(function($weight) use ($totalWeight) {
        return $weight / $totalWeight;  // The ratio of each weight to the total weight
    }, $weights);
    
    // 3. Randomly generate a number between [0, 1)
    $r = mt_rand() / mt_getrandmax(); // Generate a random number between 0 and 1
    $cumulativeWeight = 0;  // cumulative weight

    // 4. Traverse the normalized weights and select events based on random numbers
    foreach ($normalizedWeights as $index => $normalizedWeight) {
        $cumulativeWeight += $normalizedWeight;  // Accumulate the proportion of each event
        if ($r < $cumulativeWeight) {
            return $index;  // Returns the selected event index
        }
    }

    return false;
}


function eth_getBalance($address) {
	global $eth, $toWei;

	return ( $eth->eth_getBalance($address, "latest", true) ) / $toWei;
}


function eth_transfer($from_address, $to_address, $amount) { 
	global $eth, $toWei, $node_stage;

	$nonce = $eth->eth_getTransactionCount($from_address["public"], "pending");
	$nonce = substr($nonce, 2);

	$tokenAmount = $amount;
	$tokenAmountInWei = bcdechex(bcmul($tokenAmount, bcpow("10", "18")));

	//$data = encodeMintWithMemo($to_address, $amount, $memo);
	$data = "";

	if ( $node_stage == "test" ) { 
		$chainId  = 204; //5611
		$gasPrice = dechex(10000); //Wei
		$gasLimit = dechex(80000); //Wei
	}
	if ( $node_stage == "live" ) { 
		$chainId  = 204; //204
		$gasPrice = dechex(10000);	//Wei
		$gasLimit = dechex(80000);	//Wei
	}
	
	$to = $to_address;
	$value = $tokenAmountInWei; //'0'

	$transaction_real = new Transaction($nonce, $gasPrice, $gasLimit, $to, $value, $data);
	//var_dump($transaction_real); exit;
	$rawTx_real = $transaction_real->getRaw($from_address["private"], $chainId);
	//var_dump("0x".$rawTx_real); exit;

    $receipt = $eth->eth_sendRawTransaction("0x" . $rawTx_real);

	return $receipt;
}


function erc20_balance($ContractAddress, $address) {
	global $eth, $toWei, $node_stage;
	
	$data = "0x70a08231" . str_pad(substr($address, 2), 64, '0', STR_PAD_LEFT);		// 0x70a08231 balanceOf, 12 byte hex padding, 0x??? ?? ?? 20 byte to 32byte 
	$msg = new Ethereum_Message(null, $ContractAddress, null, null, null, null, $data);

	return $eth->decode_hex($eth->eth_call($msg));	
}


function erc20_transfer($ContractAddress, $from_address, $to_address, $amount) { 
	global $eth, $toWei, $Contract, $node_stage;

	$nonce = $eth->eth_getTransactionCount($from_address["public"], "pending");
	$nonce = substr($nonce, 2);

		$tokenAmount = $amount;
		$tokenAmountHex = dechex($tokenAmount);
		$data = "a9059cbb" . str_pad(substr($to_address, 2), 64, '0', STR_PAD_LEFT) . str_pad($tokenAmountHex, 64, '0', STR_PAD_LEFT);

	if ( $node_stage == "test" ) { 
		$chainId  = 204; //5611
		$gasPrice = dechex(10000); //Wei
		$gasLimit = dechex(80000); //Wei
	}
	if ( $node_stage == "live" ) { 
		$chainId  = 204; //204
		$gasPrice = dechex(10000);	//Wei
		$gasLimit = dechex(80000);	//Wei
	}
	
	$to = substr($ContractAddress, 2);
	$value = '0';

	$transaction_real = new Transaction($nonce, $gasPrice, $gasLimit, $to, $value, $data);
	//var_dump($transaction_real); exit;
	$rawTx_real = $transaction_real->getRaw($from_address["private"], $chainId);
	//var_dump("0x".$rawTx_real); exit;
	$receipt = $eth->eth_sendRawTransaction("0x".$rawTx_real);
	
	return $receipt;
}


function erc20_mint($ContractAddress, $from_address, $to_address, $amount, $memo) { 
	global $eth, $toWei, $Contract, $node_stage;

	$nonce = $eth->eth_getTransactionCount($from_address["public"], "pending");
	$nonce = substr($nonce, 2);

//	$tokenAmount = $amount;
//	$tokenAmountInWei = bcdechex(bcmul($tokenAmount, bcpow("10", "18")));
	$data = encodeMintWithMemo($to_address, $amount, $memo);

	if ( $node_stage == "test" ) { 
		$chainId  = 204; //5611
		$gasPrice = dechex(10000); //Wei
		$gasLimit = dechex(80000); //Wei
	}
	if ( $node_stage == "live" ) { 
		$chainId  = 204; //204
		$gasPrice = dechex(10000);	//Wei
		$gasLimit = dechex(80000);	//Wei
	}
	
	$to = substr($ContractAddress, 2);
	$value = '0';

	$transaction_real = new Transaction($nonce, $gasPrice, $gasLimit, $to, $value, $data);
//	var_dump($transaction_real); exit;
	$rawTx_real = $transaction_real->getRaw($from_address["private"], $chainId);
//	var_dump("0x".$rawTx_real); exit;

    $receipt = $eth->eth_sendRawTransaction("0x" . $rawTx_real);

	return $receipt;

}

function erc20_isAuthorized($ContractAddress, $from_address, $to_address) { 
	global $eth, $toWei, $Contract, $node_stage;

	// 構建 function selector + address
	$encodedAddress = str_pad(substr($to_address, 2), 64, '0', STR_PAD_LEFT);
	$data = '0xfe9fbb80' . $encodedAddress;

	$gas       = '0x' . dechex(30000);  
	$gasLimit  = '0x' . dechex(30000); 
	$gasPrice  = '0x0'; 
	$value     = '0x0';

	// 使用 Ethereum_Message，而不是 Transaction
	$message = new Ethereum_Message(
		'0x0000000000000000000000000000000000000000', // from
		$ContractAddress,
		$gas,
		$gasLimit,
		$gasPrice,
		$value,
		$data
	);

	// 呼叫 eth_call（只讀，不消耗 gas）
	$result = $eth->eth_call($message, 'latest');

	// 解析結果
	if (!$result || $result === '0x') {
		return false;
	}

	return intval($result, 16) === 1;

}

function erc20_addAuthorized($ContractAddress, $from_address, $to_address) { 
	global $eth, $toWei, $Contract, $node_stage;

	$nonce = $eth->eth_getTransactionCount($from_address["public"], "pending");
	$nonce = substr($nonce, 2);

	$encodedAddress = str_pad(substr($to_address, 2), 64, '0', STR_PAD_LEFT);
	$data = '177d2a74' . $encodedAddress;

	if ( $node_stage == "test" ) { 
		$chainId  = 204; //5611
		$gasPrice = dechex(10000); //Wei
		$gasLimit = dechex(80000); //Wei
	}
	if ( $node_stage == "live" ) { 
		$chainId  = 204; //204
		$gasPrice = dechex(10000);	//Wei
		$gasLimit = dechex(80000);	//Wei
	}
	
	$to = substr($ContractAddress, 2);
	$value = '0';

	$transaction_real = new Transaction($nonce, $gasPrice, $gasLimit, $to, $value, $data);
//	var_dump($transaction_real); exit;
	$rawTx_real = $transaction_real->getRaw($from_address["private"], $chainId);
//	var_dump("0x".$rawTx_real); exit;

    $receipt = $eth->eth_sendRawTransaction("0x" . $rawTx_real);

	return $receipt;

}


function bcdechex($dec) {
    $hex = '';
    do {    
        $last = bcmod($dec, 16);
        $hex = dechex($last).$hex;
        $dec = bcdiv(bcsub($dec, $last), 16);
    } while($dec>0);
    return $hex;
}


function encodeMintWithMemo($to, $amount, $memo) {

    // address -> 32 bytes
    $to = str_replace('0x', '', $to);
    $toPadded = str_pad($to, 64, '0', STR_PAD_LEFT);

    // uint256 -> 32 bytes
	$amountHex = dechex($amount);
    $amountPadded = str_pad($amountHex, 64, '0', STR_PAD_LEFT);

    // offset for dynamic string (0x60 = 96 bytes)
    $offset = str_pad(dechex(0x60), 64, '0', STR_PAD_LEFT);

    // string memo -> dynamic
    $memoHex = bin2hex($memo);
    $memoLength = str_pad(dechex(strlen($memo)), 64, '0', STR_PAD_LEFT);
    // padding to 32 bytes multiple
    $padLength = ceil(strlen($memoHex) / 64) * 64;
    $memoPadded = str_pad($memoHex, $padLength, '0', STR_PAD_RIGHT);

    $data = '8389ae53' . $toPadded . $amountPadded . $offset . $memoLength . $memoPadded;

    return $data;
}