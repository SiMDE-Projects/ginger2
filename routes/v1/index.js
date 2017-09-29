"use strict"

const express = require('express');

let router = express.Router();

router.get('/:username', (req, res, next) => { console.log(req.query)});
router.get('/badge/:card', (req, res, next) => {});
router.get('/:username/cotisations', (req, res, next) => {});
router.get('/find/:loginpart', (req, res, next) => {});
router.post('/:username/cotisations', (req, res, next) => {});
router.post('/:username/edit', (req, res, next) => {});
router.delete('/cotisations/:cotisation', (req, res, next) => {});

module.exports = router;