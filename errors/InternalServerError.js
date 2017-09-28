const HttpStatus = require('http-status-codes');

module.exports = class InternalServerError extends require('./AppError') {
    constructor (message) {
      // Providing default message and overriding status code.
      super(message || "Erreur interne....", HttpStatus.INTERNAL_SERVER_ERROR);
    }
  };