"use strict";
const config = require('./../config/ginger');

const SettingsController = {
    getAllSettings: (req, res) => {
        console.log(config);
        res.send("getAllSettings");
    },
    editSettings: (req, res) => {
        res.send("editSettings");
    }
}
module.exports = SettingsController;