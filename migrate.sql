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


INSERT INTO joueur (prenom, numero_licence, date_naissance, taille, poids, statut) VALUES 
('Dupont', 'Lucas', 'LIC123456', '2000-05-15', 1.82, 75, 'Actif'),
('Martin', 'Maxime', 'LIC234567', '1998-08-20', 1.76, 72, 'Blessé'),
('Durand', 'Thomas', 'LIC345678', '1999-12-11', 1.80, 78, 'Actif'),
('Bernard', 'Antoine', 'LIC456789', '2001-03-25', 1.90, 85, 'Suspendu'),
('Petit', 'Nicolas', 'LIC567890', '2000-07-10', 1.78, 74, 'Actif'),
('Lefevre', 'Matthieu', 'LIC678901', '1997-11-30', 1.83, 80, 'Absent'),
('Moreau', 'Julien', 'LIC789012', '1999-02-17', 1.77, 76, 'Actif'),
('Laurent', 'Yann', 'LIC890123', '1998-06-05', 1.88, 82, 'Blessé'),
('Simon', 'Bastien', 'LIC901234', '2001-04-22', 1.85, 79, 'Actif'),
('Michel', 'Florent', 'LIC012345', '1996-09-18', 1.72, 70, 'Actif'),
('Lemoine', 'Hugo', 'LIC112345', '2000-10-12', 1.89, 83, 'Actif'),
('Rousseau', 'Alexandre', 'LIC122345', '1999-03-28', 1.81, 77, 'Suspendu'),
('Blanc', 'Quentin', 'LIC132345', '1997-01-09', 1.75, 73, 'Actif'),
('Fournier', 'Adrien', 'LIC142345', '2002-05-19', 1.87, 78, 'Absent'),
('Girard', 'Dylan', 'LIC152345', '2001-12-02', 1.84, 80, 'Actif'),
('Perrin', 'Leo', 'LIC162345', '1998-08-30', 1.70, 68, 'Blessé')
('Morin', 'Paul', 'LIC172345', '1997-04-13', 1.79, 75, 'Actif'),
('Robert', 'Kevin', 'LIC182345', '1999-06-25', 1.82, 78, 'Blessé'),
('Petit', 'Samuel', 'LIC192345', '2001-01-15', 1.76, 74, 'Suspendu'),
('Carre', 'Victor', 'LIC202345', '2000-09-30', 1.80, 77, 'Actif'),
('Renaud', 'Louis', 'LIC212345', '1998-11-07', 1.85, 81, 'Absent'),
('Gauthier', 'Arnaud', 'LIC222345', '1996-12-10', 1.88, 84, 'Actif'),
('Dufour', 'Thibault', 'LIC232345', '2002-02-20', 1.73, 70, 'Actif'),
('Mercier', 'Enzo', 'LIC242345', '2000-05-17', 1.78, 76, 'Blessé'),
('Morel', 'Raphael', 'LIC252345', '2001-08-12', 1.83, 80, 'Actif'),
('Lambert', 'Gabriel', 'LIC262345', '1997-03-04', 1.89, 85, 'Actif'),
('Gomez', 'Evan', 'LIC272345', '1999-10-01', 1.75, 73, 'Suspendu'),
('Faure', 'Theo', 'LIC282345', '1998-06-18', 1.81, 77, 'Actif'),
('Guillot', 'Simon', 'LIC292345', '1996-09-29', 1.70, 68, 'Absent'),
('Dupuis', 'Benjamin', 'LIC302345', '2002-07-21', 1.86, 82, 'Actif'),
('Chevalier', 'Malo', 'LIC312345', '1999-05-14', 1.84, 79, 'Blessé'),
('Moulin', 'Arthur', 'LIC322345', '2000-12-19', 1.77, 74, 'Actif');