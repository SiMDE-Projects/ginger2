const ginger = require('./../config/ginger').ginger;
const rp = require('request-promise-native');

let self = module.exports = {
    buildOptions: (endPoint, params) => {
        return {
            uri: ginger.accounts_url + endPoint,
            qs: params,
            method: "GET",
            headers: {
                'User-Agent': ginger.accounts_useragent
            },
            json: true
        }
    },

    getUserInfo: (login) => {
        return new Promise( (resolve, reject) => {
            let options = self.buildOptions("getUserInfo", { "username": login });
            rp(options).then( user => {
                if (user.cardSerialNumber) {
                    user.cardSerialNumber = self._swapUid(user.cardSerialNumber).toUpperCase();
                }
                console.log(user);
                resolve(user);
            }).catch(err => {
                console.error(err);
                reject(err);
            })
        })
    },

    cardLookup: (badge) => {
        return new Promise( (resolve, reject) => {
            let options = self.buildOptions("cardLookup", { "serialNumber": badge.toLowerCase() });
            rp(options).then( user => {
                if (user.cardSerialNumber) {
                    user.cardSerialNumber = self._swapUid(user.cardSerialNumber).toUpperCase();
                }
                resolve(user);
            }).catch(err => {
                reject(err);
            })
        })        
    },

    _swapUid: (uid) => {
        return uid[6] + uid[7] + uid[4] + uid[5] + uid[2] + uid[3] + uid[0] + uid[1];
    }
}