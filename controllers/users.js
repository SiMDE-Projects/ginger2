"use strict";
const UsersService = require('../services/UsersService');
const CotisationsService = require('./../services/CotisationsService');

const ginger = require('./../config/ginger').ginger;
const HttpStatus = require('http-status-codes');
const ParamsMissingError = require('./../errors/ParamsMissingError');
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

        if (!req.params.length) {
            let e = new ParamsMissingError();
            res.status(e.status).send(e);
            return;
        }

        // If user is not authorized to search by badge
        if (req.params.badge && !req.user.permissions.includes("users_badge")) {
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
        .catch( (err) => res.status(HttpStatus.NOT_FOUND).send(err))
    },
    getCotisations: (req, res) => {
        chain
        .then( () => CotisationsService.getAllCotisations(req.params.username, req.user.permissions))
        .then( (cotisations) => res.status(HttpStatus.OK).send(cotisations))
        .catch( (err) => res.status(HttpStatus.NOT_FOUND).send(err))
    },
    getCotisation: (req, res) => {
        chain
        .then( () => CotisationsService.getCotisation(req.params.username, req.params.cotisation, req.user.permissions))
        .then( cotisation => res.status(HttpStatus.OK).send(cotisation))
        .catch( err => res.status(HttpStatus.BAD_REQUEST).send(err))
    },
    deleteCotisation: (req, res) => {
        chain
        .then( () => CotisationsService.deleteCotisation(req.params.cotisation))
        .then( () => res.status(HttpStatus.NO_CONTENT).send())
        .catch( err => res.status(HttpStatus.NOT_FOUND).send(err))
    },
    addCotisation: (req, res) => {
        if (!req.body) {
            let e = new ParamsMissingError();
            res.status(e.status).send(e);
            return;
        }
        chain
        .then( () => CotisationsService.addCotisation(req.params.username, req.body))
        .then( () => res.status(HttpStatus.NO_CONTENT).send())
        .catch( err => res.status(HttpStatus.BAD_REQUEST).send(err))
    },
    getLastCotisation: (req, res) => {
        res.send("getLastCotisation");
    },
    deleteLastCotisation: (req, res) => {
        res.send("deleteLastCotisation");
    },
    getStats: (req, res) => {
        chain
        .then( () => UsersService.getStats())
        .then( (stats) => res.status(HttpStatus.OK).send(stats))
        .catch( err => res.status(HttpStatus.BAD_REQUEST).send(err));
    },
    searchUsers: (req, res) => {
        if (!req.query.q) {
            let e = new ParamsMissingError("Paramètre q nécessaire");
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