"use strict";
const config = require('./../config/ginger');

const KeysController = {
    getAllKeys: (req, res) => {
        config.ginger.refresh_on_lookup = true;
        console.log(config.ginger.refresh_on_lookup);
        res.send("getAllKeys");
    },
    getKey: (req, res) => {
        res.send("getKey");
    },
    createKey: (req, res) => {
        res.send("createKey");
    },
    deleteKey: (req, res) => {
        res.send("deleteKey");
    },
    refreshKey: (req, res) => {
        res.send("refreshKey");
    },
    editKey: (req, res) => {
        res.send("editKey");
    }
}
module.exports = KeysController;