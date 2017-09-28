"use strict"

const ginger = require('./../config/ginger').ginger;
const KeyModel = require('./../models/').Key;
const randomToken = require('random-token');
const KeyNotFoundError = require('./../errors/KeyNotFoundError');

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
                    reject(new KeyNotFoundError());
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
                    reject(new KeyNotFoundError());
                }
            })
        })
    },
    refreshKey: (pk) => {
        return new Promise( (resolve, reject) => {
            KeyModel.update({key: randomToken(ginger.key_size)}, { where: { id: pk}}).then( (count) => {
                if (!count) {
                    reject(new KeyNotFoundError());
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
                    reject(new KeyNotFoundError());
                } else {
                    resolve();
                }
            }).catch( (err) => {
                reject(err);
            })
        })
    }
};