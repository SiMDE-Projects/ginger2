"use strict";
const UsersService = require('../services/UsersService');
const ginger = require('./../config/ginger').ginger;
var HttpStatus = require('http-status-codes');

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
        res.send("getCotisations");
    },
    deleteCotisation: (req, res) => {
        res.send("deleteCotisation");
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
        .then( () => { UsersService.searchUsers(req.query.q, req.user.permissions, req.query.limit) })
        .then( (users) => res.status(HttpStatus.OK).send(users))
    }
}
module.exports = UsersController;