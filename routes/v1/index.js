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

/**
 * @apiDefine AuthorizationHeader
 *
 * @apiHeader {String} Authorization <code>Bearer YOUR_API_KEY</code> Your API Key for authentication.
 */

/**
 * @apiDefine ContentTypeJsonHeader
 *
 * @apiHeader {String} Content-Type <code>application/json</code>
 */

/**
 * @apiDefine InvalidAuthentication
 *
 * @apiError InvalidAuthentication 403 Authentication failed.
 * @apiErrorExample {json} Forbidden:
 *     HTTP/1.1 403 Forbidden
 *     {
 *       'error': 'Authentication failed'
 *     }
 */

/**
 * @apiDefine Unauthorized
 * @apiError Unauthorized 401 Operation not permitted.
 * @apiErrorExample {json} Unauthorized:
 *     HTTP/1.1 401 Unauthorized
 *     {
 *       'error': 'Operation not permitted'
 *     }
 *
 */

 /**
 * @apiDefine NotFound
 * @apiError NotFound 404 Not found.
 * @apiErrorExample {json} NotFound:
 *     HTTP/1.1 404 NotFound
 *     {
 *       'error': 'Not Found'
 *     }
 *
 */
