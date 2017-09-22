"use strict"

const express = require('express');
const app = express();
const db = require('./models');
const routes = require('./routes/');
const config = require('./config/ginger');
const authMiddleware = require('./middlewares/authentication');
const bodyParser = require('body-parser');

app.use(bodyParser.json());
app.use(authMiddleware);


app.disable('x-powered-by');

app.use('/', routes);

app.listen(3000, function () {
  db.sequelize.sync({force: false}).then( () => {
    console.log("Server listening on 3000");
  })
});