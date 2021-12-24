# Ginger V2 [![Tests](https://github.com/simde-utc/ginger2/actions/workflows/test.yml/badge.svg)](https://github.com/simde-utc/ginger2/actions/workflows/test.yml) [![Coverage Status](https://coveralls.io/repos/github/simde-utc/ginger2/badge.svg)](https://coveralls.io/github/simde-utc/ginger2)

- Ginger : `http://localhost:8080`
- Adminer : `http://localhost:8081`

Brique logicielle d'identification des étudiants par login / numéro de badge et de gestion des cotisations

## Installation en local

Faire un `docker-compose build` puis déplacer le fichier `config/env.example.php` vers `config/env.php` pour préparer le fs.

Lancer l'installation des dependences via `docker-compose run --rm ginger2 composer install` puis les migrations de la base de données via `docker-compose run --rm ginger2 vendor/bin/phoenix migrate` 

## Utilisation
Executer `docker-compose up` pour lancer le serveur (le login de test est `testlogin` et l'appkey de test est `validAppKey`)

## Tests

Les tests sont executables via `docker-compose run --rm ginger2 composer test`

