'use strict';

module.exports = {
  up: (queryInterface, Sequelize) => {
      return queryInterface.bulkInsert('Keys', [{
        key: '1234',
        login: "simde",
        description: "Master key!",
        createdAt: new Date(2017, 9, 17),
        updatedAt: new Date(2017, 9, 18),
        users_add: true,
        users_delete: true,
        users_edit: true,
        users_badge: true,
        contributions_add: true,
        contributions_delete: true,
        contributions_read: true,
        stats_read: true,
        settings_read: true,
        keys_all: true
      }], {});
  },

  down: (queryInterface, Sequelize) => {
      return queryInterface.bulkDelete('Keys', null, {});

  }
};
