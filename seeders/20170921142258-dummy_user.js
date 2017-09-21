'use strict';

module.exports = {
  up: (queryInterface, Sequelize) => {
      return queryInterface.bulkInsert('Users', [{
        login: 'jennypau',
        firstname: "Paul",
        lastname: "Jenny",
        email: "paul.jenny@etu.utc.fr",
        isAdult: 1,
        badge: "U184D913",
        createdAt: new Date(2017, 9, 17),
        updatedAt: new Date(2017, 9, 18)
      }], {});
  },

  down: (queryInterface, Sequelize) => {
      return queryInterface.bulkDelete('Users', null, {});
  }
};
