<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module de connexion</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav>
        <div class="container">
            <div class="logo">🎫 Multipass</div>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="profil.php">Mon Profil</a></li>
                    <?php if ($_SESSION['login'] === 'admin'): ?>
                        <li><a href="admin.php">Administration</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="inscription.php">Inscription</a></li>
                    <li><a href="connexion.php">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <header>
        <div class="container">
            <h1>Module de connexion</h1>
            <p>Votre système de gestion d'identité sécurisé</p>
        </div>
    </header>

    <main>
     
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="content-box">
                    <h2>Bonjour <?php echo htmlspecialchars($_SESSION['prenom']); ?> <?php echo htmlspecialchars($_SESSION['nom']); ?> !</h2>
                    <p>Vous êtes connecté(e) avec le login : <strong><?php echo htmlspecialchars($_SESSION['login']); ?></strong></p>
                    <p>
                        <a href="profil.php" class="btn">Voir mon profil</a>
                        <?php if ($_SESSION['login'] === 'admin'): ?>
                            <a href="admin.php" class="btn btn-secondary">Panneau d'administration</a>
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="content-box">
                    <h2>Commencez dès maintenant</h2>
                    <p>
                        <a href="inscription.php" class="btn">Créer un compte</a>
                        <a href="connexion.php" class="btn btn-secondary">Se connecter</a>
                    </p>
                </div>
            <?php endif; ?>

            <div class="content-box">
                <h2>Fonctionnalités</h2>
                <ul style="list-style-position: inside; line-height: 2;">
                    <li>✅ Inscription sécurisée avec validation</li>
                    <li>✅ Connexion avec gestion de session</li>
                    <li>✅ Modification de profil</li>
                    <li>✅ Panneau d'administration (réservé aux admins)</li>
                    <li>✅ Design moderne et responsive</li>
                </ul>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Multipass - Tous droits réservés | "Leeloo Dallas Multipass!"</p>
        </div>
    </footer>
</body>

</html>