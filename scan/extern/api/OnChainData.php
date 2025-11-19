<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";
include $_SERVER['DOCUMENT_ROOT'] . "/includes/json-rpc.php";

header('Content-Type: application/json');

$mode = escape_string($_REQUEST['mode']);

if($mode == "getHolders") {
	$host = "https://opbnb-mainnet.nodereal.io/v1/7b3fc06252684017addb652363d2281a";
	$port = 443;
	$rpc = new JSON_RPC($host, $port);

	$tokenAddress = "0xaD00C426255c14786E1A4Ec6bc41D99920444e01";

	$response = $rpc->request("nr_getTokenHolderCount", [$tokenAddress]);

	$hexValue = $response->result->result;
	$decimalValue = hexdec($hexValue);

	echo json_encode(number_format($decimalValue));
	exit;

}

if($mode == "getTransaction") {
		$list_num = 3;

		$result = curl_return_json("https://icp".$node_stage.".nebula3gamefi.com:8080/api/chain_length/N3QE");
		$total_transaction = $result['chain_length'];
		$chain_length = max(0, $result['chain_length'] - $list_num);
		$data_length = $result['chain_length'] >= $list_num ? $list_num : $result['chain_length'] % $list_num;

		$result = curl_return_json("https://icp$node_stage.nebula3gamefi.com:8080/api/transactions/N3QE/$chain_length/$data_length");
		$txs = $result['transactions'];
		for ($i = 0; $i < count($txs); $i++) {
		    $txs[$i]['Index'] = $chain_length + $data_length - $i;

			$start = substr( $txs[$i]['To'], 0, 6);
			$end = substr( $txs[$i]['To'], -6);
			$txs[$i]['To'] = $start . '...' . $end;
		}

		echo json_encode($txs);
		exit;
}