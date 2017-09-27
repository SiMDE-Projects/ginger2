const HttpStatus = require('http-status-codes');

module.exports = class KeyNotFoundError extends require('./AppError') {
    constructor (message) {
      // Providing default message and overriding status code.
      super(message || "Clé introuvable", HttpStatus.NOT_FOUND);
    }
  };