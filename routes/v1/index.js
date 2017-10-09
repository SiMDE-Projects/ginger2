"use strict"

const express = require('express');
const UsersService = require('./../../services/UsersService');
const ContributionsService = require('./../../services/ContributionsService');
const HttpStatus = require('http-status-codes');
const chain = Promise.resolve();
const mapUserOldResponse = require('./../../utils/mapUserOldResponse');
const mapContributionOldResponse = require('./../../utils/mapContributionOldResponse');
const mapSearchUsersOldResponse = require('./../../utils/mapSearchUsersOldResponse');
const mapNewContributionRequest = require('./../../utils/mapNewContributionRequest');
const mapNewUserRequest = require('./../../utils/mapNewUserRequest');

const mAuthorize = require('./../../middlewares/authorize');

let router = express.Router();

router.get('/:username', (req, res, next) => {
    chain
    .then( () => UsersService.getUser(req.params.username, req.user.permissions))
    .then( (user) => { res.status(HttpStatus.OK).send(mapUserOldResponse(user))})
    .catch( err => next(err));
});

router.get('/badge/:card', mAuthorize("users_badge"), (req, res, next) => {
    chain
    .then( () => UsersService.searchUser({ badge: req.params.card}, req.user.permissions))
    .then( (user) => res.status(HttpStatus.OK).send(mapUserOldResponse(user)))
    .catch( err => next(err))
});

router.get('/:username/cotisations', mAuthorize("contributions_read"), (req, res, next) => {
    chain
    .then( () => ContributionsService.getAllContributions(req.params.username, req.user.permissions))
    .then( (contributions) => res.status(HttpStatus.OK).send(mapContributionOldResponse(contributions)))
    .catch( err => next(err))
});

router.get('/find/:loginpart', (req, res, next) => {
    chain
    .then( () => UsersService.searchUsers(req.params.loginpart))
    .then( (users) => res.status(HttpStatus.OK).send(mapSearchUsersOldResponse(users)))
    .catch( err => next(err))
});

router.post('/:username/cotisations', mAuthorize("contributions_add"), (req, res, next) => {
    chain
    .then( () => ContributionsService.addContribution(req.params.username, mapNewContributionRequest(req.body)))
    .then( () => res.status(HttpStatus.NO_CONTENT).send())
    .catch( err => next(err))
});

router.post('/:username/edit', mAuthorize("users_edit"), (req, res, next) => {
    chain
    .then( () => UsersService.editUser(req.params.username, mapNewUserRequest(req.body)))
    .then( () => res.status(HttpStatus.NO_CONTENT).send())
    .catch( err => next(err))
});

router.delete('/cotisations/:cotisation', mAuthorize("contributions_delete"), (req, res, next) => {
    chain
    .then( () => ContributionsService.deleteContribution(req.params.cotisation))
    .then( () => res.status(HttpStatus.NO_CONTENT).send())
    .catch( err => next(err))
});

module.exports = router;