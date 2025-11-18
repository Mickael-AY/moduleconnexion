-- Base de données : moduleconnexion
-- ====================================

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS moduleconnexion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Utilisation de la base de données
USE moduleconnexion;

-- ====================================
-- Table : utilisateurs
-- ====================================

CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    login VARCHAR(255) NOT NULL UNIQUE,
    prenom VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Insertion de l'utilisateur admin
-- ====================================

-- Mot de passe : admin (hashé avec password_hash)
INSERT INTO utilisateurs (login, prenom, nom, password) VALUES 
('admin', 'admin', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Note : Le mot de passe 'admin' est hashé avec password_hash() de PHP
-- Pour se connecter : login = admin, password = admin
