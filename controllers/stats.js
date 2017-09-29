"use strict";
const StatsService = require('./../services/StatsService');
const chain = Promise.resolve();

const StatsController = {
    getAllStats: (req, res, next) => {
        chain
        .then( () => StatsService.getAllStats())
        .then( (stats) => res.send(stats))
        .catch( err => next(err))
    }
}
module.exports = StatsController;



