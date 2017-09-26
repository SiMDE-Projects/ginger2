"use strict"
const CotisationModel = require('./cotisation').Cotisation;

module.exports = (sequelize, DataTypes) => {
	var User = sequelize.define('User', {
		login: {
			type: DataTypes.STRING(8),
            allowNull: false,
            unique: true
        },
        firstname: {
            type: DataTypes.STRING(32),
            allowNull: false,
        },
        lastname: {
            type: DataTypes.STRING(100),
            allowNull: false,
        },
        email: {
            type: DataTypes.STRING,
            allowNull: false,
            validate: { isEmail: true}
        },
		isAdult: {
			type: DataTypes.BOOLEAN,
            allowNull: false,
            defaultValue: false
		},
		badge: {
			type: DataTypes.STRING(32)
        },
        type: {
            type: DataTypes.ENUM("etu","escom","pers","escompers"),
            defaultValue: "etu"
        },
        isContributor: {
            type: new DataTypes.VIRTUAL(DataTypes.BOOLEAN)
        }
    });

    User.associate = (models) => {
        User.hasMany(models.Cotisation, {foreignKey: "user", onDelete: "CASCADE" });
    }
	return User;
};