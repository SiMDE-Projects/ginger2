"use strict"

const express = require('express');
const path = require('path');

const v1 = require('./v1');

let router = express.Router();

router.use('/v1', v1);

module.exports = router;