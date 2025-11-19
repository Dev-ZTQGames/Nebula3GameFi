<?php
ignore_user_abort(true);
date_default_timezone_set('Asia/Taipei');
header("Content-Type: application/json");
ini_set("max_execution_time", 300);
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$allowed_ips = ["116.203.129.16","116.203.134.67","23.88.105.37","128.140.8.200","91.99.23.109","34.80.134.81"];
$client_ip = get_client_ip();

if (!in_array($client_ip, $allowed_ips)) {
    http_response_code(403);
    exit("Access denied: Your IP ($client_ip) is not allowed.");
}

// ======== Fetch total length of on-chain data ========
$result = curl_return_json("https://icp$node_stage.nebula3gamefi.com:8080/api/chain_length/N3QE");
$total_transaction = $result['chain_length'];

echo "Total number of on-chain transactions: $total_transaction\n";

// ======== Fetch from the beginning to the latest ========
$query = mysqli_query($connect, "SELECT SUM(m_txs) AS txs FROM OnChainData");
$info = mysqli_fetch_array($query);
$txs_ = $info['txs'];
$length = $total_transaction - $txs_;

$result = curl_return_json("https://icp$node_stage.nebula3gamefi.com:8080/api/transactions/N3QE/$txs_/$length");


if (!isset($result['transactions'])) {
    die("Unable to retrieve transaction data, please check the API response.\n");
}

$txs = $result['transactions'];
echo "Number of transactions fetched: " . count($txs) . "\n";

// ======== Group by date ========
$txsByDate = $token = [];

foreach ($txs as $tx) {
    $txDate = date('Y-m-d', strtotime($tx['Timestamp']));
    if (!isset($txsByDate[$txDate])) {
        $txsByDate[$txDate] = [];
    }
	$memo = $tx['Memo'];
	
	if (!isset($txsByDate[$txDate][$memo])) {
        $token[$txDate][$memo] = [
            'count' => 0,
            'amount' => 0
        ];
    }
    $txsByDate[$txDate][$memo][] = $tx;
	
	$token[$txDate][$memo]['count']++;
	$token[$txDate][$memo]['amount'] += $tx['Amount'];  
}
var_dump($txsByDate);
//var_dump($token);
//exit;
// ======== Write into the database ========
/*
foreach ($txsByDate as $date => $memoTxList) {
    foreach ($memoTxList as $memo => $txList) {
        $txCount = count($txList); // Correctly calculate the occurrence of each memo
        $memoEscaped = mysqli_real_escape_string($connect, $memo);
		$tokens = $token[$date][$memo]['amount'];

        $values[] = "('$date', '$memoEscaped', $txCount, $tokens)";

    }
}

if (!empty($values)) {
    $sql = "
        INSERT INTO OnChainData (m_date, m_memo, m_txs, m_tokens)
        VALUES " . implode(',', $values) . "
        ON DUPLICATE KEY UPDATE
            m_txs = VALUES(m_txs),
            m_tokens = VALUES(m_tokens)
    ";

    mysqli_query($connect, $sql);
}
*/
//*
$yesterday = date('Y-m-d', strtotime('yesterday'));
$yesterdayTxs = $txsByDate[$yesterday];

$values = [];

foreach ($yesterdayTxs as $memo => $txList) {
    $txCount = count($txList);
    $memoEscaped = mysqli_real_escape_string($connect, $memo);
    $tokens = $token[$yesterday][$memo]['amount'];

    $values[] = "('$yesterday', '$memoEscaped', $txCount, $tokens)";
}

if (!empty($values)) {
    $sql = "
        INSERT INTO OnChainData (m_date, m_memo, m_txs, m_tokens)
        VALUES " . implode(',', $values) . "
        ON DUPLICATE KEY UPDATE
            m_txs = VALUES(m_txs),
            m_tokens = VALUES(m_tokens)
    ";

    $result = mysqli_query($connect, $sql);

    if ($result) {
        echo "✅ Successfully updated " . count($values) . " of data in batch（$yesterday）\n";
    } else {
        echo "❌ Batch update failed：" . mysqli_error($connect) . "\n";
    }
} else {
    echo "⚠️ No data available to update.\n";
}
//*/
echo "All done!\n";
?>
