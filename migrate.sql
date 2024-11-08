CREATE USER 'R301_projet'@'%' IDENTIFIED WITH caching_sha2_password BY 'R301_projet';GRANT USAGE ON *.* TO 'R301_projet'@'%';ALTER USER 'R301_projet'@'%' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;

GRANT SELECT, INSERT, UPDATE, DELETE ON `R301\_projet`.* TO 'R301_projet'@'%'; ALTER USER 'R301_projet'@'%' ;

-- Création de la base de données 'R301_projet'
CREATE DATABASE IF NOT EXISTS R301_projet;

-- Utilisation de la base de données 'R301_projet'
USE R301_projet;

-- Table 'joueur' pour stocker les informations des joueurs
CREATE TABLE joueur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    numero_licence VARCHAR(20) UNIQUE NOT NULL,
    date_naissance DATE NOT NULL,
    taille FLOAT,
    poids FLOAT,
    statut ENUM('Actif', 'Blessé', 'Suspendu', 'Absent') DEFAULT 'Actif'
);

CREATE TABLE commentaire (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_joueur INT NOT NULL,
    contenu TEXT,

    -- Définition des clés étrangères
    FOREIGN KEY (id_joueur) REFERENCES joueur(id) ON DELETE CASCADE
);

-- Table 'rencontres' pour stocker les informations des matchs
CREATE TABLE rencontre (
    id INT PRIMARY KEY AUTO_INCREMENT,
    date_heure DATETIME NOT NULL,
    equipe_adverse VARCHAR(50) NOT NULL,
    lieu ENUM('Domicile', 'Extérieur') NOT NULL,
    resultat ENUM('Victoire', 'Défaite', 'Nul') DEFAULT NULL
);

-- Table 'feuille_match' pour stocker la composition de chaque match,
-- incluant le poste joué et l'évaluation du joueur
CREATE TABLE feuille_match (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_rencontre INT NOT NULL,
    id_joueur INT NOT NULL,
    role ENUM('Titulaire', 'Remplaçant') NOT NULL,
    poste VARCHAR(50),
    evaluation INT CHECK(evaluation BETWEEN 1 AND 5),

    -- Définition des clés étrangères
    FOREIGN KEY (id_rencontre) REFERENCES rencontre(id) ON DELETE CASCADE,
    FOREIGN KEY (id_joueur) REFERENCES joueur(id) ON DELETE CASCADE
);

-- Table 'users' pour stocker les informations des utilisateurs (entraineur)
CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);
