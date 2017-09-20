"use strict"

const express = require('express');
const app = express();
const db = require('./models');
const routes = require('./routes/');
const config = require('./config/ginger');
const authMiddleware = require('./middlewares/authentication');

app.use(authMiddleware);
app.use('/', routes);

app.listen(3000, function () {
  db.sequelize.sync({force: false}).then( () => {
    console.log("Server listening on 3000");
  })
});