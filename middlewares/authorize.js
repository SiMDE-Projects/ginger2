"use strict"
const arrayContainsArray = require('./../utils/arrayContainsArray');

module.exports =  (...allowed) => {
    return (req, res, next) => {
        if (req.user && arrayContainsArray(req.user.permissions, allowed)) {
            next();
        }
        else {
            res.status(403).send("Vous n'avez pas les permissions");
        }
    }
}