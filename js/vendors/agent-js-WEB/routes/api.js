// api.js
const express = require('express');
const router = express.Router();

const BAT_tokenCtrl = require('../WEB/api/token');

const MM_NFTCtrl = require('../WEB/api/nft');

// Define a sample API route
router.all('/transferOut/:transferOut_id/:amount', BAT_tokenCtrl.transferOut);
router.all('/balance/:principal_id', BAT_tokenCtrl.get_BAT_balances);
router.all('/transactions/:start/:length', BAT_tokenCtrl.get_BAT_transactions);

router.all('/nft/mm/mint/basic/:principal_id', MM_NFTCtrl.mintDip721);
router.all('/nft/mm/mint/rare/:principal_id', MM_NFTCtrl.mintRareCustom);
router.all('/nft/mm/mint/epic/:principal_id', MM_NFTCtrl.mintEpicCustom);
router.all('/nft/mm/mint/unique/:principal_id', MM_NFTCtrl.mintUniqueCustom);
router.all('/nft/mm/mint/legend/:principal_id', MM_NFTCtrl.mintLegendaryCustom);

module.exports = router;
