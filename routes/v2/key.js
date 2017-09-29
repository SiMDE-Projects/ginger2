"use strict"

const express = require('express');
const path = require('path');

const kController = require('./../../controllers/keys');
const mAuthorize = require('./../../middlewares/authorize');

let router = express.Router();

/**

 * @api {GET} /v1/keys Request many keys
 * @apiName getAllKeys
 * @apiGroup Keys
 *
 * @apiParam {String} login Unique login optional (as query parameter)
 * @apiUse AuthorizationHeader 
 * @apiPermission keys_all
 *
 * @apiSuccess {Object[]} - List of keys
 * @apiSuccess {Number} -.id Unique id of the Key.
 * @apiSuccess {String} -.key Key.
 * @apiSuccess {String} -.login  Lastname of the User.
 * @apiSuccess {String} -.description  Address of the user.
 * @apiSuccess {Boolean} -.users_add Right to add users
 * @apiSuccess {Boolean} -.users_delete  Right to delete users
 * @apiSuccess {Boolean} -.users_edit  Right to edit users
 * @apiSuccess {Boolean} -.users_badge Right to view users' badge    
 * @apiSuccess {Boolean} -.contributions_add  Right to add contributions of Users
 * @apiSuccess {Boolean} -.contributions_delete Right to delete contributions of Users
 * @apiSuccess {Boolean} -.contributions_read Right to see contributions of Users
 * @apiSuccess {Boolean} -.stats  Right to see statistics about Ginger.
 * @apiSuccess {Boolean} -.settings_read  Right to see Ginger's settings
 * @apiSuccess {Boolean} -.keys_all  Right to manage API keys
 * @apiSuccess {String[]} -.permissions Array containing all key's rights
 * @apiSuccess {Datetime} -.createdAt Date of creation
 * @apiSuccess {Datetime} -.updatedAt Date of last update
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *       [
 *          {
 *              "permissions": [
 *                  "users_add",
 *                   "users_delete",
 *                  "users_edit",
 *                  "users_badge",
 *                  "contributions_add",
 *                  "contributions_delete",
 *                  "contributions_read",
 *                  "stats_read",
 *                  "settings_read",
 *                  "keys_all"
 *              ],
 *              "id": 1,
 *              "key": "1234",
 *              "login": "simde",
 *              "description": "Master key!",
 *              "users_add": true,
 *              "users_delete": true,
 *              "users_edit": true,
 *              "users_badge": true,
 *              "contributions_add": true,
 *              "contributions_delete": true,
 *              "contributions_read": true,
 *              "stats_read": true,
 *              "settings_read": true,
 *              "keys_all": true,
 *              "createdAt": "2017-10-16T22:00:00.000Z",
 *              "updatedAt": "2017-10-17T22:00:00.000Z"
 *          }
 *      ]
 *
 * @apiExample {curl} Query all keys:
 *     curl -i "http://localhost:3000/v1/keys" -H "Authorization: Bearer YOUR_API_KEY"
 * @apiExample {curl} Query all login's keys:
 *     curl -i "http://localhost:3000/v1/keys?login=simde" -H "Authorization: Bearer YOUR_API_KEY"
 * @apiUse InvalidAuthentication
 * @apiUse Unauthorized
 */
router.get('/', mAuthorize('keys_all'), kController.getAllKeys);

