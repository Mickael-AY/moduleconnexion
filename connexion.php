<?php
require_once 'config.php';

// Redirection si déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$erreur = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($login) || empty($password)) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        // Recherche de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
        $stmt->execute([$login]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur && password_verify($password, $utilisateur['password'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $utilisateur['id'];
            $_SESSION['login'] = $utilisateur['login'];
            $_SESSION['prenom'] = $utilisateur['prenom'];
            $_SESSION['nom'] = $utilisateur['nom'];

            header('Location: index.php');
            exit;
        } else {
            $erreur = "Login ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Multipass</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav>
        <div class="container">
            <div class="logo">🎫 Multipass</div>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="inscription.php">Inscription</a></li>
                <li><a href="connexion.php">Connexion</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <div class="container">
            <div class="form-container">
                <h2>Connexion</h2>

                <?php if (!empty($erreur)): ?>
                    <div class="message error">
                        ❌ <?php echo htmlspecialchars($erreur); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="login">Login</label>
                        <input
                            type="text"
                            id="login"
                            name="login"
                            value="<?php echo htmlspecialchars($_POST['login'] ?? ''); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required>
                    </div>

                    <button type="submit" class="btn btn-full">Se connecter</button>
                </form>

                <p style="text-align: center; margin-top: 1.5rem;">
                    Pas encore de compte ? <a href="inscription.php" style="color: var(--primary-color);">S'inscrire</a>
                </p>

                <div style="margin-top: 2rem; padding: 1rem; background-color: #f8f9fa; border-radius: 5px; text-align: center;">
                    <p style="font-size: 0.9rem; color: #666;">
                        <strong>Compte admin par défaut :</strong><br>
                        Login : admin | Mot de passe : admin
                    </p>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Multipass - Tous droits réservés</p>
        </div>
    </footer>
</body>

</html>