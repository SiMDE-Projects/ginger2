"use strict"

const OverlappingContributionsError = require('./../errors/OverlappingContributionsError');
const WrongParameterError = require('./../errors/WrongParameterError');

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
		},
		amount: {
			type: DataTypes.INTEGER,
			allowNull: false
		},
		source: {
			type: DataTypes.ENUM("utc","bdeadmin","bdecotiz","simde"),
			defaultValue: "simde"
		}
	}, { validate: {
        beginBeforeEnd() {
			if (new Date(this.getDataValue("begin")) > new Date(this.getDataValue("end"))) {
				throw new WrongParameterError("La date de début avant date de fin!");
			}
        },
        overlappingDates() {
			return Contribution.count({
				where: {
					user: this.getDataValue("user"),
					$and: [
						{
							begin: {
								$lt: this.getDataValue("end")
							}
						},
						{
							end: {
								$gt: this.getDataValue("begin")
							}
						}
					]
				}
			}).then( (count) => {
				if (count) {
					throw new OverlappingContributionsError();
				}
			})
        }
	}});
	Contribution.associate = (models) => {
        Contribution.belongsTo(models.User, {foreignKey: "user", onDelete: "CASCADE" });
    }
	return Contribution;
};