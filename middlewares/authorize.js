"use strict"
const arrayContainsArray = require('./../utils/arrayContainsArray');
const UnauthorizedError = require('./../errors/UnauthorizedError');

module.exports =  (...allowed) => {
    return (req, res, next) => {
        if (req.user && arrayContainsArray(req.user.permissions, allowed)) {
            next();
        }
        else {
            next(new UnauthorizedError(null, req.user.key, allowed));
        }
    }
}