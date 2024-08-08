const express = require("express");
const path = require('path');
const https = require('https');
const fs = require('fs');

const app = express();

var options = {
  key: fs.readFileSync('/ssl_nebula3gamefi/20240608/privkey1.pem'),
  cert: fs.readFileSync('/ssl_nebula3gamefi/20240608/fullchain1.pem')
};

const hostname = '0.0.0.0';
const port = 8080;

// Import routes
const apiRouter = require('./routes/api');
const indexRouter = require('./routes/index');

// Use routes
app.use('/api', apiRouter);
app.use('/', indexRouter);

https.createServer(options, app).listen(port, hostname, () => {
	console.log(`Server running at https://0.0.0.0:${port}/`); 
});
