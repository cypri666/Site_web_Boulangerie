<?php
// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";  // Nom d'utilisateur par défaut dans WAMP
$password = "";      // Mot de passe par défaut (vide si non modifié)
$dbname = "boulangerie";  // Nom de la base de données que vous avez créée

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>
