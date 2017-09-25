"use strict"

const express = require('express');
const app = express();
const db = require('./models');
const routes = require('./routes/');
const config = require('./config/ginger');
const authMiddleware = require('./middlewares/authentication');
const bodyParser = require('body-parser');

app.use(authMiddleware);
app.use(bodyParser.json());

app.disable('x-powered-by');

app.use('/', routes);

app.listen(3000, function () {
  db.sequelize.sync({force: false}).then( () => {
    console.log("Server listening on 3000");
  })
});


// TO DO: Ajouter plain: true dans les requêtes Sequelize pour ne pas avoir les champs inutiles de Sequelize
// TO DO: Gérer les erreurs de Sequelize. Construire un objet { err: "Message d'erreur", code: HttpStatus.NOT_FOUND } dans les services