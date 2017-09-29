"use strict";
const config = require('./../config/ginger');
const KeysService = require('./../services/KeysService');
const chain = Promise.resolve();
const HttpStatus = require('http-status-codes');

const MissingParamError = require('./../errors/MissingParamError');

const KeysController = {
    getAllKeys: (req, res, next) => {
        chain
        .then( () =>  KeysService.getAllKeys(req.query.login))
        .then( (keys) => res.status(HttpStatus.OK).send(keys))
        .catch( (err) => next(err));
    },
    getKey: (req, res, next) => {
        chain
        .then( () => KeysService.getKey(req.params.id))
        .then( (key) => res.status(HttpStatus.OK).send(key))
        .catch( (err) => res.status(HttpStatus.BAD_REQUEST).send(err))
    },
    createKey: (req, res, next) => {
        if (!Object.keys(req.body).length) {
            next(new MissingParamError());
            return;
        }
        chain
        .then( () => KeysService.createKey(req.body))
        .then( (key) => res.status(HttpStatus.CREATED).send(key))
        .catch( (err) => res.status(HttpStatus.BAD_REQUEST).send(err))
    },
    deleteKey: (req, res, next) => {
        chain
        .then( () => KeysService.deleteKey(req.params.id))
        .then( () => res.status(HttpStatus.OK).send())
        .catch( (err) => res.status(HttpStatus.NOT_FOUND).send(err))
    },
    refreshKey: (req, res, next) => {
        chain
        .then( () => KeysService.refreshKey(req.params.id))
        .then( (key) => res.status(HttpStatus.OK).send(key))
        .catch( (err) => res.status(HttpStatus.NOT_FOUND).send(err))
    },
    editKey: (req, res, next) => {
        if (!Object.keys(req.body).length) {
            next(new MissingParamError());
            return;
        }
        chain
        .then( () => KeysService.editKey(req.params.id, req.body))
        .then( () => res.status(HttpStatus.NO_CONTENT).send())
        .catch( (err) => res.status(HttpStatus.BAD_REQUEST).send(err))
    }
}
module.exports = KeysController;