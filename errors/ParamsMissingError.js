const HttpStatus = require('http-status-codes');

module.exports = class ParamsMissingError extends require('./AppError') {
    constructor (message) {
      // Providing default message and overriding status code.
      super(message || "Un paramètre est manquant!", HttpStatus.BAD_REQUEST);
    }
  };