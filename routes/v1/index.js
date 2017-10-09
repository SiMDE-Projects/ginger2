"use strict"

const express = require('express');
const UsersService = require('./../../services/UsersService');
const HttpStatus = require('http-status-codes');
const chain = Promise.resolve();
const mapUserOldResponse = require('./../../utils/mapUserOldResponse');
let router = express.Router();

router.get('/:username', (req, res, next) => {
    chain
    .then( () => UsersService.getUser(req.params.username, req.user.permissions))
    .then( (user) => { res.status(HttpStatus.OK).send(mapUserOldResponse(user))})
    .catch( err => next(err));
});
router.get('/badge/:card', (req, res, next) => {
    chain
    .then( () => UsersService.searchUser({ badge: req.params.card}, req.user.permissions))
    .then( (user) => res.status(HttpStatus.OK).send(mapUserOldResponse(user)))
    .catch( err => next(err))
});
router.get('/:username/cotisations', (req, res, next) => {});
router.get('/find/:loginpart', (req, res, next) => {});
router.post('/:username/cotisations', (req, res, next) => {});
router.post('/:username/edit', (req, res, next) => {});
router.delete('/cotisations/:cotisation', (req, res, next) => {});

module.exports = router;