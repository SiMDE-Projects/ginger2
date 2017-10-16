"use strict";
const UsersService = require('../services/UsersService');
const ContributionsService = require('./../services/ContributionsService');

const ginger = require('./../config/ginger').ginger;
const HttpStatus = require('http-status-codes');
const MissingParamError = require('./../errors/MissingParamError');
const UnauthorizedError = require('./../errors/UnauthorizedError');
const WrongParameterError = require('./../errors/WrongParameterError');

const chain = Promise.resolve();

const UsersController = {
    getUser: (req, res, next) => {
        chain
        .then(() => UsersService.getUser(req.params.username, req.user.permissions))
        .then( (user) => { res.send(user) })
        .catch( (err) => { next(err)});
    },
    searchUser: (req, res, next) => {
        if (!Object.keys(req.query).length) {
            next(new MissingParamError());
            return;
        }

        // If user is not authorized to search by badge
        if (req.query.badge && !req.user.permissions.includes("users_badge")) {
            next(new UnauthorizedError("Vous n'avez pas la permission de rechercher par badge"));
            return;
        }

        chain
        .then( () => UsersService.searchUser(req.query, req.user.permissions))
        .then( (user) => res.status(HttpStatus.OK).send(user))
        .catch( (err) => next(err));
    },
    createUser: (req, res, next) => {
        chain
        .then( () => UsersService.createUser(req.body))
        .then( (user) => res.status(HttpStatus.NO_CONTENT).send())
        .catch( err => next(err));
    },
    deleteUser: (req, res, next) => {
        chain
        .then( () => UsersService.deleteUser(req.params.username))
        .then( () => res.status(HttpStatus.OK).send())
        .catch( (err) => { next(err)})
    },
    editUser: (req, res, next) => {
        if (!Object.keys(req.body).length) {
            next(new MissingParamError());
            return;
        }
         
        chain
        .then( () => UsersService.editUser(req.params.username, req.body))
        .then( () => res.status(HttpStatus.NO_CONTENT).send())
        .catch( (err) => next(err))
    },
    getContributions: (req, res, next) => {
        chain
        .then( () => ContributionsService.getAllContributions(req.params.username, req.user.permissions))
        .then( (contributions) => res.status(HttpStatus.OK).send(contributions))
        .catch( (err) => next(err))
    },
    getContribution: (req, res, next) => {
        chain
        .then( () => ContributionsService.getContribution(req.params.username, req.params.contribution, req.user.permissions))
        .then( contribution => res.status(HttpStatus.OK).send(contribution))
        .catch( err => next(err))
    },
    deleteContribution: (req, res, next) => {
        chain
        .then( () => ContributionsService.deleteContribution(req.params.contribution))
        .then( () => res.status(HttpStatus.NO_CONTENT).send())
        .catch( err => next(err))
    },
    addContribution: (req, res, next) => {
        if (!Object.keys(req.body).length) {
            next(new MissingParamError());
            return;
        }
        chain
        .then( () => ContributionsService.addContribution(req.params.username, req.body))
        .then( () => res.status(HttpStatus.NO_CONTENT).send())
        .catch( err => next(err))
    },
    getLastContribution: (req, res, next) => {
        res.send("getLastContribution");
    },
    deleteLastContribution: (req, res, next) => {
        res.send("deleteLastContribution");
    },
    getStats: (req, res, next) => {
        chain
        .then( () => UsersService.getStats())
        .then( (stats) => res.status(HttpStatus.OK).send(stats))
        .catch( err => next(err));
    },
    searchUsers: (req, res, next) => {
        if (!req.query.q) {
            next(new MissingParamError("Paramètre q nécessaire"));
            return;
        }
        if (req.query.limit && req.query.limit > ginger.limit_max_search) {
            next(new WrongParameterError("Limite dépassé! Maximum " + ginger.limit_max_search));
            return;
        }
        if (req.query.limit && !Number.isInteger(req.query.limit)) {
            next(new WrongParameterError("limit doit être un nombre"));
            return;
        }
        chain
        .then( () => UsersService.searchUsers(req.query.q, req.user.permissions, req.query.limit))
        .then( (users) => { res.status(HttpStatus.OK).send(users)})
        .catch( err => next(err));
    }
}
module.exports = UsersController;