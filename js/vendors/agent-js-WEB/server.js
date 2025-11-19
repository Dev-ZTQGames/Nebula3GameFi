const express = require("express");
const path = require('path');
const https = require('https');
const fs = require('fs');

const app = express();

app.set('trust proxy', true);

var options = {
  key: fs.readFileSync('/ssl_nebula3gamefi/20250805/privkey8.pem'),
  cert: fs.readFileSync('/ssl_nebula3gamefi/20250805/fullchain8.pem')
};

const hostname = '0.0.0.0';
const port = 8080;
/*
const apiLimiter = rateLimit({
  windowMs: 60 * 1000, // 1 min
  max: 60, // 20 request per min
  message: { status: 'error', message: 'Too many requests, please try again later.' }
});
*/
// Import routes
const apiRouter = require('./routes/api');
const indexRouter = require('./routes/index');

const allowedIPs = ['34.80.134.81', '35.229.138.233', '104.199.216.196', '34.81.111.209', '210.59.144.44', '210.59.144.41', '34.81.111.209', '34.81.89.114', '34.64.222.56' , '35.194.40.125', '35.194.217.14', '::1', '127.0.0.1']; // 改成你允許的 IP

// IP middleware
function ipFilter(req, res, next) {
  const ip = req.ip || req.connection.remoteAddress;
  const cleanIp = ip.startsWith('::ffff:') ? ip.substring(7) : ip;

  if (allowedIPs.includes(cleanIp)) {
    next();
  } else {
    res.status(403).json({status: 'error', message: 'Forbidden: Your IP is not allowed' + cleanIp});
  }
}

// Use routes
//app.use('/api', ipFilter, apiLimiter, apiRouter);
app.use('/api', ipFilter, apiRouter);
app.use('/', indexRouter);

https.createServer(options, app).listen(port, hostname, () => {
	console.log(`Server running at https://0.0.0.0:${port}/`); 
});
