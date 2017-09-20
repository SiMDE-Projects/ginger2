"use strict"

const keyModel = require('../models').Key;

class KeysService {
        constructor() {
        }

        getSingleKey(token) {
            Key.findByKey(token).then({

            })
        }
}
     
module.exports = new KeysService();