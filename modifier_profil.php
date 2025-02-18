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

        // Récupérer les informations de l'utilisateur
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Récupérer les nouvelles données du formulaire
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $adresse = htmlspecialchars($_POST['adresse']);
            $ville = htmlspecialchars($_POST['ville']);
            $code_postal = htmlspecialchars($_POST['code_postal']);

            // Mettre à jour les informations
            $sql = "UPDATE clients SET Nom = :nom, Prenom = :prenom, Email = :email, Adresse = :adresse, Ville = :ville, Code_Postal = :code_postal WHERE ID_Client = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':ville', $ville);
            $stmt->bindParam(':code_postal', $code_postal);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Redirection vers le profil après mise à jour
                header("Location: profil.php?id=$user_id");
                exit();
            } else {
                echo "Erreur lors de la mise à jour.";
            }
        } else {
            // Récupérer les informations actuelles pour les afficher dans le formulaire
            $sql = "SELECT * FROM clients WHERE ID_Client = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                echo "Utilisateur non trouvé.";
                exit();
            }
        }
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
    <title>Modifier Profil</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
            color: #333;
        }

        .profil-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #FFD700;
            font-size: 2.5rem;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 1.1rem;
            color: #333;
        }

        input[type="text"], input[type="email"] {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        input[type="submit"] {
            padding: 12px;
            font-size: 1.2rem;
            background-color: #FFD700;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #FFC300;
        }
    </style>
</head>
<body>

<div class="profil-container">
    <h1>Modifier Profil</h1>
    <form action="modifier_profil.php?id=<?php echo $user_id; ?>" method="POST">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user['Nom']); ?>" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['Prenom']); ?>" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>

        <label for="adresse">Adresse :</label>
        <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($user['Adresse']); ?>" required>

        <label for="ville">Ville :</label>
        <input type="text" id="ville" name="ville" value="<?php echo htmlspecialchars($user['Ville']); ?>" required>

        <label for="code_postal">Code Postal :</label>
        <input type="text" id="code_postal" name="code_postal" value="<?php echo htmlspecialchars($user['Code_Postal']); ?>" required>

        <input type="submit" value="Mettre à jour">
    </form>
</div>

</body>
</html>
