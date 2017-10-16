const HttpStatus = require('http-status-codes');

module.exports = class WrongContentType extends require('./AppError') {
    constructor (message) {
      // Providing default message and overriding status code.
      super(message || "Vérifiez que l'en-tête 'Content-Type' est 'application/json'", HttpStatus.UNSUPPORTED_MEDIA_TYPE);
    }
  };