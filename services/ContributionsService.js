const ginger = require('./../config/ginger').ginger;
const arrayContainsArray = require('./../utils/arrayContainsArray');
const ContributionModel = require('./../models/').Contribution;
const UserModel = require('./../models').User;

const ContributionNotFoundError = require('./../errors/ContributionNotFoundError');

let self = module.exports = {
    getAllContributions: (login, permissions = []) => {
        return new Promise( (resolve, reject) => {
            let excludingAttributes = ["createdAt", "updatedAt", "user"];
            if (!permissions.includes("users_badge")) {
                excludingAttributes.push("badge");
            }
            ContributionModel.findAll({
                attributes: { exclude: excludingAttributes },
                include: [{
                    model: UserModel,
                    where: { login: login },
                    attributes: { exclude: excludingAttributes.concat(["id"]) }

                }]
            }).then( (contributions) => {
                if (!contributions.length) {
                    reject(new ContributionNotFoundError("Aucune contribution ou utilisateur inexistant!"));
                } else {
                    resolve(contributions);
                }
            }).catch( (err) => {
                console.log(err);
                reject(err);
            })
        });
    },
    getContribution: (login, id, permissions = []) => {
        let excludingAttributes = ["createdAt", "updatedAt", "user"];
        if (!permissions.includes("users_badge")) {
            excludingAttributes.push("badge");
        }
        return new Promise( (resolve, reject) => {
            ContributionModel.findOne({
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
            }).then( contribution => {
                if (!contribution) {
                    reject(new ContributionNotFoundError());
                } else {
                    resolve(contribution);
                }
            }).catch( err => {
                // Erreur Sequelize
                console.error(err);
                reject(err);
            })
        });
    },
    deleteContribution: (id) => {
        return new Promise( (resolve, reject) => {
            ContributionModel.delete({
                where: {
                    id: id
                }
            }).then( count => {
                if (!count) {
                    reject(new ContributionNotFoundError());
                } else {
                    resolve();
                }
            }).catch( err => reject(err));
        })
    },
    addContribution: (login, params) => {
        return new Promise( (resolve, reject) => {
            UserModel.findOne({
                where: { login: login }
            }).then( user => {
                params.user = user.get('id')
                ContributionModel.create(params).then( (contribution) => {
                    resolve();
                }).catch( err => reject(err))
            }).catch( err => reject(err));
        })
    },

    isContributor: (user) => {
        return new Promise( (resolve, reject) => {
            ContributionModel.count({
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