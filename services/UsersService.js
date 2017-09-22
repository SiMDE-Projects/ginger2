const ginger = require('./../config/ginger').ginger;
const AccountsService = require('./AccountsService');
const UserModel = require('./../models/').User;


let self = module.exports = {
    getUser: (username, permissions) => {
        let excludingAttributes = ["createdAt", "updatedAt","id"];
        if (!permissions.includes("users_badge")) {
            excludingAttributes.push("badge");
        }

        return new Promise( (resolve, reject) => {
            UserModel.findOne({ where: {login: username}, attributes: { exclude: excludingAttributes }}).then(user => {
                if (!user) {
                    reject("User not found!");
                } else {
                    resolve(user);
                }
            });
        });
    },
    searchUser: (params, permissions) => {
        let excludingAttributes = ["createdAt", "updatedAt","id"];
        if (!permissions.includes("users_badge")) {
            excludingAttributes.push("badge");
        }
        return new Promise( (resolve, reject) => {
            UserModel.findOne( {
                where: params,
                attributes: { exclude: excludingAttributes }
            }).then( user => {
                if (!user) {
                    reject("User not found!");
                } else {
                    resolve(user);
                }
            }).catch( (err) => {
                // TO BE DONE
                reject("erreur de la requête!");
            })
        })
    },
    createUser: (user) => {
        return new Promise( (resolve, reject) => {
            UserModel.create(user)
            .then( () => {
               resolve();
            })
            .catch( (err) => {
                // Quelque chose ne fonctionne pas à la création
                // On devrait gérer les erreurs de Sequelize ici, et renvoyer l'objet adapté au controleur
                reject("TO BE DONE");
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
                    reject("User not found!");
                }
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
                    reject("User not found!");
                }
            })
            .catch( (err) => {
                // Quelque chose s'est mal passé, on devrait gérer ici les erreurs de Sequelize
                console.log(err);
                reject("TO BE DONE");
            })
        })
    },
    searchUsers: (search, permissions, limit = 10) => {
        let excludingAttributes = ["createdAt", "updatedAt","id"];
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
                        }

                    ]
                },
                group: ['login'],
                attributes: { exclude: excludingAttributes },
                limit: parseInt(limit, 10)
            }).then( (users) => {
                resolve(users);
            });
        });
    }
}