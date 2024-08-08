const { NFT_MiningMaze } = require("../declarations/NFT_MiningMaze_api");
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

async function mintDip721(req, res) {
    try {
		const userPrincipalId = req.params.principal_id;
        const userPrincipal = Principal.fromText(userPrincipalId);

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