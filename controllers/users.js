"use strict";
const UsersService = require('../services/UsersService');
const chain = Promise.resolve();

const UsersController = {
    getUser: (req, res) => {
        chain
        .then(() => UsersService.getUser(req.params.username, req.user.permissions))
        .then( (user) => { res.send(user) })
        .catch( (err) => { res.status(404).send(err)});
    },
    createUser: (req, res) => {
        chain
        .then( () => UsersService.createUser(req.body))
        .then( (user) => res.status(204).send())
        .catch( err => res.status(400).send(err));
    },
    deleteUser: (req, res) => {
        chain
        .then( () => UsersService.deleteUser(req.params.username))
        .then( () => res.status(204).send())
        .catch( (err) => res.status(404).send(err))
    },
    editUser: (req, res) => {
        chain
        .then( () => UsersService.editUser(req.params.username, req.body))
        .then( () => res.status(204).send())
        .catch( (err) => res.status(404).send(err))
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
        res.send("searchUsers");
    }
}
module.exports = UsersController;