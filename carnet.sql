-- =============================================
-- Fichier: carnet.sql
-- Description: Script complet d'installation
-- =============================================

-- 1. Création de la base de données
CREATE DATABASE IF NOT EXISTS carnet_db;
USE carnet_db;

-- =============================================
-- 2. Table des contacts (CARNET)
-- =============================================
CREATE TABLE IF NOT EXISTS CARNET (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    adresse TEXT,
    photo VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- 3. Table des utilisateurs (authentification)
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- 4. Table des logs (journal d'activité)
-- =============================================
CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    user_nom VARCHAR(100) NOT NULL,
    action VARCHAR(50) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =============================================
-- 5. Insertion d'un administrateur par défaut
--    Mot de passe: password
--    (hash généré avec password_hash('password', PASSWORD_DEFAULT))
-- =============================================
INSERT INTO users (nom, email, password, role) VALUES 
('Administrateur', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- =============================================
-- 6. Insertion d'un utilisateur normal (optionnel)
--    Mot de passe: password
-- =============================================
INSERT INTO users (nom, email, password, role) VALUES 
('Utilisateur Normal', 'user@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- =============================================
-- 7. Données de test (contacts) - optionnel
-- =============================================
INSERT INTO CARNET (nom, prenom, telephone, email, adresse, created_at) VALUES
('Dupont', 'Jean', '0612345678', 'jean.dupont@email.com', '12 rue de Paris, 75001 Paris', NOW()),
('Martin', 'Sophie', '0623456789', 'sophie.martin@email.com', '45 avenue des Fleurs, 69002 Lyon', NOW()),
('Bernard', 'Lucas', '0634567890', 'lucas.bernard@email.com', '8 boulevard de la Mer, 13008 Marseille', NOW()),
('Petit', 'Julie', '0645678901', 'julie.petit@email.com', '3 rue des Écoles, 31000 Toulouse', NOW()),
('Robert', 'Thomas', '0656789012', 'thomas.robert@email.com', '22 place de la Gare, 44000 Nantes', NOW()),
('Richard', 'Emma', '0667890123', 'emma.richard@email.com', '17 chemin des Vignes, 33000 Bordeaux', NOW()),
('Durand', 'Paul', '0678901234', 'paul.durand@email.com', '91 rue Nationale, 59000 Lille', NOW()),
('Dubois', 'Clara', '0689012345', 'clara.dubois@email.com', '5 impasse des Lilas, 67000 Strasbourg', NOW()),
('Moreau', 'Antoine', '0690123456', 'antoine.moreau@email.com', '34 avenue de la République, 13001 Marseille', NOW()),
('Simon', 'Camille', '0601234567', 'camille.simon@email.com', '76 rue Victor Hugo, 69003 Lyon', NOW());

-- =============================================
-- 8. Quelques logs de test (optionnel)
-- =============================================
INSERT INTO logs (user_id, user_nom, action, details, ip_address) VALUES
(1, 'Administrateur', 'LOGIN', 'Connexion réussie', '127.0.0.1'),
(1, 'Administrateur', 'CREATE', 'Ajout contact: Jean Dupont', '127.0.0.1'),
(1, 'Administrateur', 'CREATE', 'Ajout contact: Sophie Martin', '127.0.0.1'),
(1, 'Administrateur', 'UPDATE', 'Modifié contact: Jean Dupont (ID: 1)', '127.0.0.1'),
(2, 'Utilisateur Normal', 'LOGIN', 'Connexion réussie', '127.0.0.1');

-- =============================================
-- 9. Vérification (afficher les tables)
-- =============================================
SHOW TABLES;
SELECT '=== Table CARNET ===' as '';
SELECT * FROM CARNET;
SELECT '=== Table users ===' as '';
SELECT id, nom, email, role FROM users;
SELECT '=== Table logs ===' as '';
SELECT * FROM logs LIMIT 5;