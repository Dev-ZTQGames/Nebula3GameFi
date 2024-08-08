//  NFT
import {
    createActor as createActor_NFT_MININGMAZE,
	NFT_MiningMaze
} from "../declarations/NFT_MiningMaze";
//  PMW Token
import {
    createActor as createActor_Token_index,
} from "../declarations/Token_index";
import {
    createActor as createActor_Token_ledger,
} from "../declarations/Token_ledger";

import { AuthClient } from "@dfinity/auth-client";
import { HttpAgent } from "@dfinity/agent";
import { Principal } from '@dfinity/principal';

let principal_user;

let actor_NFT_MM;
let actor_PMW_ledger;
let actor_PMW_index;
let actor_ICP_ledger;

let authClient;

async function initAuthClient() {
    authClient = await AuthClient.create({
		idleOptions: {
		  idleTimeout: 1000 * 60 * 60 * 24, // set to 1 day
		  disableDefaultIdleCallback: true // disable the default reload behavior
		}
	});
}

const init = async () => {

	if (await authClient.isAuthenticated()) {
		console.log('Logged in');
	    await handleAuthenticated(authClient);
		
		const principalIDPromise = principal_user.toText();
		const NFT_balancesPromise = get_NFT_balances(principal_user);
		const PMW_balancesPromise = get_PMW_balances(principal_user);
		const ICP_balancesPromise = get_ICP_balances(principal_user);
		const NFT_listPromise = get_NFT_list(principal_user);
		
		const [principalID, NFT_balances, PMW_balances, ICP_balances, NFT_list] = await Promise.all([
		    principalIDPromise,
		    NFT_balancesPromise,
		    PMW_balancesPromise,
		    ICP_balancesPromise,
		    NFT_listPromise
		]);

		const data = {
		    LoggedIn: true,
		    principalID: principalID,
		    NFT_balances: NFT_balances,
			PMW_balances: PMW_balances,
			ICP_balances: ICP_balances,
			NFT_list: NFT_list,
		};
		window.parent.postMessage(data, '*');
	} else {
		console.log('Not logged in');

		const data = {
		    LoggedIn: false
		};
		window.parent.postMessage(data, '*');

	}
};

async function login() {
		
	const days = BigInt(1);
	const hours = BigInt(24);
	const nanoseconds = BigInt(3600000000000);
  
	(async () => {
	    await authClient.login({
	        onSuccess: async () => {
	            await handleAuthenticated(authClient);
				window.parent.postMessage('reloadPage', '*');
	        },
	        identityProvider: "https://identity.ic0.app/#authorize",
	        // Maximum authorization expiration is 8 days
	        maxTimeToLive: days * hours * nanoseconds,
	    });
	})();
}

async function logout() {
		
	await authClient.logout();
	authClient = null;
	window.parent.postMessage('reloadPage', '*');

}
  
async function handleAuthenticated(authClient) {
	const identity = await authClient.getIdentity();
	principal_user = await identity.getPrincipal();
	console.log(identity);
	const agent = new HttpAgent({ identity, host: "https://icp-api.io" });

	actor_NFT_MM = createActor_NFT_MININGMAZE(process.env.CANISTER_ID_NFT_MININGMAZE, {
	    agent,
	});
  
	actor_PMW_index = createActor_Token_index(process.env.CANISTER_ID_PMW_INDEX, {
	    agent,
	});
	actor_PMW_ledger = createActor_Token_ledger(process.env.CANISTER_ID_PMW_LEDGER, {
	    agent,
	});

		actor_ICP_ledger = createActor_Token_ledger("ryjl3-tyaaa-aaaaa-aaaba-cai", {
	    agent,
	});
  
}


//Token function

async function get_PMW_balances(principal) {
    try {
        const Account = {
            'owner' : principal,
            'subaccount' : [],
        };

        var balances = await actor_PMW_ledger.icrc1_balance_of(Account);
	//	console.log("PMW balance: " + balance);
		return balances;
    } catch (error) {
        console.error('Error getting balance:', error);
    }
}

