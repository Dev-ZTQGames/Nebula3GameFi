// index.js
const express = require('express');
const path = require('path');

const router = express.Router();

const indexPath = path.join(__dirname, '..', 'WEB', 'dist', 'index.html');

// Serve static files
router.use(express.static(path.join(__dirname, '..', 'WEB', 'dist')));

// Serve the index.html for all other routes
router.all('*', (req, res) => {
    res.sendFile(indexPath);
});

module.exports = router;
