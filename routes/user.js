const express = require("express");
const router = express.Router();
const db = require("../db");

// Obtener el perfil de un usuario
router.get("/user/profile", async (req, res) => {
    const { userId } = req.query;

    if (!userId) {
        return res.status(400).json({ success: false, message: "Falta el parámetro userId" });
    }

    try {
        const [rows] = await db.query(
            `SELECT nombres, apellidos, email, fecha_nacimiento, peso, talla, genero, nivel_actividad, 
             TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad 
             FROM usuarios WHERE id = ?`,
            [userId]
        );

        if (rows.length > 0) {
            const user = {
                nombres: rows[0].nombres || "No registrado",
                apellidos: rows[0].apellidos || "No registrado",
                email: rows[0].email || "No registrado",
                fecha_nacimiento: rows[0].fecha_nacimiento || "No registrada",
                peso: rows[0].peso || "No registrada",
                talla: rows[0].talla || "No registrada",
                genero: rows[0].genero || "No especificado",
                nivel_actividad: rows[0].nivel_actividad || "No especificado",
                edad: rows[0].edad || "0"
            };

            res.json({ success: true, user });
        } else {
            res.status(404).json({ success: false, message: "Usuario no encontrado" });
        }
    } catch (error) {
        console.error(error);
        res.status(500).json({ success: false, message: "Error en el servidor" });
    }
});

module.exports = router;