/**

 * @api {POST} /v1/keys Create a key
 * @apiName createKey
 * @apiGroup Keys
 *
 * @apiPermission keys_all
 * @apiUse AuthorizationHeader 
 * @apiUse ContentTypeJsonHeader
 * 
 * @apiParam {String} login Unique login (compulsory)
 * @apiParam {String} description  Description of the key (compulsory)
 * @apiParam {Boolean} users_add Right to add users (default: false)
 * @apiParam {Boolean} users_delete  Right to delete users (default: false)
 * @apiParam {Boolean} users_edit  Right to edit users (default: false)
 * @apiParam {Boolean} users_badge Right to view users' badge (default: false)   
 * @apiParam {Boolean} contributions_add  Right to add contributions of Users (default: false)
 * @apiParam {Boolean} contributions_delete Right to delete contributions of Users (default: false)
 * @apiParam {Boolean} contributions_read Right to see contributions of Users (default: false)
 * @apiParam {Boolean} stats  Right to see statistics about Ginger. (default: false)
 * @apiParam {Boolean} settings_read  Right to see Ginger's settings (default: false)
 * @apiParam {Boolean} keys_all  Right to manage API keys (default: false)
 * @apiSuccess (201 - Created) {String} login Unique login (compulsory)
 * @apiSuccess (201 - Created) {String} description  Description of the key (compulsory)
 * @apiSuccess (201 - Created) {Boolean} users_add Right to add users (default: false)
 * @apiSuccess (201 - Created) {Boolean} users_delete  Right to delete users (default: false)
 * @apiSuccess (201 - Created) {Boolean} users_edit  Right to edit users (default: false)
 * @apiSuccess (201 - Created) {Boolean} users_badge Right to view users' badge (default: false)   
 * @apiSuccess (201 - Created) {Boolean} contributions_add  Right to add contributions of Users (default: false)
 * @apiSuccess (201 - Created) {Boolean} contributions_delete Right to delete contributions of Users (default: false)
 * @apiSuccess (201 - Created) {Boolean} contributions_read Right to see contributions of Users (default: false)
 * @apiSuccess (201 - Created) {Boolean} stats  Right to see statistics about Ginger. (default: false)
 * @apiSuccess (201 - Created) {Boolean} settings_read  Right to see Ginger's settings (default: false)
 * @apiSuccess (201 - Created) {Boolean} keys_all  Right to manage API keys (default: false)
 * @apiSuccessExample {json} Created-Response:
 *     HTTP/1.1 201 CREATED
 *          {
 *              "permissions": [
 *                  "users_add",
 *                   "users_delete",
 *                  "users_edit",
 *                  "users_badge",
 *                  "contributions_add",
 *                  "contributions_delete",
 *                  "contributions_read",
 *                  "stats_read",
 *                  "settings_read",
 *                  "keys_all"
 *              ],
 *              "id": 1,
 *              "key": "1234",
 *              "login": "simde",
 *              "description": "Master key!",
 *              "users_add": true,
 *              "users_delete": true,
 *              "users_edit": true,
 *              "users_badge": true,
 *              "contributions_add": true,
 *              "contributions_delete": true,
 *              "contributions_read": true,
 *              "stats_read": true,
 *              "settings_read": true,
 *              "keys_all": true,
 *              "createdAt": "2017-10-16T22:00:00.000Z",
 *              "updatedAt": "2017-10-17T22:00:00.000Z"
 *          }
 *
 * @apiExample {curl} Create a key:
 *     curl -X POST \
 *      -i "http://localhost:3000/v1/keys" \
 *      -H "Authorization: Bearer YOUR_API_KEY" \
 *      -H "Content-type: application/json" \
 *      -d '{ "login": "simde", "description": "Clé de test 3"}'
 * 
 * @apiUse InvalidAuthentication
 * @apiUse Unauthorized
 */
router.post('/', mAuthorize('keys_all'), kController.createKey);

/**
 * @api {GET} /v1/keys/:id Request a specific key
 * @apiName getKey
 * @apiGroup Keys
 *
 * @apiParam {Number} id Unique id
 * @apiUse AuthorizationHeader 
 * @apiPermission keys_all
 *
 * @apiSuccess {Number} id Unique id of the Key.
 * @apiSuccess {String} key Key.
 * @apiSuccess {String} login  Lastname of the User.
 * @apiSuccess {String} description  Address of the user.
 * @apiSuccess {Boolean} users_add Right to add users
 * @apiSuccess {Boolean} users_delete  Right to delete users
 * @apiSuccess {Boolean} users_edit  Right to edit users
 * @apiSuccess {Boolean} users_badge Right to view users' badge    
 * @apiSuccess {Boolean} contributions_add  Right to add contributions of Users
 * @apiSuccess {Boolean} contributions_delete Right to delete contributions of Users
 * @apiSuccess {Boolean} contributions_read Right to see contributions of Users
 * @apiSuccess {Boolean} stats  Right to see statistics about Ginger.
 * @apiSuccess {Boolean} settings_read  Right to see Ginger's settings
 * @apiSuccess {Boolean} keys_all  Right to manage API keys
 * @apiSuccess {String[]} permissions Array containing all key's rights
 * @apiSuccess {Datetime} createdAt Date of creation
 * @apiSuccess {Datetime} updatedAt Date of last update
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *          "permissions": [
 *             "users_add",
 *             "users_delete",
 *             "users_edit",
 *             "users_badge",
 *             "contributions_add",
 *             "contributions_delete",
 *             "contributions_read",
 *             "stats_read",
 *             "settings_read",
 *             "keys_all"
 *            ],
 *            "id": 1,
 *            "key": "1234",
 *            "login": "simde",
 *            "description": "Master key!",
 *            "users_add": true,
 *            "users_delete": true,
 *            "users_edit": true,
 *            "users_badge": true,
 *            "contributions_add": true,
 *            "contributions_delete": true,
 *            "contributions_read": true,
 *            "stats_read": true,
 *            "settings_read": true,
 *            "keys_all": true,
 *            "createdAt": "2017-10-16T22:00:00.000Z",
 *            "updatedAt": "2017-10-17T22:00:00.000Z"
 *     }
 *
 * @apiExample {curl} Query a key:
 *     curl -i "http://localhost:3000/v1/keys/1" -H "Authorization: Bearer YOUR_API_KEY"
 * @apiUse InvalidAuthentication
 * @apiUse Unauthorized
 * @apiUse NotFound
 */
