"use strict";
const SettingsService = require('./../services/SettingsService');
const HttpStatus = require('http-status-codes')
const chain = Promise.resolve();

const MissingParamError = require('./../errors/MissingParamError');

const SettingsController = {
    getAllSettings: (req, res, next) => {
        chain
        .then( () => SettingsService.getAllSettings())
        .then( (settings) => res.status(HttpStatus.OK).send(settings))
        .catch( err => next(err));
    },
    editSettings: (req, res, next) => {
        if (!Object.keys(req.body).length) {
            next(new MissingParamError());
            return;
        }
        chain
        .then( () => SettingsService.editSettings(req.body))
        .then( (config) => res.status(HttpStatus.OK).send(config))
        .catch( (err) => next(err));
    }
}
module.exports = SettingsController;