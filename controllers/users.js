"use strict";

const UsersController = {
    getUser: (req, res) => {
        res.send(req.params.username);
    },
    createUser: (req, res) => {
        res.send("createUser");
    },
    deleteUser: (req, res) => {
        res.send("deleteUser");
    },
    editUser: (req, res) => {
        res.send("editUser");
    },
    getCotisations: (req, res) => {
        res.send("getCotisations");
    },
    deleteCotisation: (req, res) => {
        res.send("deleteCotisation");
    },
    getLastCotisation: (req, res) => {
        res.send("getLastCotisation");
    },
    deleteLastCotisation: (req, res) => {
        res.send("deleteLastCotisation");
    },
    getStats: (req, res) => {
        res.send("getStats");
    },
    searchUsers: (req, res) => {
        res.send("searchUsers");
    }
}
module.exports = UsersController;