const nftAddress = "0x84237e61cB64Eb2e0DdFd0467Dc19db4cBecDa15";  // live contract address
const rpcAddress = "https://eth.llamarpc.com";  // live net


const titleMessage = document.getElementById("title_msg");
const connectButton = document.getElementById("enableEthereumButton");
const walletID = document.getElementById("showAccount");

connectButton.addEventListener("click", () => {
	if (typeof window.ethereum !== "undefined") {
		if (ethereum.isMetaMask) {
			console.log('MetaMask is installed!');
			//Connecting to MetaMask
			ethereum.request({ method: "eth_requestAccounts" }).then((accounts) => {
				const account = accounts[0];
			//	account="0xdef7ec5db177b4694a0d3cc98f0fa5a8f389799e";	
				titleMessage.innerHTML = "Metamask connected: ";
				walletID.innerHTML = account.substr(0,8)+"...";
				walletID.style.display = "inline";
				$("#clipboard-wallet-account").attr("data-clipboard-text",account);
				$("#clipboard-wallet-account").show();
				connectButton.remove();
				setCookie("m_account", account, 1);

				const web3 = new Web3(window.ethereum);
				web3.eth.getBalance(account).then((balance) => {
					var finalETH = balance/1000000000000000000;	// wei to eth
					$("#metamask_first_address").html("<b>" + finalETH + " ETH</b>");
					$("#assets_eth").html('<a href="javascript:void(0);"><figure><img src="../images/sub/nft/icon-ethereum.svg" alt=""></figure><p><b>' + finalETH + ' ETH</b></p></a>');
					setCookie("m_finalETH", finalETH, 1);
				});

				const nft_array = get_nft_list_ethescan1(account);
			
			}).catch((error) => {
				walletID.innnerHTML = "Connecting Error. Please check metamask...";	
				console.log(error, error.code);
			});	 
		}
		else{
			console.log('Other wallet is installed!');
		}
	
   } else {
	  window.open("https://metamask.io/download/", "_blank");
   }
});



/** 
 * 지갑 주소 하나로 ethescan contract 에서 찾기
 *  return - 해당 지갑이 보유한 NFT id 배열
 */
async function get_nft_list_ethescan1(inputAddress){
	// NFT token ID 를 저장하기 위한 배열
	let tokenIdArray = new Array();
	let tempArray = new Array();
	//const etherscanApiUrl = "https://api-goerli.etherscan.io/api"; // test api server
	const etherscanApiUrl = "https://api.etherscan.io/api";  // live api server
	let sendUrl = etherscanApiUrl
				+"?module=account"
				+"&action=tokennfttx"
				+"&page=1"
				+"&offset=10000"
				+"&sort=asc"
				+"&contractaddress="+nftAddress
				+"&apikey=W9GGR7GKAHZ1PH7V7HNTKK941FH34DHMJZ";     // free APi key 100,000 call per day           
//	console.log(sendUrl);

	// api 호출
	fetch(sendUrl)
	.then(res => res.json())
	.then(data => {
		let resJson = JSON.parse(JSON.stringify(data)); 
		let resJsonResult = resJson.result;

		// 모든 받은 NFT ID 를 push 한다.
		for (i=0; i<resJsonResult.length; i++) {
			let trxOne = resJsonResult[i];
			if (trxOne.to == inputAddress) {
				tokenIdArray.push(trxOne.tokenID);
			}
		}

		// 거래 순서를 알 수 없으므로 다시 반복문으로 pop 한다.
		for (i=0; i<resJsonResult.length; i++) {
			let trxOne = resJsonResult[i];            
			if (trxOne.from == inputAddress) {
				for(i=0; i<tokenIdArray.length; i++) {
					if(tokenIdArray[i]==trxOne.tokenID) {
						tokenIdArray.pop();
					}
					else {
						tempArray.push(tokenIdArray.pop());
					}
				}                
				tokenIdArray = tempArray;
			}
		}        
		return(tokenIdArray);
	}).then( (tokenIdArray) => {
		var CountOfclwmcNFT = tokenIdArray.length;
		$("#assets_nft_clwmc").html(CountOfclwmcNFT);
		setCookie("m_countNFT", CountOfclwmcNFT, 1);
		var NFTs = "";
		var NFT_images = "";
		for (i = 0; i < tokenIdArray.length; i++) {
		  NFTs = NFTs + tokenIdArray[i] + "|";
		  get_nft_list1(tokenIdArray[i]).then( url => {
			 NFT_images = NFT_images + url + "|";
			 setCookie("m_NFT_images", NFT_images, 1);
		  });
		}
		setCookie("m_NFTs", NFTs, 1);
		console.log("NFTs : " + NFTs );
		console.log("All platform-related data for Metamask has been updated.");
	});
}

const tokenURIABI = [
	{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "tokenId",
				"type": "uint256"
			}
		],
		"name": "tokenURI",
		"outputs": [
			{
				"internalType": "string",
				"name": "",
				"type": "string"
			}
		],
		"stateMutability": "view",
		"type": "function"
	}
];

/**
 * ipfs 형식의 주소를 http 기반으로 치환
 */
function addIPFSProxy(ipfsHash) {
	const URL = "https://ipfs.io/ipfs/"  // base site of ipfs
	const hash = ipfsHash.replace(/^ipfs?:\/\//, '')
	const ipfsURL = URL + hash

//	console.log(ipfsURL)
	return ipfsURL
}


/**
 * id 를 알고있는 NFT 단건 가져오기
 */
async function get_nft_list1(tokenId) {
	// const tokenId = 5591;  // test 
	var web3 = new Web3(new Web3.providers.HttpProvider(rpcAddress));
	var contract  = new web3.eth.Contract(tokenURIABI, nftAddress);
	
	const result = await contract.methods.tokenURI(tokenId).call();
//	console.log(result);

	const ipfsURL = addIPFSProxy(result);

	const request = new Request(ipfsURL);
	const response = await fetch(request);
	const metadata = await response.json();
//	console.log(metadata);

	const image = addIPFSProxy(metadata.image);
	
	return image;
}