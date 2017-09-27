const HttpStatus = require('http-status-codes');

module.exports = class UserNotFoundError extends require('./AppError') {
    constructor (message) {
      // Providing default message and overriding status code.
      super(message || "Utilisateur introuvable", HttpStatus.NOT_FOUND);
    }
  };