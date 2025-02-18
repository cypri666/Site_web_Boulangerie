<?php
// Connexion à la base de données
$host = '127.0.0.1';  // Utilisez 127.0.0.1 au lieu de localhost pour éviter des problèmes de socket
$port = '3306'; // Le port par défaut de MySQL
$dbname = 'boulangerie';  // Nom de la base de données
$username = 'root';  // Nom d'utilisateur MySQL
$password = '';  // Mot de passe MySQL (laisser vide si aucun mot de passe n'est configuré)

try {
    // Connexion à MySQL avec PDO
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Définir le mode d'erreur de PDO sur Exception pour plus de contrôle

    // Vérifier si une requête POST a été envoyée (soumission du formulaire d'inscription)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Récupérer les données du formulaire en les protégeant des attaques XSS avec htmlspecialchars()
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $email = htmlspecialchars($_POST['email']);
        $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);  // Hachage du mot de passe
        $adresse = htmlspecialchars($_POST['adresse']);
        $ville = htmlspecialchars($_POST['ville']);
        $code_postal = htmlspecialchars($_POST['code_postal']);

        // Vérifier si l'adresse e-mail existe déjà dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            // Si un utilisateur avec cet email existe déjà
            echo "Cet e-mail est déjà utilisé. Veuillez choisir un autre e-mail.";
        } else {
            // Préparer la requête d'insertion dans la table 'clients'
            $sql = "INSERT INTO clients (Nom, Prenom, Email, Mot_de_passe, Adresse, Ville, Code_Postal) 
                    VALUES (:nom, :prenom, :email, :mot_de_passe, :adresse, :ville, :code_postal)";
            $stmt = $pdo->prepare($sql);

            // Lier les valeurs aux paramètres de la requête
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mot_de_passe', $mot_de_passe);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':ville', $ville);
            $stmt->bindParam(':code_postal', $code_postal);

            // Exécuter la requête
            if ($stmt->execute()) {
                // Récupérer l'ID de l'utilisateur récemment créé
                $user_id = $pdo->lastInsertId();

                // Rediriger l'utilisateur vers la page de profil avec son ID
                header("Location: profil.php?id=$user_id");
                exit();  // Terminer le script ici après la redirection pour éviter l'exécution de code supplémentaire
            } else {
                echo "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }
    }
} catch (PDOException $e) {
    // En cas d'erreur de connexion ou de requête SQL
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>
