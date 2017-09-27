const HttpStatus = require('http-status-codes');

module.exports = class UnauthorizedError extends require('./AppError') {
    constructor (message, key, permissions) {
      super(message || "Vous n'avez pas les permissions pour cette opération!", HttpStatus.UNAUTHORIZED);

      // Providing default message and overriding status code.
      this.key = key;

      this.permissions = permissions;
      
    }
  };