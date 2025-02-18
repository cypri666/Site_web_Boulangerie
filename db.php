<?php
// Informations de connexion à la base de données
$host = 'localhost';  // 'localhost' ou '127.0.0.1'
$dbname = 'boulangerie';  // Nom de la base de données que vous avez créée
$username = 'root';  // Utilisateur MySQL (souvent 'root' en local)
$password = '';  // Mot de passe MySQL (généralement vide en local avec WAMP)

// Activer le rapport d'erreurs
ini_set('display_errors', 1);  // Affiche les erreurs à l'écran
error_reporting(E_ALL);  // Niveau maximal de rapport d'erreurs

try {
    // Connexion à MySQL via PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Activer les exceptions en cas d'erreur
    echo "Connexion réussie à la base de données.";  // Pour tester que la connexion fonctionne
} catch (PDOException $e) {
    // Gestion des erreurs de connexion
    // Le message d'erreur complet sera affiché à l'écran, utile pour le débogage
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
