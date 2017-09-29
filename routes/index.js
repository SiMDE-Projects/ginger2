"use strict"

const express = require('express');
const path = require('path');

const v1 = require('./v1');
const v2 = require('./v2');

const authMiddleware = require('./../middlewares/authentication');
const authOldMiddleware = require('./../middlewares/authentication_old');
let router = express.Router();

router.use('/v1', authOldMiddleware, v1);
router.use('/v2', authMiddleware, v2);

module.exports = router;