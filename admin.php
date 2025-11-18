<?php
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Vérifier si l'utilisateur est admin
if ($_SESSION['login'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT id, login, prenom, nom FROM utilisateurs ORDER BY id ASC");
$utilisateurs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Multipass</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav>
        <div class="container">
            <div class="logo">🎫 Multipass</div>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="profil.php">Mon Profil</a></li>
                <li><a href="admin.php">Administration</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <header>
        <div class="container">
            <h1>🔐 Panneau d'Administration</h1>
            <p>Gestion des utilisateurs du système Multipass</p>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="content-box">
                <h2>Liste des utilisateurs inscrits</h2>
                <p>Nombre total d'utilisateurs : <strong><?php echo count($utilisateurs); ?></strong></p>

                <?php if (empty($utilisateurs)): ?>
                    <p style="text-align: center; margin-top: 2rem; color: #666;">
                        Aucun utilisateur enregistré pour le moment.
                    </p>
                <?php else: ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Login</th>
                                <th>Prénom</th>
                                <th>Nom</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($utilisateurs as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($user['login']); ?>
                                        <?php if ($user['login'] === 'admin'): ?>
                                            <span style="background-color: var(--primary-color); color: white; padding: 2px 8px; border-radius: 3px; font-size: 0.8rem; margin-left: 5px;">ADMIN</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                                    <td><?php echo htmlspecialchars($user['nom']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="content-box">
                <h3>Informations</h3>
                <p>Cette page est accessible uniquement pour l'utilisateur <strong>admin</strong>.</p>
                <p>Vous pouvez consulter la liste complète des utilisateurs inscrits sur la plateforme Multipass.</p>
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