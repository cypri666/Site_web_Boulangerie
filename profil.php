<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'boulangerie';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'ID de l'utilisateur est passé dans l'URL
    if (isset($_GET['id'])) {
        $user_id = (int) $_GET['id'];

        // Récupérer les informations de l'utilisateur depuis la base de données
        $sql = "SELECT * FROM clients WHERE ID_Client = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Récupérer l'utilisateur sous forme de tableau
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si l'utilisateur est trouvé
        if ($user) {
            $nom = htmlspecialchars($user['Nom']);
            $prenom = htmlspecialchars($user['Prenom']);
            $email = htmlspecialchars($user['Email']);
            $adresse = htmlspecialchars($user['Adresse']);
            $ville = htmlspecialchars($user['Ville']);
            $code_postal = htmlspecialchars($user['Code_Postal']);
        } else {
            echo "Utilisateur non trouvé.";
            exit();
        }

        // Récupérer l'historique des commandes de l'utilisateur
        $sql_commandes = "SELECT * FROM commandes WHERE client_id = :client_id ORDER BY date_commande DESC";
        $stmt_commandes = $pdo->prepare($sql_commandes);
        $stmt_commandes->bindParam(':client_id', $user_id, PDO::PARAM_INT);
        $stmt_commandes->execute();
        $commandes = $stmt_commandes->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "ID utilisateur manquant.";
        exit();
    }
} catch (PDOException $e) {
    echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
            color: #333;
        }

        .profil-container {
            max-width: 700px;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #FFD700;
            font-size: 2.5rem;
        }

        p {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .profil-info {
            margin-bottom: 20px;
            text-align: left;
        }

        .profil-info p {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: #555;
        }

        .profil-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 3px solid #FFD700;
        }

        .actions {
            margin-top: 20px;
        }

        .actions a {
            text-decoration: none;
            color: white;
            background-color: #FFD700;
            padding: 10px 20px;
            border-radius: 25px;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin: 5px;
        }

        .actions a:hover {
            background-color: #FFC300;
        }

        .order-history {
            margin-top: 30px;
            text-align: left;
        }

        .order-history h2 {
            font-size: 1.8rem;
            color: #FFD700;
        }

        .order-history ul {
            list-style-type: none;
            padding: 0;
        }

        .order-history ul li {
            background-color: #f9f9f9;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        footer {
            margin-top: 40px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="profil-container">
    <h1>Bienvenue, <?php echo "$prenom $nom"; ?></h1>

    <!-- Photo de profil utilisateur (statique ou changeable par l'utilisateur) -->
    <img src="profil_default.png" alt="Photo de profil" class="profil-image">

    <div class="profil-info">
        <p><strong>Email :</strong> <?php echo $email; ?></p>
        <p><strong>Adresse :</strong> <?php echo "$adresse, $ville, $code_postal"; ?></p>
    </div>

    <!-- Actions possibles (Modifier profil, Déconnexion) -->
    <div class="actions">
        <a href="modifier_profil.php?id=<?php echo $user_id; ?>">Modifier le profil</a>
        <a href="deconnexion.php">Se déconnecter</a>
    </div>

    <!-- Historique des commandes (dynamique) -->
    <div class="order-history">
        <h2>Historique des commandes</h2>
        <ul>
            <?php if (!empty($commandes)): ?>
                <?php foreach ($commandes as $commande): ?>
                    <li>
                        Commande #<?php echo htmlspecialchars($commande['id']); ?> -
                        <?php echo htmlspecialchars($commande['date_commande']); ?> -
                        <strong><?php echo htmlspecialchars($commande['montant']); ?>€</strong> -
                        Statut : <?php echo htmlspecialchars($commande['statut']); ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Aucune commande passée.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<footer>
    <p>&copy; 2024 Plaisir et Gourmandise. Tous droits réservés.</p>
</footer>

</body>
</html>
