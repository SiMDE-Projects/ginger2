const ginger = require('./../config/ginger').ginger;
const AccountsService = require('./AccountsService');
const UserModel = require('./../models/').User;
const ContributionModel = require('./../models').Contribution;
const ContributionsService = require('./ContributionsService');
const Sequelize = require('sequelize');
const UserNotFoundError = require('./../errors/UserNotFoundError');

let self = module.exports = {

    createUserFromAccountsLogin: (login) => {
        return new Promise( (resolve, reject) => {
            AccountsService.getUserInfo(login)
            .then( (accountsUser) => {
                UserModel.create({
                    firstname: accountsUser.firstName,
                    lastname: accountsUser.lastName,
                    email: accountsUser.mail,
                    isAdult: accountsUser.legalAge,
                    badge: accountsUser.cardSerialNumber
                })
                .then( (user) => resolve(user))
                .catch( err => {
                    // Problème de base de données
                    reject(err);
                })
            })
            .catch( err => {
                reject(err);
            })
        });
    },
    createUserFromAccountsBadge: (badge) => {
        return new Promise( (resolve, reject) => {
            AccountsService.cardLookup(badge)
            .then( (accountsUser) => {
                UserModel.create({
                    firstname: accountsUser.firstName,
                    lastname: accountsUser.lastName,
                    email: accountsUser.mail,
                    isAdult: accountsUser.legalAge,
                    badge: accountsUser.cardSerialNumber
                })
                .then( (user) => resolve(user))
                .catch( err => {
                    // Problème de base de données
                    reject(err);
                })
            })
            .catch( err => {
                reject(err);
            })
        });
    },
    refreshUserFromAccounts: (user) => {
        return new Promise( (resolve, reject) => {
            if (ginger.refresh_on_lookup 
                && Date.now() >= new Date(user.getDataValue("updatedAt")).getTime() + ginger.time_before_update
                && user.type !== "ext") {
                AccountsService.getUserInfo(user.login)
                .then( (accountsUser) => {
                    user.update( {
                        firstname: accountsUser.firstName,
                        lastname: accountsUser.lastName,
                        email: accountsUser.mail,
                        isAdult: accountsUser.legalAge,
                        badge: accountsUser.cardSerialNumber
                    }).then( () => {
                        resolve(user);
                    });
                })
                .catch( (err) => {
                    // On balance un 404 si 404
                    // C'est moche mais bon on renvoie l'ancien utilisateur quand même
                    // TODO: Log l'erreur pour le SiMDE
                    if (err.code === "ECONNREFUSED") {
                        console.log("AIE AIE");
                    }
                    reject(err);
                })
            }
            else {
                resolve(user);
            }
        })
    },
    getUser: (username, permissions = []) => {
        let excludingAttributes = ["createdAt"];
        if (!permissions.includes("users_badge")) {
            excludingAttributes.push("badge");
        }
        
        return new Promise( (resolve, reject) => {
            UserModel.findOne({ where: {login: username}, attributes: { exclude: excludingAttributes }})
            .then(user => {
                if (!user) {
                    // L'utilisateur existe pas dans la DB, on appelle AccountsUTC pour savoir si il existe vraiment
                    self.createUserFromAccountsLogin(username).then( user => {
                        delete(user.dataValues.id);
                        resolve(user);
                    }).catch( err => {
                        // Erreur dans la base de données :/ (intégrité souvent)
                        reject(err);
                    })
                } else {
                    Promise.all([ContributionsService.isContributor(user), self.refreshUserFromAccounts(user)])
                    .then( ([isCotisant, finalUser]) => {
                        delete(finalUser.dataValues.id);
                        finalUser.setDataValue("isContributor", isCotisant);
                        resolve(finalUser);
                    })
                    .catch( err => {
                        reject(err);
                    })
                }
            })
            .catch( err => {
                reject(err);
            })
        });
    },
    searchUser: (params, permissions = []) => {
        let excludingAttributes = ["createdAt"];
        if (!permissions.includes("users_badge")) {
            excludingAttributes.push("badge");
        }
        return new Promise( (resolve, reject) => {
            UserModel.findOne({ where: params, attributes: { exclude: excludingAttributes }})
            .then(user => {
                if (!user) {
                    throw("not found");
                } else {
                    Promise.all([ContributionsService.isContributor(user), self.refreshUserFromAccounts(user)])
                    .then( ([isCotisant, finalUser]) => {
                        delete(finalUser.dataValues.id);
                        finalUser.setDataValue("isContributor", isCotisant);
                        resolve(finalUser);
                    })
                    .catch( err => {
                        reject(err);
                    })
                }
            })
            .catch( err => {
                // L'utilisateur existe pas dans la DB, on appelle AccountsUTC pour savoir si il existe vraiment
                if (params.login) {
                    self.createUserFromAccountsLogin(params.login).then( user => {
                        delete(user.dataValues.id);
                        delete(user.dataValues.createdAt);
                        delete(user.dataValues.updatedAt);
                        resolve(user);
                    }).catch( err => {
                        // Erreur dans la base de données :/ (intégrité souvent)
                        reject(err);
                    })
                } else if (params.badge) {
                    self.createUserFromAccountsBadge(params.badge).then( user => {
                        delete(user.dataValues.id);
                        delete(user.dataValues.createdAt);
                        delete(user.dataValues.updatedAt);
                        resolve(user);
                    }).catch( err => {
                        // Erreur dans la base de données :/ (intégrité souvent)
                        reject(err);
                    })
                } else {
                    // Erreur dans la base de donnée
                    reject(err);
                }
            })
        });
    },
    createUser: (user) => {
        return new Promise( (resolve, reject) => {
            UserModel.create(user)
            .then( () => {
               resolve();
            })
            .catch(Sequelize.UniqueConstraintError, err => {
                // L'utilisateur existe déjà
                reject("TO BE DONE");
            })
            .catch(Sequelize.ValidationError, err => {
                // Un champ est manquant!
                console.log(err);
                console.log("AIE");
                reject("AIE");
            })
        })
    },
    deleteUser: (pk) => {
        return new Promise( (resolve, reject) => {
            UserModel.destroy({ where: { login: pk}})
            .then( (count) => {
                if (count) {
                    resolve();
                } else {
                    reject(new UserNotFoundError());
                }
            })
            .catch( err => {
                console.log(err);
                reject(err);
            })
        })
    },
    editUser: (pk, attributes) => {
        return new Promise( (resolve, reject) => {
            UserModel.update({ attributes, where: { login: pk}})
            .then((count) => {
                if (count) {
                    resolve();
                } else {
                    reject(new UserNotFoundError());
                }
            })
            .catch( (err) => {
                // Quelque chose s'est mal passé, on devrait gérer ici les erreurs de Sequelize
                console.log(err);
                reject(err);
            })
        })
    },
    searchUsers: (search, permissions = [], limit = ginger.limit_default_search) => {
        let excludingAttributes = ["createdAt", "updatedAt"];
        if (!permissions.includes("users_badge")) {
            excludingAttributes.push("badge");
        }

        return new Promise( (resolve, reject) => {
            UserModel.findAll({
                where: {
                    $or: [
                        {
                            login: { 
                                $like: '%' + search + '%'
                            },
                        },
                        {
                            firstname: {
                                $like: '%' + search + '%'
                            }
                        },
                        {
                            lastname: {
                                $like: '%' + search + '%'
                            }
                        },
                        {
                            email: {
                                $like: '%' + search + '%'
                            }
                        },
                        {
                            badge: {
                                $like: '%' + search + '%'
                            }
                        }

                    ]
                },
                group: ['login'],
                attributes: { exclude: excludingAttributes },
                limit: parseInt(limit, 10)
            }).then( (users) => {
                return Promise.all(users.map( user => {
                        return new Promise( resolve2 => {
                            ContributionsService.isContributor(user).then( (is) => {
                                delete(user.dataValues.id);
                                user.setDataValue("isContributor", is);
                                resolve2(user);
                            })
                        })
                    }));
            }).then( usersFinal => { resolve(usersFinal)});
        });
    },
    getStats: () => {
        return new Promise( (resolve, reject) => {
            UserModel.findAndCountAll({ include: [{ model: ContributionModel}]})
            .then(result => {
                let types = {};
                let adults = { "0": 0, "1": 0};

                UserModel.rawAttributes.type.values.forEach( (value) => {
                    types[value] = 0;
                });

                result.rows.forEach( (user) => {
                    types[user.type] += 1;
                    adults[user.isAdult ? 1: 0] += 1;
                });

                let fi = { "total": result.count, "types": types, "adults": adults};
                resolve(fi);
            });
        })
    }
}