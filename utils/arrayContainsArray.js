"use strict"
// From: https://github.com/lodash/lodash/issues/1743#issue-125967660
module.exports = (superset, subset) => {
    if (0 === subset.length) {
      return false;
    }
    return subset.every(function (value) {
      return (superset.indexOf(value) >= 0);
    });
}