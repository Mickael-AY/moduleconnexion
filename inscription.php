<?php
require_once 'config.php';

$erreurs = [];
$success = false;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validation
    if (empty($login)) {
        $erreurs[] = "Le login est obligatoire.";
    }

    if (empty($prenom)) {
        $erreurs[] = "Le prénom est obligatoire.";
    }

    if (empty($nom)) {
        $erreurs[] = "Le nom est obligatoire.";
    }

    if (empty($password)) {
        $erreurs[] = "Le mot de passe est obligatoire.";
    } elseif (strlen($password) < 6) {
        $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    if ($password !== $password_confirm) {
        $erreurs[] = "Les mots de passe ne correspondent pas.";
    }

    // Vérifier si le login existe déjà
    if (empty($erreurs)) {
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            $erreurs[] = "Ce login est déjà utilisé.";
        }
    }

    // Insertion en base de données
    if (empty($erreurs)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (login, prenom, nom, password) VALUES (?, ?, ?, ?)");

        if ($stmt->execute([$login, $prenom, $nom, $password_hash])) {
            $success = true;
            // Redirection après 2 secondes
            header("refresh:2;url=connexion.php");
        } else {
            $erreurs[] = "Une erreur est survenue lors de l'inscription.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Multipass</title>
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
                <h2>Créer un compte</h2>

                <?php if ($success): ?>
                    <div class="message success">
                        ✅ Inscription réussie ! Redirection vers la page de connexion...
                    </div>
                <?php endif; ?>

                <?php if (!empty($erreurs)): ?>
                    <div class="message error">
                        <?php foreach ($erreurs as $erreur): ?>
                            <p>❌ <?php echo htmlspecialchars($erreur); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!$success): ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="login">Login *</label>
                            <input
                                type="text"
                                id="login"
                                name="login"
                                value="<?php echo htmlspecialchars($_POST['login'] ?? ''); ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="prenom">Prénom *</label>
                            <input
                                type="text"
                                id="prenom"
                                name="prenom"
                                value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="password">Mot de passe * (6 caractères minimum)</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirm">Confirmation du mot de passe *</label>
                            <input
                                type="password"
                                id="password_confirm"
                                name="password_confirm"
                                required>
                        </div>

                        <button type="submit" class="btn btn-full">S'inscrire</button>
                    </form>

                    <p style="text-align: center; margin-top: 1.5rem;">
                        Vous avez déjà un compte ? <a href="connexion.php" style="color: var(--primary-color);">Se connecter</a>
                    </p>
                <?php endif; ?>
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