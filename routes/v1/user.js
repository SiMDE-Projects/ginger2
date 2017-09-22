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

router.get('/:username/cotisations', mAuthorize("cotisations_read"), uController.getCotisations);
router.delete('/:username/cotisations/:cotisation', mAuthorize("cotisations_delete"), uController.deleteCotisation);
router.get('/:username/cotisations/last', mAuthorize("cotisations_read"), uController.getLastCotisation);
router.delete('/:username/cotisations/last', mAuthorize("cotisations_delete"), uController.deleteLastCotisation);


module.exports = router;