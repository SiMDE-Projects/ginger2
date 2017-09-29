"use strict"

const express = require('express');
const app = express();
const db = require('./models');
const routes = require('./routes/');
const config = require('./config/ginger');
const bodyParser = require('body-parser');

app.use('/apidoc', express.static('apidoc'));


app.use(bodyParser.json());

app.disable('x-powered-by');

app.use('/', routes);

app.use( (err, req, res, next) => {
  if (res.headersSent) {
    return next(err)
  }

  // Si l'erreur n'a pas de statut, elle n'était pas prévue
  if (!err.status) {
    err.status = 500;
  }

  if (err.status > 400) {
    if (err.status >= 500) {
      // On envoie un mail avec la stacktrace pour les erreurs "graves"
    }
    // On log ça dans un fichier spécial pour consultation humaine
  }
  res.status(err.status)
  res.send(err);
});


app.listen(3000, function () {
  db.sequelize.sync({force: false}).then( () => {
    console.log("Server listening on 3000");
  })
});