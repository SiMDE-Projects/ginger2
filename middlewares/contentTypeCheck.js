const WrongContentTypeError = require('./../errors/WrongContentTypeError');

module.exports = (req, res, next) => {
    if ((req.method == "POST" || req.method == "PATCH") && !req.is('application/json')) {
        next(new WrongContentTypeError());
    } else {
        next();
    }
}