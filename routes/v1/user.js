"use strict"

const express = require('express');
const path = require('path');

const uController = require('./../../controllers/users');
const mAuthorize = require('./../../middlewares/authorize');

let router = express.Router();

router.get('/', uController.searchUser);
router.post('/', mAuthorize("users_add"), uController.createUser);

router.get('/stats', mAuthorize("stats_read"), uController.getStats);
router.get('/search', uController.searchUsers);

router.get('/:username', uController.getUser);
router.delete('/:username', mAuthorize("users_delete"), uController.deleteUser);
router.patch('/:username', mAuthorize("users_edit"), uController.editUser);

router.get('/:username/contributions', mAuthorize("contributions_read"), uController.getContributions);
router.post('/:username/contributions', mAuthorize("contributions_add"), uController.addContribution);
router.get('/:username/contributions/:contribution', mAuthorize("contributions_read"), uController.getContribution);
router.delete('/:username/contributions/:contribution', mAuthorize("contributions_delete"), uController.deleteContribution);
router.get('/:username/contributions/last', mAuthorize("contributions_read"), uController.getLastContribution);
router.delete('/:username/contributions/last', mAuthorize("contributions_delete"), uController.deleteLastContribution);


module.exports = router;