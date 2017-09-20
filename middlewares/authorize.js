"use strict"
//TBD
// From: https://github.com/lodash/lodash/issues/1743#issue-125967660
function arrayContainsArray (superset, subset) {
    if (0 === subset.length) {
      return false;
    }
    return subset.every(function (value) {
      return (superset.indexOf(value) >= 0);
    });
  }

module.exports =  (...allowed) => {
    return (req, res, next) => {
        if (req.user && arrayContainsArray(req.user.permissions, allowed)) {
            next();
        }
        else {
            res.status(403).send("Vous n'avez pas les permissions");
        }
    }
}