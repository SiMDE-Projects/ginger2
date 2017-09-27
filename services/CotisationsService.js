const ginger = require('./../config/ginger').ginger;
const arrayContainsArray = require('./../utils/arrayContainsArray');
const CotisationModel = require('./../models/').Cotisation;
const UserModel = require('./../models').User;

let self = module.exports = {
    getAllCotisations: (login, permissions = []) => {
        return new Promise( (resolve, reject) => {
            let excludingAttributes = ["createdAt", "updatedAt", "user"];
            if (!permissions.includes("users_badge")) {
                excludingAttributes.push("badge");
            }
            CotisationModel.findAll({
                attributes: { exclude: excludingAttributes },
                include: [{
                    model: UserModel,
                    where: { login: login },
                    attributes: { exclude: excludingAttributes.concat(["id"]) }

                }]
            }).then( (cotisations) => {
                if (!cotisations) {
                    reject("???");
                } else {
                    resolve(cotisations);
                }
            }).catch( (err) => {
                console.log(err);
                reject(err);
            })
        });
    },
    getCotisation: (login, id, permissions = []) => {
        let excludingAttributes = ["createdAt", "updatedAt", "user"];
        if (!permissions.includes("users_badge")) {
            excludingAttributes.push("badge");
        }
        return new Promise( (resolve, reject) => {
            CotisationModel.findOne({
                where: {
                    id: id
                },
                attributes: {
                    exclude: excludingAttributes
                },
                include: [{
                    model: UserModel,
                    where: { login: login },
                    attributes: { exclude: excludingAttributes.concat(["id"])}
                }]
            }).then( cotisation => {
                if (!cotisation) {
                    reject("???");
                } else {
                    resolve(cotisation);
                }
            }).catch( err => {
                console.error(err);
                reject(err);
            })
        });
    },
    deleteCotisation: (id) => {
        return new Promise( (resolve, reject) => {
            CotisationModel.delete({
                where: {
                    id: id
                }
            }).then( count => {
                if (!count) {
                    reject("No cotisation");
                } else {
                    resolve();
                }
            }).catch( err => reject(err));
        })
    },
    addCotisation: (login, params) => {
        return new Promise( (resolve, reject) => {
            UserModel.findOne({
                where: { login: login }
            }).then( user => {
                params.user = user.get('id')
                CotisationModel.create(params).then( (cotisation) => {
                    resolve();
                }).catch( err => reject(err))
            }).catch( err => reject(err));
        })
    },

    isContributor: (user) => {
        return new Promise( (resolve, reject) => {
            CotisationModel.count({
                where: {
                    user: user.id,
                    begin: {
                        $lte: new Date()
                    },
                    end: {
                        $gte: new Date()
                    }
                }
            }).then( count => {
                resolve(!!count);
            }).catch( err => {
                reject(err);
            })
        })
    }
};