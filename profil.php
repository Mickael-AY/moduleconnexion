<?php
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$erreurs = [];
$success = false;

// Récupérer les informations actuelles de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$utilisateur = $stmt->fetch();

if (!$utilisateur) {
    header('Location: logout.php');
    exit;
}

// Traitement du formulaire de modification
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

    // Vérifier si le login existe déjà (sauf pour l'utilisateur actuel)
    if (empty($erreurs) && $login !== $utilisateur['login']) {
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE login = ? AND id != ?");
        $stmt->execute([$login, $_SESSION['user_id']]);
        if ($stmt->fetch()) {
            $erreurs[] = "Ce login est déjà utilisé.";
        }
    }

    // Validation du mot de passe si renseigné
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }
        if ($password !== $password_confirm) {
            $erreurs[] = "Les mots de passe ne correspondent pas.";
        }
    }

    // Mise à jour en base de données
    if (empty($erreurs)) {
        if (!empty($password)) {
            // Mise à jour avec nouveau mot de passe
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE utilisateurs SET login = ?, prenom = ?, nom = ?, password = ? WHERE id = ?");
            $result = $stmt->execute([$login, $prenom, $nom, $password_hash, $_SESSION['user_id']]);
        } else {
            // Mise à jour sans changer le mot de passe
            $stmt = $pdo->prepare("UPDATE utilisateurs SET login = ?, prenom = ?, nom = ? WHERE id = ?");
            $result = $stmt->execute([$login, $prenom, $nom, $_SESSION['user_id']]);
        }

        if ($result) {
            $success = true;
            // Mettre à jour les variables de session
            $_SESSION['login'] = $login;
            $_SESSION['prenom'] = $prenom;
            $_SESSION['nom'] = $nom;

            // Recharger les données
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $utilisateur = $stmt->fetch();
        } else {
            $erreurs[] = "Une erreur est survenue lors de la mise à jour.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Multipass</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav>
        <div class="container">
            <div class="logo">🎫 Multipass</div>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="profil.php">Mon Profil</a></li>
                <?php if ($_SESSION['login'] === 'admin'): ?>
                    <li><a href="admin.php">Administration</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <div class="container">
            <div class="form-container">
                <h2>Mon Profil</h2>

                <?php if ($success): ?>
                    <div class="message success">
                        ✅ Profil mis à jour avec succès !
                    </div>
                <?php endif; ?>

                <?php if (!empty($erreurs)): ?>
                    <div class="message error">
                        <?php foreach ($erreurs as $erreur): ?>
                            <p>❌ <?php echo htmlspecialchars($erreur); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="login">Login *</label>
                        <input
                            type="text"
                            id="login"
                            name="login"
                            value="<?php echo htmlspecialchars($utilisateur['login']); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom *</label>
                        <input
                            type="text"
                            id="prenom"
                            name="prenom"
                            value="<?php echo htmlspecialchars($utilisateur['prenom']); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="nom">Nom *</label>
                        <input
                            type="text"
                            id="nom"
                            name="nom"
                            value="<?php echo htmlspecialchars($utilisateur['nom']); ?>"
                            required>
                    </div>

                    <hr style="margin: 2rem 0; border: none; border-top: 2px solid var(--light-color);">

                    <p style="text-align: center; margin-bottom: 1rem; color: #666;">
                        Laissez vide si vous ne souhaitez pas changer le mot de passe
                    </p>

                    <div class="form-group">
                        <label for="password">Nouveau mot de passe (6 caractères minimum)</label>
                        <input
                            type="password"
                            id="password"
                            name="password">
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">Confirmation du nouveau mot de passe</label>
                        <input
                            type="password"
                            id="password_confirm"
                            name="password_confirm">
                    </div>

                    <button type="submit" class="btn btn-full">Mettre à jour mon profil</button>
                </form>
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