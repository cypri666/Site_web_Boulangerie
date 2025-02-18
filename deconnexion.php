<?php
// Détruire la session
session_start();
session_destroy();

// Rediriger vers la page d'accueil ou une page de connexion
header("Location: Accueil.html");
exit();
?>