async function get_ICP_balances(principal) {
    try {
        const Account = {
            'owner' : principal,
            'subaccount' : [],
        };

        var balances = await actor_ICP_ledger.icrc1_balance_of(Account);
	//	console.log("ICP balance: " + balance);
		return balances;
    } catch (error) {
        console.error('Error getting balance:', error);
    }
}

async function transfer(Tokens) {
    try {
		const principalID_main = 'p63kj-vlrqf-chucp-shmgr-gnuj7-uop7f-flj32-qzfgc-l55nf-vuutz-gae';
		const principal_main = Principal.fromText(principalID_main);

		const Account = {
            'owner' : principal_main,
            'subaccount' : [],
        };
        const args = {
			'to' : Account,
			'fee' : [],
			'memo' : [],
			'from_subaccount' : [],
			'created_at_time' : [],
			'amount' : Tokens,
        };

        var result = await actor_PMW_ledger.icrc1_transfer(args);
	//	console.log("result: " + result);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}
/*
async function transfer_out(principal, Tokens) {
    try {
		const main_principalID = 'pi5f5-wa6q7-y2zcs-4nqx7-veomh-k3rqy-bpii6-54d47-iix3c-hh3nx-pae';
		const main_principal = Principal.fromText(main_principalID);
		
		const Account_from = {
            'owner' : main_principal,
            'subaccount' : [],
        };
		const Account_to = {
            'owner' : principal,
            'subaccount' : [],
        };
        const args = {
			'to' : Account_to,
			'fee' : [],
			'spender_subaccount' : [],
			'from' : Account_from,
			'memo' : [],
			'created_at_time' : [],
			'amount' : Tokens,
        };

        var result = await actor_PMW_ledger.icrc2_transfer_from(args);
	//	console.log("result: " + result);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}
*/

//NFT function

async function get_NFT_balances(principal) {
    try {
        var result = await actor_NFT_MM.balanceOfDip721(principal);
     //	console.log("NFT_MM balances: " + result);
		return result;
    } catch (error) {
        console.error('Error getting balance:', error);
    }
}

async function get_NFT_list(principal) {
    try {
        var result = await actor_NFT_MM.getTokenIdsForUserDip721(principal);
     //	console.log("NFT_MM list: " + result);
		return result;
    } catch (error) {
        console.error('Error getting list:', error);
    }
}


/*
async function mintDip721(principal) {
    try {
		var args = {
			
		};
        var result = await actor_NFT_MM.mintDip721(principal,[]);
     //	console.log("NFT_MM list: " + list);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}

async function executeMinting(principal, times) {
    let results = [];

    for (let i = 0; i < times; i++) {
        let result = await mintDip721(principal);
        results.push(result);
    }

    return results;
}

async function mintRareCustom(principal) {
    try {
        var result = await actor_NFT_MM.mintRareCustom(principal,[]);
     //	console.log("NFT_MM list: " + list);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}

async function mintEpicCustom(principal) {
    try {
        var result = await actor_NFT_MM.mintEpicCustom(principal,[]);
     //	console.log("NFT_MM list: " + list);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}

async function mintUniqueCustom(principal) {
    try {
        var result = await actor_NFT_MM.mintUniqueCustom(principal,[]);
     //	console.log("NFT_MM list: " + list);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}

async function mintLegendaryCustom(principal) {
    try {
        var result = await actor_NFT_MM.mintLegendaryCustom(principal,[]);
     //	console.log("NFT_MM list: " + list);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}
*/


async function totalSupplyNFT() {
    try {
        var result = await NFT_MiningMaze.totalSupplyDip721();
     //	console.log("NFT_MM list: " + list);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}

async function totalSupplyNormalNFT() {
    try {
        var result = await NFT_MiningMaze.totalSupplyNormalCustom();
     //	console.log("NFT_MM list: " + list);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}

async function totalSupplyRareNFT() {
    try {
        var result = await NFT_MiningMaze.totalSupplyRareCustom();
     //	console.log("NFT_MM list: " + list);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}

async function totalSupplyEpicNFT() {
    try {
        var result = await NFT_MiningMaze.totalSupplyEpicCustom();
     //	console.log("NFT_MM list: " + list);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}

async function totalSupplyUniqueNFT() {
    try {
        var result = await NFT_MiningMaze.totalSupplyUniqueCustom();
     //	console.log("NFT_MM list: " + list);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}

async function totalSupplyLegendaryNFT() {
    try {
        var result = await NFT_MiningMaze.totalSupplyLegendaryCustom();
     //	console.log("NFT_MM list: " + list);
		return result;
    } catch (error) {
        console.error('Error getting result:', error);
    }
}

document.addEventListener("DOMContentLoaded", async function() {

	await initAuthClient();
	await init();
	
	const allowedDomains = ['https://dev.nebula3gamefi.com', 'https://test.nebula3gamefi.com', 'https://nebula3gamefi.com', 'https://www.nebula3gamefi.com'];
	window.addEventListener('message', async function(event) {
		
		if (allowedDomains.includes(event.origin)) {
			if (event.data === 'login') {
			    login();

			} else if (event.data === 'logout') {
				logout();

			} else if (event.data.mode === 'transfer') {
				const amount = event.data.amount;
			    transfer(amount).then(function(result) {
					const data = {
						status: 'transfer',
						amount: amount,
						result: result,
					};
					window.parent.postMessage(data, '*');
				});
				

			}/* else if (event.data.mode === 'transferOut') {
				const amount = event.data.amount;
			    transfer_out(principal_user, amount).then(function(result) {
					const data = {
						status: 'transferOut',
						result: result,
						amount: amount,
					}
					window.parent.postMessage(data, '*');
				});

			} else if (event.data === 'mintDip721') {
			    mintDip721(principal_user).then(function(result) {
					const data = {
						status: 'mint',
						result: result,
					}
					window.parent.postMessage(data, '*');
				});

			} else if (event.data.mode === 'executeMinting') {
				const times = event.data.times;
			    executeMinting(principal_user,times).then(function(result) {
					const data = {
						status: 'mint',
						result: result,
					}
					window.parent.postMessage(data, '*');
				});

			} else if (event.data === 'mintRareCustom') {
			    mintRareCustom(principal_user).then(function(result) {
					const data = {
						status: 'mint',
						result: result,
					}
					window.parent.postMessage(data, '*');
				});

			} else if (event.data === 'mintEpicCustom') {
			    mintEpicCustom(principal_user).then(function(result) {
					const data = {
						status: 'mint',
						result: result,
					}
					window.parent.postMessage(data, '*');
				});

			} else if (event.data === 'mintUniqueCustom') {
			    mintUniqueCustom(principal_user).then(function(result) {
					const data = {
						status: 'mint',
						result: result,
					}
					window.parent.postMessage(data, '*');
				});

			} else if (event.data === 'mintLegendaryCustom') {
			    mintLegendaryCustom(principal_user).then(function(result) {
					const data = {
						status: 'mint',
						result: result,
					}
					window.parent.postMessage(data, '*');
				});

			} */else if (event.data === 'totalSupply') {

				const totalSupplyNormalCustom	 = await totalSupplyNormalNFT();
				const totalSupplyRareCustom		 = await totalSupplyRareNFT();
				const totalSupplyEpicCustom		 = await totalSupplyEpicNFT();
				const totalSupplyUniqueCustom	 = await totalSupplyUniqueNFT();
				const totalSupplyLegendaryCustom = await totalSupplyLegendaryNFT();

				const data = {
					NFT_totalSupply: true,
					totalSupplyNormalCustom: totalSupplyNormalCustom,
					totalSupplyRareCustom: totalSupplyRareCustom,
					totalSupplyEpicCustom: totalSupplyEpicCustom,
					totalSupplyUniqueCustom: totalSupplyUniqueCustom,
					totalSupplyLegendaryCustom: totalSupplyLegendaryCustom,
				};
				
				window.parent.postMessage(data, '*');
			} else if (event.data === 'totalSupplyNormal') {

				const totalSupplyNormalCustom	 = await totalSupplyNormalNFT();

				const data = {
					NFT_totalSupply: true,
					totalSupplyNormalCustom: totalSupplyNormalCustom,
				};
				
				window.parent.postMessage(data, '*');
			}
		}
	});

    
});