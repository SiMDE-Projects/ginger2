"use strict";
const SettingsService = require('./../services/SettingsService');
const HttpStatus = require('http-status-codes')
const chain = Promise.resolve();

const SettingsController = {
    getAllSettings: (req, res) => {
        chain
        .then( () => SettingsService.getAllSettings())
        .then( (settings) => res.status(HttpStatus.OK).send(settings))
    },
    editSettings: (req, res) => {
        chain
        .then( () => SettingsService.editSettings(req.query))
        .then( () => res.status(HttpStatus.NO_CONTENT).send())
        .catch( () => res.status(HttpStatus.BAD_REQUEST).send())
    }
}
module.exports = SettingsController;