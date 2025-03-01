const mysql = require("mysql2");

// Configuración de la conexión a la base de datos
const pool = mysql.createPool({
    host: "localhost",
    user: "root",
    password: "Martin1614*",
    database: "vitalife",
    port: 3306,
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});

module.exports = pool.promise(); // Exportar la conexión