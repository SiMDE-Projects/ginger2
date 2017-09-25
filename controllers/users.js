"use strict";
const UsersService = require('../services/UsersService');
const CotisationsService = require('./../services/CotisationsService');

const ginger = require('./../config/ginger').ginger;
const HttpStatus = require('http-status-codes');

const chain = Promise.resolve();

const UsersController = {
    getUser: (req, res) => {
        if (!req.params.username) {
            res.status(HttpStatus.BAD_REQUEST).send();
            return;
        }
        chain
        .then(() => UsersService.getUser(req.params.username, req.user.permissions))
        .then( (user) => { res.send(user) })
        .catch( (err) => { res.status(HttpStatus.OK).send(err)});
    },
    searchUser: (req, res) => {
        if (!req.params.length) {
            res.status(HttpStatus.BAD_REQUEST).send();
            return;
        }
        chain
        .then( () => UsersService.searchUser(req.query, req.user.permissions))
        .then( (user) => res.status(HttpStatus.OK).send(user))
        .catch( (err) => res.status(HttpStatus.BAD_REQUEST).send(err));
    },
    createUser: (req, res) => {
        chain
        .then( () => UsersService.createUser(req.body))
        .then( (user) => res.status(HttpStatus.NO_CONTENT).send())
        .catch( err => res.status(HttpStatus.NOT_FOUND).send(err));
    },
    deleteUser: (req, res) => {
        if (!req.params.username) {
            res.status(HttpStatus.BAD_REQUEST).send();
            return;
        }
        chain
        .then( () => UsersService.deleteUser(req.params.username))
        .then( () => res.status(HttpStatus.OK).send())
        .catch( (err) => res.status(HttpStatus.NOT_FOUND).send(err))
    },
    editUser: (req, res) => {
        if (!req.params.username) {
            res.status(HttpStatus.BAD_REQUEST).send();
            return;
        }
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
            res.status(HttpStatus.BAD_REQUEST).send();
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
        res.send("getStats");
    },
    searchUsers: (req, res) => {
        if (!req.query.q) {
            res.status(HttpStatus.BAD_REQUEST).send("Paramètre q nécessaire");
            return;
        }
        if (req.query.limit && req.query.limit > ginger.limit_max_search) {
            res.status(HttpStatus.BAD_REQUEST).send("Limite dépassé!");
            return;
        }
        if (req.query.limit && !Number.isInteger(req.query.limit)) {
            res.status(HttpStatus.BAD_REQUEST).send("Limite doit être un nombre");
            return;
        }
        chain
        .then( () => UsersService.searchUsers(req.query.q, req.user.permissions, req.query.limit))
        .then( (users) => { res.status(HttpStatus.OK).send(users)})
    }
}
module.exports = UsersController;