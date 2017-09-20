module.exports = {
  "development": {
    "username": "root",
    "password": "root",
    "database": "ginger",
    "host": "127.0.0.1",
    "dialect": "mysql"
  },
  "test": {
    "username": "root",
    "password": null,
    "database": "ginger",
    "host": "127.0.0.1",
    "dialect": "mysql"
  },
  "production": {
    "username": process.env.GINGER_DB_USERNAME,
    "password": process.env.GINGER_DB_PASSWORD,
    "database": process.env.GINGER_DB_NAME,
    "host": process.env.GINGER_DB_HOSTNAME,
    "dialect": "mysql"
  }
}
