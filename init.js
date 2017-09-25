const db = require('./models');
const env = process.env.NODE_ENV || "development";
const exec = require('child_process').exec;

db.sequelize.sync({force: true}).then(() => {
    if (env === "development") {
        exec('sequelize db:seed:all', (err, out, code) => {
            if (err) {
                console.error("Vous devez installer sequelize-cli en global!");
                console.error("npm install -g sequelize-cli");
                console.error(err);
                process.exit();
                return;
            }
            console.log(out);
            console.log("Votre base de données est prête!");
            process.exit();
        });
    }
});