const ginger = require('./../config/ginger').ginger;

let self = module.exports = {
    getAllStats: () => {
        return new Promise( (resolve, reject) => {
            resolve(ginger);
        });
    }
};