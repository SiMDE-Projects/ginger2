"use strict"

module.exports = (sequelize, DataTypes) => {
	var User = sequelize.define('User', {
		login: {
			type: DataTypes.STRING(8),
			allowNull: false,
			primaryKey: true,
        },
        firstname: {
            type: DataTypes.STRING,
            allowNull: false,
        },
        lastname: {
            type: DataTypes.STRING,
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
			type: DataTypes.STRING,
			allowNull: false
		}
    });

    User.associate = (models) => {
        User.hasMany(models.Cotisation, {foreignKey: "login", onDelete: "CASCADE" });
    }
	return User;
};