"use strict"

module.exports = (sequelize, DataTypes) => {
	const Cotisation = sequelize.define('Cotisation', {
		id: {
			type: DataTypes.INTEGER,
			allowNull: false,
			primaryKey: true,
			autoIncrement: true
        },
		begin: {
			type: DataTypes.DATEONLY,
            allowNull: false,
            defautValue: DataTypes.NOW
		},
		end: {
			type: DataTypes.DATEONLY,
			allowNull: false
		}
	}, { validate: {
        beginBeforeEnd() {
            // TDB
        },
        overlappingDates() {
            // TBD
        }
    }});
	return Cotisation;
};