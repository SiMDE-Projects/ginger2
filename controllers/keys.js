"use strict";
const config = require('./../config/ginger');
const KeysService = require('./../services/KeysService');
const chain = Promise.resolve();
const HttpStatus = require('http-status-codes');

const KeysController = {
    getAllKeys: (req, res) => {
        chain
        .then( () =>  KeysService.getAllKeys(req.query.login))
        .then( (keys) => res.status(HttpStatus.OK).send(keys))
        .catch( (err) => res.status(HttpStatus.METHOD_FAILURE).send(err));
    },
    getKey: (req, res) => {
        chain
        .then( () => KeysService.getKey(req.params.id))
        .then( (key) => res.status(HttpStatus.OK).send(key))
        .catch( (err) => res.status(HttpStatus.BAD_REQUEST).send(err))
    },
    createKey: (req, res) => {
        res.send("createKey");
    },
    deleteKey: (req, res) => {
        chain
        .then( () => KeysService.deleteKey(req.params.id))
        .then( () => res.status(HttpStatus.OK).send())
        .catch( (err) => res.status(HttpStatus.NOT_FOUND).send(err))
    },
    refreshKey: (req, res) => {
        chain
        .then( () => KeysService.refreshKey(req.params.id))
        .then( (key) => res.status(HttpStatus.OK).send(key))
        .catch( (err) => res.status(HttpStatus.NOT_FOUND).send(err))
    },
    editKey: (req, res) => {
        res.send("editKey");
    }
}
module.exports = KeysController;