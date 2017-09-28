"use strict"

module.exports = (sequelize, DataTypes) => {
	const Contribution = sequelize.define('Contribution', {
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
			if (new Date(this.getDataValue("begin")) > new Date(this.getDataValue("end"))) {
				throw new Error("La date de début avant date de fin!");
			}
        },
        overlappingDates() {
            // TBD
        }
	}});
	Contribution.associate = (models) => {
        Contribution.belongsTo(models.User, {foreignKey: "user", onDelete: "CASCADE" });
    }
	return Contribution;
};