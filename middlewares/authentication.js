"use strict"

const Key = require('../models').Key;
const KeyNotFoundError = require('./../errors/KeyNotFoundError');
const KeyMissingError = require('./../errors/KeyMissingError');

var authenticate = (req, res, next) => {
    let token;
    if (req.headers && req.headers.authorization) {
        let parts = req.headers.authorization.split(' ');

        if (parts.length === 2 && parts[0] === "Bearer") {
            token = parts[1]; 
        }
    }

    if (token) {
        // Build a Key object
        Key.findOne({ where: { key: token }}).then(key => {
            if (!key) {
                return Promise.reject(new KeyNotFoundError());
            }
            req.user = key;
            next();
        }).catch( (err) => { next(err)});
    } else {
        next(new KeyMissingError());
    }
}

module.exports = authenticate;