const HttpStatus = require('http-status-codes');

module.exports = class ContributionNotFoundError extends require('./AppError') {
    constructor (message) {
      // Providing default message and overriding status code.
      super(message || "Cotisation introuvable", HttpStatus.NOT_FOUND);
    }
  };