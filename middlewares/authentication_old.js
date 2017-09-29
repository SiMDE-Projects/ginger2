"use strict"

const Key = require('../models').Key;
const KeyNotFoundError = require('./../errors/KeyNotFoundError');
const KeyMissingError = require('./../errors/KeyMissingError');

var authenticate = (req, res, next) => {
    let token;
    if (req.query && req.query.key) {
        token = req.query.key;
    }

    if (token) {
        // Build a Key object
        Key.findOne({ where: { key: token }}).then(key => {
            if (!key) {
                return Promise.reject(new KeyNotFoundError());
            }
            req.user = key;
            // On supprime la clé de la requête pour simplifier la vie après
            delete req.query.key;
            next();
        }).catch( (err) => { next(err)});
    } else {
        next(new KeyMissingError());
    }
}

module.exports = authenticate;