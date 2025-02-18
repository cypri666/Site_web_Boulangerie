require('dotenv').config();
const express = require('express');
const path = require('path');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

const app = express();
app.use(express.json());
app.use(express.static(__dirname)); // Servir les fichiers statiques du même répertoire

// Simule une base de données pour cet exemple
const users = {};

// Vérifiez que la clé JWT_SECRET est bien définie
if (!process.env.JWT_SECRET) {
    console.error("Erreur : La clé JWT_SECRET n'est pas définie dans le fichier .env");
    process.exit(1); // Arrête le serveur si JWT_SECRET est absent
}

// Route pour afficher la page d'inscription/connexion
app.get('/formulaire.html', (req, res) => {
    res.sendFile(path.join(__dirname, 'formulaire.html'));
});

// Route pour afficher la page de compte
app.get('/compte.html', (req, res) => {
    res.sendFile(path.join(__dirname, 'compte.html'));
});

// Route d'inscription
app.post('/signup', async (req, res) => {
    const { username, firstname, lastname, email, password } = req.body;

    if (users[email]) {
        return res.status(400).json({ message: "Cet utilisateur existe déjà." });
    }

    const hashedPassword = await bcrypt.hash(password, 10);
    users[email] = { username, firstname, lastname, email, password: hashedPassword };

    res.json({ message: "Compte créé avec succès." });
});

// Route de connexion
app.post('/login', (req, res) => {
    const { email, password } = req.body;
    const user = users[email];

    if (user && bcrypt.compareSync(password, user.password)) {
        const token = jwt.sign(
            { email, username: user.username, firstname: user.firstname, lastname: user.lastname },
            process.env.JWT_SECRET,
            { expiresIn: '1h' }
        );
        res.json({ success: true, token });
    } else {
        res.status(400).json({ success: false, message: "Identifiants incorrects." });
    }
});

// Route sécurisée pour obtenir les informations de l'utilisateur
app.get('/compte', (req, res) => {
    const token = req.headers['authorization'];
    if (!token) {
        return res.status(401).json({ message: "Token manquant." });
    }

    try {
        const decoded = jwt.verify(token, process.env.JWT_SECRET);
        const user = users[decoded.email];
        if (user) {
            res.json({ success: true, firstname: user.firstname, lastname: user.lastname });
        } else {
            res.status(400).json({ success: false, message: "Utilisateur non trouvé." });
        }
    } catch (error) {
        res.status(400).json({ success: false, message: "Token invalide." });
    }
});

// Démarrer le serveur
const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Le serveur fonctionne sur le port ${PORT}`);
});
