"use strict"

const Key = require('../models').Key;

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
                return Promise.reject("La clé n'existe pas");
            }
            req.user = key;
            next();
        }).catch( (err) => {res.status(401).send(err)});
    } else {
        res.status(401).send();
    }
}

module.exports = authenticate;