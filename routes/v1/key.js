"use strict"

const express = require('express');
const path = require('path');

const kController = require('./../../controllers/keys');
const mAuthorize = require('./../../middlewares/authorize');

let router = express.Router();

router.get('/', mAuthorize('keys_all'), kController.getAllKeys);
router.post('/', mAuthorize('keys_all'), kController.createKey);

router.get('/:id', mAuthorize('keys_all'), kController.getKey);
router.delete('/:id', mAuthorize('keys_all'), kController.deleteKey);
router.post('/:id', mAuthorize('keys_all'), kController.refreshKey);
router.patch('/:id', mAuthorize('keys_all'), kController.editKey);

module.exports = router;