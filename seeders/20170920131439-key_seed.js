'use strict';

module.exports = {
  up: (queryInterface, Sequelize) => {
      return queryInterface.bulkInsert('Keys', [{
        key: '1234',
        login: "polar",
        description: "Logiciel de caisse",
        createdAt: new Date(2017, 9, 17),
        updatedAt: new Date(2017, 9, 18)
      }], {});
  },

  down: (queryInterface, Sequelize) => {
      return queryInterface.bulkDelete('Keys', null, {});

  }
};