router.get('/:id', mAuthorize('keys_all'), kController.getKey);

/**

 * @api {DELETE} /v1/keys/:id Delete a specific key
 * @apiName deleteKey
 * @apiGroup Keys
 *
 * @apiParam {Number} id Unique id
 * @apiUse AuthorizationHeader 
 * @apiPermission keys_all
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *     }
 *
 * @apiExample {curl} Delete a key:
 *     curl -i "http://localhost:3000/v1/keys/1" -H "Authorization: Bearer YOUR_API_KEY" -X DELETE
 * @apiUse InvalidAuthentication
 * @apiUse Unauthorized
 * @apiUse NotFound
 */

router.delete('/:id', mAuthorize('keys_all'), kController.deleteKey);

/**
 * @api {POST} /v1/keys/1 Regenerate a key
 * @apiName refreshKey
 * @apiGroup Keys
 *
 * @apiPermission keys_all
 * @apiUse AuthorizationHeader 
 * @apiUse ContentTypeJsonHeader
 * 
 * @apiParam {String} login Unique login (compulsory)
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *       [
 *          {
 *              "permissions": [
 *                  "users_add",
 *                   "users_delete",
 *                  "users_edit",
 *                  "users_badge",
 *                  "contributions_add",
 *                  "contributions_delete",
 *                  "contributions_read",
 *                  "stats_read",
 *                  "settings_read",
 *                  "keys_all"
 *              ],
 *              "id": 1,
 *              "key": "1234",
 *              "login": "simde",
 *              "description": "Master key!",
 *              "users_add": true,
 *              "users_delete": true,
 *              "users_edit": true,
 *              "users_badge": true,
 *              "contributions_add": true,
 *              "contributions_delete": true,
 *              "contributions_read": true,
 *              "stats_read": true,
 *              "settings_read": true,
 *              "keys_all": true,
 *              "createdAt": "2017-10-16T22:00:00.000Z",
 *              "updatedAt": "2017-10-17T22:00:00.000Z"
 *          }
 *      ]
 *
 * @apiExample {curl} Regenerate a key:
 *     curl -X POST \
 *  -i "http://localhost:3000/v1/keys/1" \
 *  -H "Authorization: Bearer YOUR_API_KEY" \
 *  -H "Content-type: application/json" \
 * 
 * @apiUse InvalidAuthentication
 * @apiUse Unauthorized
 * @apiUse NotFound
 */
router.post('/:id', mAuthorize('keys_all'), kController.refreshKey);

/**

 * @api {PATCH} /v1/keys/1 Edit a key
 * @apiName editKey
 * @apiGroup Keys
 *
 * @apiPermission keys_all
 * @apiUse AuthorizationHeader 
 * @apiUse ContentTypeJsonHeader
 * 
 * @apiParam {Number} id Unique id
 * @apiParam {String} login Unique login (compulsory)
 * @apiParam {String} description  Description of the key (compulsory)
 * @apiParam {Boolean} users_add Right to add users (default: false)
 * @apiParam {Boolean} users_delete  Right to delete users (default: false)
 * @apiParam {Boolean} users_edit  Right to edit users (default: false)
 * @apiParam {Boolean} users_badge Right to view users' badge (default: false)   
 * @apiParam {Boolean} contributions_add  Right to add contributions of Users (default: false)
 * @apiParam {Boolean} contributions_delete Right to delete contributions of Users (default: false)
 * @apiParam {Boolean} contributions_read Right to see contributions of Users (default: false)
 * @apiParam {Boolean} stats  Right to see statistics about Ginger. (default: false)
 * @apiParam {Boolean} settings_read  Right to see Ginger's settings (default: false)
 * @apiParam {Boolean} keys_all  Right to manage API keys (default: false)
 * @apiSuccessExample {json} No-Content-Response:
 *     HTTP/1.1 204 NO_CONTENT
 *      {}
 *
 * @apiExample {curl} Edit a key:
 *     curl -X PATCH \
 *  -i "http://localhost:3000/v1/keys" \
 *  -H "Authorization: Bearer YOUR_API_KEY" \
 *  -H "Content-type: application/json" \
 *  -d '{ "login": "simde", "description": "Clé de test 3"}'
 * 
 * @apiUse InvalidAuthentication
 * @apiUse Unauthorized
 * @apiUse NotFound
 */

router.patch('/:id', mAuthorize('keys_all'), kController.editKey);

module.exports = router;