const express = require("express");
const cors = require("cors");
const bodyParser = require("body-parser");
const authRoutes = require("./routes/auth");
const userRoutes = require("./routes/user");

const app = express();

// Middleware
app.use(cors());
app.use(bodyParser.json());

// Rutas
app.use("/api", authRoutes);
app.use("/api", userRoutes);

// Iniciar el servidor
const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Servidor corriendo en http://localhost:${PORT}`);
});