"use strict"

const express = require('express');
const path = require('path');

const sController = require('./../../controllers/settings');
const mAuthorize = require('./../../middlewares/authorize');

let router = express.Router();

router.get('/', mAuthorize("settings_read"), sController.getAllSettings);
router.patch('/', mAuthorize("settings_read"), sController.editSettings);

module.exports = router;