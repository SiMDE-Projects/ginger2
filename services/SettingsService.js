const ginger = require('./../config/ginger').ginger;
const arrayContainsArray = require('./../utils/arrayContainsArray');

const WrongParameterError = require('./../errors/WrongParameterError');
let self = module.exports = {
    getAllSettings: () => {
        return new Promise( (resolve, reject) => {
            resolve(ginger);
        });
    },
    editSettings: (params) => {
        return new Promise( (resolve, reject) => {
            // On check tous les params
            return Promise.all(Object.keys(params).map( key => {
                return new Promise( (resolve2) => {
                    if (typeof(params[key]) !== typeof(ginger[key])) {
                        throw new WrongParameterError("Mauvais format pour " + key+ ". " + typeof(ginger[key]) + " attendu");
                    } else {
                        resolve2();
                    }
                })
            }))
            .then( () => {
                return Promise.all(Object.keys(params).map( key => {
                    return new Promise( (resolve2) => {
                        ginger[key] = params[key];
                        resolve2();
                    })
                }))
            })
            .then( () => resolve(ginger))
            .catch( err => reject(err));
        });
    }
};