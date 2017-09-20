"use strict"

const express = require('express');
const path = require('path');

const sController = require('./../../controllers/stats');
const mAuthorize = require('./../../middlewares/authorize');

let router = express.Router();

router.get('/', mAuthorize("stats_read"), sController.getAllStats);

module.exports = router;