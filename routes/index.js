"use strict"

const express = require('express');
const path = require('path');

const v2 = require('./v2');

let router = express.Router();

router.use('/v2', v2);

module.exports = router;