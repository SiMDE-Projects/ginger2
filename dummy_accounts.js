"use strict"

const express = require('express');
const app = express();
const dummyData = [
    {
        "username":"jennypau",
        "firstName":"Paul",
        "lastName":"POULETTE",
        "mail":"paul.jnny@iutc.fr",
        "profile":"ETU UTC",
        "cardSerialNumber":"819335fA",
        "cardStartDate":1441576800000,
        "cardEndDate":null,
        "legalAge":true
    },
    {
        "username":"cerihar",
        "firstName":"César",
        "lastName":"POULET",
        "mail":"ceriar@etu.utc.fr",
        "profile":"ETU UTC",
        "cardSerialNumber":"819335f1",
        "cardStartDate":1441576800000,
        "cardEndDate":null,
        "legalAge":false        
    }
];

app.get('/ws-picasso/getUserInfo', (req, res) => {
    let user = dummyData.find( (el) => el.username === req.query.username);
    if (user) {
        res.send(user);
    } else {
        res.status(404).send();
    }
});

app.get('/ws-picasso/cardLookup', (req, res) => {
    let user = dummyData.find( (el) => el.cardSerialNumber === req.query.cardSerialNumber);
    if (user) {
        res.send(user);
    } else {
        res.status(404).send();
    }
});

app.listen(3001, function () {
      console.log("Dummy Accounts Server running on 3001");
});