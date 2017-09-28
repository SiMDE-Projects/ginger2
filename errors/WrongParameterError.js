const HttpStatus = require('http-status-codes');

module.exports = class WrongParameterError extends require('./AppError') {
    constructor (message) {
      // Providing default message and overriding status code.
      super(message || "Vérifiez le format de vos paramètres", HttpStatus.BAD_REQUEST);
    }
  };