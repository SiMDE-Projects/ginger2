"use strict";
const StatsService = require('./../services/StatsService');
const chain = Promise.resolve();

const StatsController = {
    getAllStats: (req, res) => {
        chain
        .then( () => StatsService.getAllStats())
        .then( (stats) => res.send(stats))
    }
}
module.exports = StatsController;



