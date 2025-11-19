<?php
////////////////////////////////////////////////////////////////////////////////////
/////
/////	AES 128 비트 암호화 처리 ( 복호화 X )
/////
////////////////////////////////////////////////////////////////////////////////////
	header("Content-Type: application/json");

	include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

/*
$result = curl_return_json("https://testnet.tonapi.io/v2/traces/e04a272e0835b38741a088846b6bef8dfd24264b232762335051d310e20ed9e5");
$data = findJettonTransfer($result);
echo json_encode($data);
$status = $data["success"] ? 1 : 0;

exit;
	if (isset($data['in_msg']['decoded_op_name'])) {
	    $opName = $data['in_msg']['decoded_op_name'];
	}

	if (isset($data['in_msg']['decoded_body']['amount'])) {
	    $amount = $data['in_msg']['decoded_body']['amount'] / 1e9;
	}

	if (isset($data['in_msg']['decoded_body']['destination'])) {
	    $destination = tonRawToUserFriendly($data['in_msg']['decoded_body']['destination'], false);
	}
	
	if ($opName === 'jetton_transfer') {
    echo "操作類型: {$opName}\n";
    echo "轉帳數量: {$amount}\n";
    echo "接收地址: {$destination}\n";
} else {
    echo "不是 jetton_transfer 交易\n";
}
	
//echo json_encode($result['transaction']["success"]);
//echo json_encode($result["children"][0]["transaction"]["in_msg"]);

exit;
*/
function findJettonTransfer($tx) {
    // 檢查當前 transaction
    if (isset($tx['transaction']['in_msg']['decoded_op_name']) && 
        $tx['transaction']['in_msg']['decoded_op_name'] === 'jetton_transfer') {
        return $tx['transaction'];
    }

    // 遞迴檢查 children
    if (isset($tx['children']) && is_array($tx['children'])) {
        foreach ($tx['children'] as $child) {
            $result = findJettonTransfer($child);
            if ($result !== null) {
                return $result;
            }
        }
    }

    return null;
}


function tonRawToUserFriendly($raw, $bounceable = true) {
    // 拆解 raw 格式 "0:xxxx"
    list($wc, $hex) = explode(':', $raw);
    $wc = intval($wc);
    $addrBytes = hex2bin($hex);

    if ($addrBytes === false || strlen($addrBytes) !== 32) {
        throw new Exception("Invalid raw address");
    }

    // 設定 tag (bounceable or non-bounceable)
    $tag = $bounceable ? chr(0x11) : chr(0x51);

    // workchain byte (有符號)
    $wcByte = pack('c', $wc);

    $bytes = $tag . $wcByte . $addrBytes;

    // 加上 CRC16
    $crc = crc16($bytes);
    $full = $bytes . $crc;

    // Base64 (URL-safe)
    return rtrim(strtr(base64_encode($full), '+/', '-_'), '=');
}

function crc16($data) {
    $poly = 0x1021;
    $crc = 0;
    $len = strlen($data);
    for ($i = 0; $i < $len; $i++) {
        $crc ^= (ord($data[$i]) << 8);
        for ($j = 0; $j < 8; $j++) {
            if ($crc & 0x8000) {
                $crc = ($crc << 1) ^ $poly;
            } else {
                $crc <<= 1;
            }
            $crc &= 0xFFFF;
        }
    }
    return pack('n', $crc);
}

// 測試
$raw = "0:4a12956817c3710bfbc8b54817206b8e259e29b0c23b467c076374fd7cfb9aac";

echo "Bounceable (EQ...): " . tonRawToUserFriendly($raw, true) . PHP_EOL;
echo "Non-Bounceable (UQ...): " . tonRawToUserFriendly($raw, false) . PHP_EOL;