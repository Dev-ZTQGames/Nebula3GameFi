const { createActor } = require("../declarations/NFT_MiningMaze_api");
const { Principal } = require('@dfinity/principal');
const { Secp256k1KeyIdentity } = require('@dfinity/identity-secp256k1');
const { HttpAgent } = require('@dfinity/agent');

function replaceBigIntsWithNumbers(obj) {

  if (typeof obj === 'bigint') {
    return Number(obj);
  }
  
  if (typeof obj === 'object' && obj !== null) {
    for (let key in obj) {
      obj[key] = replaceBigIntsWithNumbers(obj[key]);
    }
  }
  
  return obj;
}

// get identity from seed
async function getMainIdentity() {
	// principalID: 22m77-meyox-ajdbx-bf2wv-thpd2-anszk-f25zw-pp2h4-ldab6-oizgf-iae
    const seed = "aurorahunt seed powerful quick kind swap transfer winner aurora hunter nabula space rock search find success Install library defined message possible secure provided randomly";
    const identity = await Secp256k1KeyIdentity.fromSeedPhrase(seed);
    return { identity };
}

async function mintDip721(req, res) {
    try {
		const userPrincipalId = req.params.principal_id;
        const userPrincipal = Principal.fromText(userPrincipalId);

		const { identity } = await getMainIdentity();
		const agent = new HttpAgent({ identity, host: "https://icp-api.io" });
		const NFT_MiningMaze = createActor("vkhzt-ryaaa-aaaam-ac7aq-cai", { agent });

        const resultWithBigInt = await NFT_MiningMaze.mintDip721(userPrincipal,[]);
		const resultWithNumbers	= replaceBigIntsWithNumbers(resultWithBigInt);
		const data = {
			status: 'mint',
			result: resultWithNumbers,
		};

		res.send(data);
    } catch (error) {
        console.error('Error getting result:', error);
		res.status(500).json({ error: error.message });
    }
}

async function mintRareCustom(req, res) {
    try {
		const userPrincipalId = req.params.principal_id;
        const userPrincipal = Principal.fromText(userPrincipalId);

		const { identity } = await getMainIdentity();
		const agent = new HttpAgent({ identity, host: "https://icp-api.io" });
		const NFT_MiningMaze = createActor("vkhzt-ryaaa-aaaam-ac7aq-cai", { agent });

        const resultWithBigInt = await NFT_MiningMaze.mintRareCustom(userPrincipal,[]);
		const resultWithNumbers	= replaceBigIntsWithNumbers(resultWithBigInt);

		const data = {
			status: 'mint',
			result: resultWithNumbers,
		};

		res.send(data);
    } catch (error) {
        console.error('Error getting result:', error);
		res.status(500).json({ error: error.message });
    }
}

async function mintEpicCustom(req, res) {
    try {
		const userPrincipalId = req.params.principal_id;
        const userPrincipal = Principal.fromText(userPrincipalId);

		const { identity } = await getMainIdentity();
		const agent = new HttpAgent({ identity, host: "https://icp-api.io" });
		const NFT_MiningMaze = createActor("vkhzt-ryaaa-aaaam-ac7aq-cai", { agent });

        const resultWithBigInt = await NFT_MiningMaze.mintEpicCustom(userPrincipal,[]);
		const resultWithNumbers	= replaceBigIntsWithNumbers(resultWithBigInt);

		const data = {
			status: 'mint',
			result: resultWithNumbers,
		};

		res.send(data);
    } catch (error) {
        console.error('Error getting result:', error);
		res.status(500).json({ error: error.message });
    }
}

async function mintUniqueCustom(req, res) {
    try {
		const userPrincipalId = req.params.principal_id;
        const userPrincipal = Principal.fromText(userPrincipalId);

		const { identity } = await getMainIdentity();
		const agent = new HttpAgent({ identity, host: "https://icp-api.io" });
		const NFT_MiningMaze = createActor("vkhzt-ryaaa-aaaam-ac7aq-cai", { agent });

        const resultWithBigInt = await NFT_MiningMaze.mintUniqueCustom(userPrincipal,[]);
		const resultWithNumbers	= replaceBigIntsWithNumbers(resultWithBigInt);

		const data = {
			status: 'mint',
			result: resultWithNumbers,
		};

		res.send(data);
    } catch (error) {
        console.error('Error getting result:', error);
		res.status(500).json({ error: error.message });
    }
}

async function mintLegendaryCustom(req, res) {
    try {
		const userPrincipalId = req.params.principal_id;
        const userPrincipal = Principal.fromText(userPrincipalId);

		const { identity } = await getMainIdentity();
		const agent = new HttpAgent({ identity, host: "https://icp-api.io" });
		const NFT_MiningMaze = createActor("vkhzt-ryaaa-aaaam-ac7aq-cai", { agent });

        const resultWithBigInt = await NFT_MiningMaze.mintLegendaryCustom(userPrincipal,[]);
		const resultWithNumbers	= replaceBigIntsWithNumbers(resultWithBigInt);

		const data = {
			status: 'mint',
			result: resultWithNumbers,
		};

		res.send(data);
    } catch (error) {
        console.error('Error getting result:', error);
		res.status(500).json({ error: error.message });
    }
}

module.exports = { mintDip721, mintRareCustom, mintEpicCustom, mintUniqueCustom, mintLegendaryCustom};