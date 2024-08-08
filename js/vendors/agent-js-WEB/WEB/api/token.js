const { createActor, BAT_ledger } = require("../declarations/Token_ledger_api");
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

async function transferOut(req, res) {
    try {
        // get main identity
        const { identity } = await getMainIdentity();
		
        // get user principalID and transfer amount from request
        const userPrincipalId = req.params.transferOut_id;
        const userPrincipal = Principal.fromText(userPrincipalId);
        const amount = Number(req.params.amount);

        const resultWithBigInt = await transferOutAction(identity, userPrincipal, amount);
		const resultWithNumbers	= replaceBigIntsWithNumbers(resultWithBigInt);
        const data = {
            status: 'transferOut',
            result: resultWithNumbers,
            amount: amount,
        };

        res.send(data);
    } catch (error) {
        console.error('Error transferring out:', error);
        res.status(500).json({ error: error.message });
    }
}

async function transferOutAction(identity, userPrincipal, amount) {
    const agent = new HttpAgent({ identity, host: "https://icp-api.io" });
    const actor_BAT_ledger = createActor("sddoy-iyaaa-aaaam-aclfq-cai", { agent });

	const mainPrincipalID = "lhgk6-qk64x-eppjw-znpre-rp7of-a2d5s-5qm3l-7y4e4-mowjf-vkuv6-uqe"; //p63kj-vlrqf-chucp-shmgr-gnuj7-uop7f-flj32-qzfgc-l55nf-vuutz-gae
	const mainPrincipal = Principal.fromText(mainPrincipalID);

    const Account_from = {
        'owner' : mainPrincipal,
        'subaccount' : [],
    };
    const Account_to = {
        'owner' : userPrincipal,
        'subaccount' : [],
    };
    const args = {
        'to' : Account_to,
        'fee' : [],
        'spender_subaccount' : [],
        'from' : Account_from,
        'memo' : [],
        'created_at_time' : [],
        'amount' : amount,
    };

    const result = await actor_BAT_ledger.icrc2_transfer_from(args);
    return result;
}

async function get_BAT_balances(req, res) {
    try {
		const userPrincipalId = req.params.principal_id;
        const userPrincipal = Principal.fromText(userPrincipalId);

        const args = {
            'owner' : userPrincipal,
            'subaccount' : [],
        };

        var resultWithBigInt = await BAT_ledger.icrc1_balance_of(args);
		const resultWithNumbers	= replaceBigIntsWithNumbers(resultWithBigInt);
		
		const data = {
            status: 'balances',
			principal_id: userPrincipalId,
            balances: resultWithNumbers,
        };

		res.send(data);
    } catch (error) {
        console.error('Error getting balance:', error);
		res.status(500).json({ error: error.message });
    }
}

async function get_BAT_transactions(req, res) {
    try {
		const start = parseInt(req.params.start);
		const length = parseInt(req.params.length);

        const args = {
            'start' : start,
            'length' : length,
        };

        var resultWithBigInt = await BAT_ledger.get_transactions(args);
		const resultWithNumbers	= replaceBigIntsWithNumbers(resultWithBigInt);

		const data = {
            status: 'transactions',
            result: resultWithNumbers,
        };

		res.send(data);
    } catch (error) {
        console.error('Error getting transactions:', error);
		res.status(500).json({ error: error.message });
    }
}

module.exports = { transferOut, get_BAT_balances, get_BAT_transactions };
