const ginger = require('./../config/ginger').ginger;
const arrayContainsArray = require('./../utils/arrayContainsArray');

let self = module.exports = {
    getAllSettings: () => {
        return new Promise( (resolve, reject) => {
            resolve(ginger);
        });
    },
    editSettings: (params) => {
        return new Promise( (resolve, reject) => {
            console.log(params);
            resolve();
            // TO DO  
        });
    }
};