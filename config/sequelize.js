module.exports = {
  "development": {
    "username": process.env.GINGER_DEV_DB_USERNAME || "root",
    "password": process.env.GINGER_DEV_DB_PASSWORD || "root",
    "database": process.env.GINGER_DEV_DB_NAME || "ginger",
    "host": process.env.GINGER_DEV_DB_HOSTNAME || "127.0.0.1",
    "dialect": "mysql"
  },
  "test": {
    "username": process.env.GINGER_TEST_DB_USERNAME || "root",
    "password": process.env.GINGER_TEST_DB_PASSWORD || null,
    "database": process.env.GINGER_TEST_DB_NAME || "ginger",
    "host": process.env.GINGER_TEST_DB_HOSTNAME || "127.0.0.1",
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
