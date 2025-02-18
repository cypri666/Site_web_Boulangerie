<?php
// Inclure le fichier de connexion à la base de données
include 'connection.php';

// Requête pour sélectionner les produits dans la base de données
$sql = "SELECT * FROM Produits";
$result = $conn->query($sql);

// Vérifier si des résultats ont été trouvés
if ($result->num_rows > 0) {
    // Afficher les produits
    while($row = $result->fetch_assoc()) {
        echo "<h2>" . $row["Nom_Produit"] . "</h2>";
        echo "<p>Description : " . $row["Description"] . "</p>";
        echo "<p>Prix : " . $row["Prix"] . " €</p>";
        echo "<p>Quantité disponible : " . $row["Quantité_Stock"] . "</p>";
        echo "<img src='" . $row["Image_URL"] . "' alt='" . $row["Nom_Produit"] . "'>";
        echo "<hr>";
    }
} else {
    echo "Aucun produit trouvé";
}

// Fermer la connexion à la base de données
$conn->close();
?>
