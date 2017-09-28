"use strict";
const SettingsService = require('./../services/SettingsService');
const HttpStatus = require('http-status-codes')
const chain = Promise.resolve();

const MissingParamError = require('./../errors/MissingParamError');

const SettingsController = {
    getAllSettings: (req, res) => {
        chain
        .then( () => SettingsService.getAllSettings())
        .then( (settings) => res.status(HttpStatus.OK).send(settings))
    },
    editSettings: (req, res) => {
        if (!Object.keys(req.body).length) {
            let e = new MissingParamError();
            res.status(e.status).send(e);
            return;
        }
        chain
        .then( () => SettingsService.editSettings(req.body))
        .then( () => res.status(HttpStatus.NO_CONTENT).send())
        .catch( () => res.status(HttpStatus.BAD_REQUEST).send())
    }
}
module.exports = SettingsController;