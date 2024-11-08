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


INSERT INTO joueur (id_joueur, prenom, numero_licence, date_naissance, taille, poids, statut) VALUES 
(1, 'Dupont', 'Lucas', 'LIC123456', '2000-05-15', 1.82, 75, 'Actif'),
(2, 'Martin', 'Maxime', 'LIC234567', '1998-08-20', 1.76, 72, 'Blessé'),
(3, 'Durand', 'Thomas', 'LIC345678', '1999-12-11', 1.80, 78, 'Actif'),
(4, 'Bernard', 'Antoine', 'LIC456789', '2001-03-25', 1.90, 85, 'Suspendu'),
(5, 'Petit', 'Nicolas', 'LIC567890', '2000-07-10', 1.78, 74, 'Actif'),
(6, 'Lefevre', 'Matthieu', 'LIC678901', '1997-11-30', 1.83, 80, 'Absent'),
(7, 'Moreau', 'Julien', 'LIC789012', '1999-02-17', 1.77, 76, 'Actif'),
(8, 'Laurent', 'Yann', 'LIC890123', '1998-06-05', 1.88, 82, 'Blessé'),
(9, 'Simon', 'Bastien', 'LIC901234', '2001-04-22', 1.85, 79, 'Actif'),
(10, 'Michel', 'Florent', 'LIC012345', '1996-09-18', 1.72, 70, 'Actif'),
(11, 'Lemoine', 'Hugo', 'LIC112345', '2000-10-12', 1.89, 83, 'Actif'),
(12, 'Rousseau', 'Alexandre', 'LIC122345', '1999-03-28', 1.81, 77, 'Suspendu'),
(13, 'Blanc', 'Quentin', 'LIC132345', '1997-01-09', 1.75, 73, 'Actif'),
(14, 'Fournier', 'Adrien', 'LIC142345', '2002-05-19', 1.87, 78, 'Absent'),
(15, 'Girard', 'Dylan', 'LIC152345', '2001-12-02', 1.84, 80, 'Actif'),
(16, 'Perrin', 'Leo', 'LIC162345', '1998-08-30', 1.70, 68, 'Blessé')
(17, 'Morin', 'Paul', 'LIC172345', '1997-04-13', 1.79, 75, 'Actif'),
(18, 'Robert', 'Kevin', 'LIC182345', '1999-06-25', 1.82, 78, 'Blessé'),
(19, 'Petit', 'Samuel', 'LIC192345', '2001-01-15', 1.76, 74, 'Suspendu'),
(20, 'Carre', 'Victor', 'LIC202345', '2000-09-30', 1.80, 77, 'Actif'),
(21, 'Renaud', 'Louis', 'LIC212345', '1998-11-07', 1.85, 81, 'Absent'),
(22, 'Gauthier', 'Arnaud', 'LIC222345', '1996-12-10', 1.88, 84, 'Actif'),
(23, 'Dufour', 'Thibault', 'LIC232345', '2002-02-20', 1.73, 70, 'Actif'),
(24, 'Mercier', 'Enzo', 'LIC242345', '2000-05-17', 1.78, 76, 'Blessé'),
(25, 'Morel', 'Raphael', 'LIC252345', '2001-08-12', 1.83, 80, 'Actif'),
(26, 'Lambert', 'Gabriel', 'LIC262345', '1997-03-04', 1.89, 85, 'Actif'),
(27, 'Gomez', 'Evan', 'LIC272345', '1999-10-01', 1.75, 73, 'Suspendu'),
(28, 'Faure', 'Theo', 'LIC282345', '1998-06-18', 1.81, 77, 'Actif'),
(29, 'Guillot', 'Simon', 'LIC292345', '1996-09-29', 1.70, 68, 'Absent'),
(30, 'Dupuis', 'Benjamin', 'LIC302345', '2002-07-21', 1.86, 82, 'Actif'),
(31, 'Chevalier', 'Malo', 'LIC312345', '1999-05-14', 1.84, 79, 'Blessé'),
(32, 'Moulin', 'Arthur', 'LIC322345', '2000-12-19', 1.77, 74, 'Actif');

INSERT INTO commentaire (id_joueur, contenu) VALUES
(1, 'Joueur fiable et très engagé sur le terrain.'),
(2, 'Blessé fréquemment, a besoin de renforcement physique.'),
(1, 'Excellente endurance et bonne vision du jeu.'),
(4, 'Suspendu pour comportement non professionnel.'),
(5, 'Très rapide, spécialiste des contres-attaques.'),
(6, 'Absent pour raisons personnelles, toujours régulier sinon.'),
(7, 'Capable de jouer plusieurs positions, très polyvalent.'),
(8, 'Blessé au genou, en phase de rééducation.'),
(9, 'Excellent défenseur, solide dans les duels.'),
(10, 'Très bon dribbleur, manque cependant de précision.'),
(1, 'Actif et disponible, joueur de confiance.'),
(12, 'Suspendu pour accumulation de cartons jaunes.'),
(13, 'Très efficace dans les passes, mais manque d’agressivité.'),
(6, 'Absent pour raisons personnelles, joueur d’avenir.'),
(15, 'Attaquant régulier, inscrit souvent des buts décisifs.'),
(16, 'Blessé récemment, en attente de retour sur le terrain.'),
(17, 'Joueur constant et fiable dans la défense.'),
(18, 'Blessé aux ischio-jambiers, sera absent quelques semaines.'),
(19, 'Suspendu pour conduite antisportive.'),
(20, 'Joueur rapide et efficace dans les transitions.'),
(21, 'Absent pour des raisons médicales, retour prévu prochainement.'),
(22, 'Bonne condition physique, joueur expérimenté.'),
(23, 'Jeune joueur prometteur, en progression rapide.'),
(24, 'Blessé à la cheville, en traitement actuellement.'),
(25, 'Joueur polyvalent, peut jouer en milieu et défense.'),
(26, 'Excellent dans les airs, très bon jeu de tête.'),
(27, 'Suspendu pour comportement agressif en match.'),
(28, 'Milieu de terrain, bonne maîtrise technique.'),
(29, 'Absent pour des raisons familiales, très bon coéquipier.'),
(30, 'Attaquant très rapide, excellent dans les finitions.'),
(31, 'Blessé à la hanche, en rééducation pour quelques mois.'),
(32, 'Joueur énergique, apporte beaucoup de dynamisme.');
