"use strict"

module.exports = (sequelize, DataTypes) => {
	const Key = sequelize.define('Key', {
		id: {
			type: DataTypes.INTEGER,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
        },
        key: {
            type: DataTypes.STRING(32),
            allowNull: false,
            unique: true
        },
		login: {
			type: DataTypes.STRING(32),
			allowNull: false
		},
		description: {
			type: DataTypes.TEXT,
			allowNull: false
		},
		users_add: {
			type: DataTypes.BOOLEAN,
			defaultValue: false
		},
		users_delete: {
			type: DataTypes.BOOLEAN,
			defaultValue: false
		},
		users_edit: {
			type: DataTypes.BOOLEAN,
			defaultValue: false
		},
		users_badge: {
			type: DataTypes.BOOLEAN,
			defaultValue: false
		},
		contributions_add: {
			type: DataTypes.BOOLEAN,
			defaultValue: false
		},
		contributions_delete: {
			type: DataTypes.BOOLEAN,
			defaultValue: false
		},
		contributions_read: {
			type: DataTypes.BOOLEAN,
			defaultValue: false
		},
		stats_read: {
			type: DataTypes.BOOLEAN,
			defaultValue: false
		},
		settings_read: {
			type: DataTypes.BOOLEAN,
			defaultValue: false
		},
		keys_all: {
			type: DataTypes.BOOLEAN,
			defaultValue: false
		},
	}, { getterMethods: {
		permissions: function() {
			let ar = ["users_add","users_delete","users_edit","users_badge","contributions_add","contributions_delete","contributions_read","stats_read","settings_read","keys_all"];
			let rights = [];
			ar.forEach( (el) => {
				if (this.getDataValue(el)) {
					rights.push(el);
				}
			});
			return rights;
		}
	}});
	return Key;
};