"use strict"

const ginger = require('./../config/ginger').ginger;
const KeyModel = require('./../models/').Key;
const randomToken = require('random-token');

let self = module.exports = {
    getAllKeys: (login) => {
        let searchTerm = {}
        if (login) {
            searchTerm = { login: login };
        }
        return new Promise( (resolve, reject) => {
            KeyModel.findAll({ where: searchTerm}).then( (keys) => {
                resolve(keys);
            }).catch( (err) => { reject(err)});
        });
    },
    getKey: (pk) => {
        return new Promise( (resolve, reject) => {
            KeyModel.findById(pk).then( (key) => {
                if (!key) {
                    reject("Aucune clé avec cet ID");
                } else {
                    resolve(key);
                }
            }).catch( (err) => {
                reject(err);
            })
        })
    },
    deleteKey: (pk) => {
        return new Promise( (resolve, reject) => {
            KeyModel.destroy( { where: { id: pk}}).then( (count) => {
                if (count) {
                    resolve();
                } else {
                    reject("Key not found!");
                }
            })
        })
    },
    refreshKey: (pk) => {
        return new Promise( (resolve, reject) => {
            KeyModel.update({key: randomToken(ginger.key_size)}, { where: { id: pk}}).then( (count) => {
                if (!count) {
                    reject("Not found");
                } else {
                    KeyModel.findById(pk).then( (key) => {
                        console.log(key);
                        resolve(key);
                    })
                }
            }).catch( (err) => { 
                reject(err)
            });
        })
    },
    createKey: (params) => {
        console.log(params);
        params.key = randomToken(ginger.key_size);
        return new Promise( (resolve, reject) => {
            KeyModel.create(params).then( (key) => {
                resolve(key);
            })
            .catch( (err) => {
                reject(err);
            })
        })
    },
    editKey: (pk, params) => {
        return new Promise( (resolve, reject) => {
            KeyModel.update(params, { where: { id: pk }}).then( (count) => {
                if(!count) {
                    reject("Not found!");
                } else {
                    resolve();
                }
            }).catch( (err) => {
                reject(err);
            })
        })
    }
};