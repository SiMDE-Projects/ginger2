"use strict"

const express = require('express');
const path = require('path');
const UsersRoutes = require('./user');
const KeysRoutes = require('./key');
const SettingsRoutes = require('./settings');
const StatsRoutes = require('./stats');

let router = express.Router();

router.use('/users', UsersRoutes);
router.use('/keys', KeysRoutes);
router.use('/settings', SettingsRoutes);
router.use('/stats', StatsRoutes);

module.exports = router;