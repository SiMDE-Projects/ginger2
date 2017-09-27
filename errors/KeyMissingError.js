const HttpStatus = require('http-status-codes');

module.exports = class KeyMissingError extends require('./AppError') {
    constructor (message) {
      // Providing default message and overriding status code.
      super(message || "Clé d'authentification manquante!", HttpStatus.BAD_REQUEST);
    }
  };