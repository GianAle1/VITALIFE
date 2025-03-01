const express = require("express");
const router = express.Router();
const db = require("../db");

// Registrar un nuevo usuario
router.post("/auth/register", async (req, res) => {
    const { nombres, apellidos, email, password, fecha_nacimiento, peso, talla, genero, nivel_actividad} = req.body;

    if (!nombres || !apellidos || !email || !password) {
        return res.status(400).json({ success: false, message: "Faltan datos en la solicitud" });
    }

    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        return res.status(400).json({ success: false, message: "Correo electrónico inválido" });
    }

    try {
        // Verificar si el correo ya está registrado
        const [existingUser] = await db.query("SELECT id FROM usuarios WHERE email = ?", [email]);
        if (existingUser.length > 0) {
            return res.status(400).json({ success: false, message: "El correo ya está registrado" });
        }

        // Insertar el nuevo usuario
        const [result] = await db.query(
            `INSERT INTO usuarios (nombres, apellidos, email, password, fecha_nacimiento, peso, talla, genero, nivel_actividad) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [nombres, apellidos, email, password, fecha_nacimiento, peso, talla, genero, nivel_actividad]
        );

        // Obtener el ID del usuario recién insertado
        const userId = result.insertId;

        res.json({ success: true, message: "Registro exitoso", userId: userId, });
    } catch (error) {
        console.error(error);
        res.status(500).json({ success: false, message: "Error en el servidor" });
    }
});

// Iniciar sesión
router.post("/auth/login", async (req, res) => {
    const { email, password } = req.body;

    if (!email || !password) {
        return res.status(400).json({ success: false, message: "Faltan datos" });
    }

    try {
        const [rows] = await db.query(
            "SELECT id, nombres, apellidos, password FROM usuarios WHERE email = ?",
            [email]
        );

        if (rows.length > 0) {
            const user = rows[0];

            if (password === user.password) { // ⚠️ Mejorar con hash en el futuro
                res.json({
                    success: true,
                    message: "Inicio de sesión exitoso",
                    userId: user.id,
                    nombres: user.nombres,
                    apellidos: user.apellidos
                });
            } else {
                res.status(401).json({ success: false, message: "Contraseña incorrecta" });
            }
        } else {
            res.status(404).json({ success: false, message: "Usuario no encontrado" });
        }
    } catch (error) {
        console.error(error);
        res.status(500).json({ success: false, message: "Error en el servidor" });
    }
});

module.exports = router;