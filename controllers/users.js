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
    getUser: (req, res) => {
        chain
        .then(() => UsersService.getUser(req.params.username, req.user.permissions))
        .then( (user) => { res.send(user) })
        .catch( (err) => { res.status(err.status).send(err)});
    },
    searchUser: (req, res) => {
        if (!Object.keys(req.query).length) {
            let e = new MissingParamError();
            res.status(e.status).send(e);
            return;
        }

        // If user is not authorized to search by badge
        if (req.query.badge && !req.user.permissions.includes("users_badge")) {
            let e = new UnauthorizedError("Vous n'avez pas la permission de rechercher par badge");
            res.status(e.status).send(e);
            return;
        }

        chain
        .then( () => UsersService.searchUser(req.query, req.user.permissions))
        .then( (user) => res.status(HttpStatus.OK).send(user))
        .catch( (err) => res.status(err.status).send(err));
    },
    createUser: (req, res) => {
        chain
        .then( () => UsersService.createUser(req.body))
        .then( (user) => res.status(HttpStatus.NO_CONTENT).send())
        .catch( err => res.status(HttpStatus.NOT_FOUND).send(err));
    },
    deleteUser: (req, res) => {
        chain
        .then( () => UsersService.deleteUser(req.params.username))
        .then( () => res.status(HttpStatus.OK).send())
        .catch( (err) => { res.status(err.status).send(err)})
    },
    editUser: (req, res) => {      
        chain
        .then( () => UsersService.editUser(req.params.username, req.body))
        .then( () => res.status(HttpStatus.NO_CONTENT).send())
        .catch( (err) => res.status(err.status).send(err))
    },
    getContributions: (req, res) => {
        chain
        .then( () => ContributionsService.getAllContributions(req.params.username, req.user.permissions))
        .then( (contributions) => res.status(HttpStatus.OK).send(contributions))
        .catch( (err) => res.status(err.status).send(err))
    },
    getContribution: (req, res) => {
        chain
        .then( () => ContributionsService.getContribution(req.params.username, req.params.contribution, req.user.permissions))
        .then( contribution => res.status(HttpStatus.OK).send(contribution))
        .catch( err => res.status(err.status).send(err))
    },
    deleteContribution: (req, res) => {
        chain
        .then( () => ContributionsService.deleteContribution(req.params.contribution))
        .then( () => res.status(HttpStatus.NO_CONTENT).send())
        .catch( err => res.status(err.status).send(err))
    },
    addContribution: (req, res) => {
        if (!Object.keys(req.body).length) {
            let e = new MissingParamError();
            res.status(e.status).send(e);
            return;
        }
        chain
        .then( () => ContributionsService.addContribution(req.params.username, req.body))
        .then( () => res.status(HttpStatus.NO_CONTENT).send())
        .catch( err => res.status(HttpStatus.BAD_REQUEST).send(err))
    },
    getLastContribution: (req, res) => {
        res.send("getLastContribution");
    },
    deleteLastContribution: (req, res) => {
        res.send("deleteLastContribution");
    },
    getStats: (req, res) => {
        chain
        .then( () => UsersService.getStats())
        .then( (stats) => res.status(HttpStatus.OK).send(stats))
        .catch( err => res.status(HttpStatus.BAD_REQUEST).send(err));
    },
    searchUsers: (req, res) => {
        if (!req.query.q) {
            let e = new MissingParamError("Paramètre q nécessaire");
            res.status(e.status).send(e);
            return;
        }
        if (req.query.limit && req.query.limit > ginger.limit_max_search) {
            let e = new WrongParameterError("Limite dépassé! Maximum " + ginger.limit_max_search);
            res.status(e.status).send(e);
            return;
        }
        if (req.query.limit && !Number.isInteger(req.query.limit)) {
            let e = new WrongParameterError("limit doit être un nombre");
            res.status(e.status).send(e);
            return;
        }
        chain
        .then( () => UsersService.searchUsers(req.query.q, req.user.permissions, req.query.limit))
        .then( (users) => { res.status(HttpStatus.OK).send(users)})
    }
}
module.exports = UsersController;