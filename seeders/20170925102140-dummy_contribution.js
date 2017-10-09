'use strict';

module.exports = {
  up: (queryInterface, Sequelize) => {
      return queryInterface.bulkInsert('Contributions', [{
        begin: new Date(2017, 9, 15),
        end: new Date(2018, 8, 31),
        createdAt: new Date(2017, 9, 25),
        updatedAt: new Date(2017, 9, 25),
        amount: 20,
        user: 1
      }], {});
  },

  down: (queryInterface, Sequelize) => {
      return queryInterface.bulkDelete('Contributions', null, {});
  }
};
