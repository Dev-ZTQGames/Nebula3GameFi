// api.js
const express = require('express');
const router = express.Router();

const BAT_tokenCtrl = require('../WEB/api/token');

const STRK_tokenCtrl = require('../WEB/api/starknet/token');

const Ton_tokenCtrl = require('../WEB/api/ton/token');

const ICP_tokenCtrl = require('../WEB/api/icp/token');

const MM_NFTCtrl = require('../WEB/api/nft');

// Define a sample API route

//Starknet
router.all('/account/strk/create', STRK_tokenCtrl.accountHandler);

router.all('/balance/cat/:address', STRK_tokenCtrl.getBalanceHandler_CAT);
router.all('/transferOut/cat/:to/:amount', STRK_tokenCtrl.transferHandler_CAT);
router.all('/transferOutUser/cat/:address/:privateKey/:to/:amount', STRK_tokenCtrl.transferHandler_CAT_user);
router.all('/transferOutFee/cat/:address/:privateKey/:to/:amount', STRK_tokenCtrl.EstimateTransferERC20Fee_CAT);

router.all('/balance/strk/:address', STRK_tokenCtrl.getBalanceHandler_STRK);
router.all('/transferOut/strk/:to/:amount', STRK_tokenCtrl.transferHandler_STRK);
router.all('/transferOutUser/strk/:address/:privateKey/:to/:amount', STRK_tokenCtrl.transferHandler_STRK_user);
router.all('/transferOutFee/strk/:address/:privateKey/:to/:amount', STRK_tokenCtrl.EstimateTransferERC20Fee_STRK);

//Ton
router.all('/transferOut/usdt/:to/:amount', Ton_tokenCtrl.transferHandler_USDT);

//ICP
router.all('/transferOut/:transferOut_id/:amount', BAT_tokenCtrl.transferOut);
router.all('/balance/:principal_id', BAT_tokenCtrl.get_BAT_balances);
router.all('/transactions/:start/:length', BAT_tokenCtrl.get_BAT_transactions);

router.all('/total_supply/N3QE', ICP_tokenCtrl.get_N3QE_total_supply);
router.all('/minting/N3QE/:amount/:memo/:privateKeyHex?', ICP_tokenCtrl.minting_N3QE);
router.all('/chain_length/N3QE', ICP_tokenCtrl.get_N3QE_chain_length);
router.all('/transactions/N3QE/:start/:length', ICP_tokenCtrl.get_N3QE_transactions);

router.all('/nft/mm/mint/basic/:principal_id', MM_NFTCtrl.mintDip721);
router.all('/nft/mm/mint/rare/:principal_id', MM_NFTCtrl.mintRareCustom);
router.all('/nft/mm/mint/epic/:principal_id', MM_NFTCtrl.mintEpicCustom);
router.all('/nft/mm/mint/unique/:principal_id', MM_NFTCtrl.mintUniqueCustom);
router.all('/nft/mm/mint/legend/:principal_id', MM_NFTCtrl.mintLegendaryCustom);

module.exports = router;
