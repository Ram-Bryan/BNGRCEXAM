CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) NOT NULL UNIQUE,
    libelle VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    tel VARCHAR(15),
    role_id INT NOT NULL,

    UNIQUE (email),
    INDEX idx_utilisateur_role (role_id),

    CONSTRAINT fk_utilisateur_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL,
    symbole VARCHAR(50),
    description TEXT,

    UNIQUE (libelle)
);

CREATE TABLE IF NOT EXISTS etats_objet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) NOT NULL UNIQUE,
    libelle VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS statuts_objet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) NOT NULL UNIQUE,
    libelle VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS objets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proprietaire_id INT NOT NULL,
    categorie_id INT NOT NULL,
    etat_id INT NOT NULL, -- NOUVEAU, USE
    statut_id INT NOT NULL, -- DISPONIBLE, RESERVE
    titre VARCHAR(150) NOT NULL,
    description TEXT,
    prix_estime DECIMAL(10,2),

    INDEX idx_objet_proprietaire (proprietaire_id),
    INDEX idx_objet_categorie (categorie_id),
    INDEX idx_objet_statut (statut_id),

    CONSTRAINT fk_objet_proprietaire
        FOREIGN KEY (proprietaire_id) REFERENCES utilisateurs(id),

    CONSTRAINT fk_objet_categorie
        FOREIGN KEY (categorie_id) REFERENCES categories(id),

    CONSTRAINT fk_objet_etat
        FOREIGN KEY (etat_id) REFERENCES etats_objet(id),

    CONSTRAINT fk_objet_statut
        FOREIGN KEY (statut_id) REFERENCES statuts_objet(id)
);

CREATE TABLE IF NOT EXISTS photos_objet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    objet_id INT NOT NULL,
    chemin VARCHAR(255) NOT NULL,
    ordre INT NOT NULL,
    est_principale BOOLEAN NOT NULL DEFAULT FALSE,

    INDEX idx_photo_objet (objet_id),
    INDEX idx_photo_ordre (objet_id, ordre),

    CONSTRAINT fk_photo_objet
        FOREIGN KEY (objet_id) REFERENCES objets(id)
);

CREATE TABLE IF NOT EXISTS statuts_echange (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) NOT NULL UNIQUE,
    libelle VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS echanges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    demandeur_id INT NOT NULL,
    receveur_id INT NOT NULL,
    statut_id INT NOT NULL,
    date_demande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_reponse DATETIME NULL,

    INDEX idx_echange_demandeur (demandeur_id),
    INDEX idx_echange_receveur (receveur_id),
    INDEX idx_echange_statut (statut_id),

    CONSTRAINT fk_echange_demandeur
        FOREIGN KEY (demandeur_id) REFERENCES utilisateurs(id),

    CONSTRAINT fk_echange_receveur
        FOREIGN KEY (receveur_id) REFERENCES utilisateurs(id),

    CONSTRAINT fk_echange_statut
        FOREIGN KEY (statut_id) REFERENCES statuts_echange(id)
);

CREATE TABLE IF NOT EXISTS echange_objets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    echange_id INT NOT NULL,
    objet_id INT NOT NULL,
    direction ENUM('OFFERT','DEMANDE') NOT NULL,

    INDEX idx_echange_objet (echange_id),
    INDEX idx_objet_echange (objet_id),

    CONSTRAINT fk_echange_objets_echange
        FOREIGN KEY (echange_id) REFERENCES echanges(id),

    CONSTRAINT fk_echange_objets_objet
        FOREIGN KEY (objet_id) REFERENCES objets(id)
);

CREATE TABLE IF NOT EXISTS historique_proprietaire_objet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    objet_id INT NOT NULL,
    utilisateur_id INT NOT NULL,
    echange_id INT NULL,
    date_acquisition DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_historique_objet (objet_id, date_acquisition),

    CONSTRAINT fk_historique_objet
        FOREIGN KEY (objet_id) REFERENCES objets(id),

    CONSTRAINT fk_historique_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),

    CONSTRAINT fk_historique_echange
        FOREIGN KEY (echange_id) REFERENCES echanges(id)
);

CREATE VIEW IF NOT EXISTS v_echanges_utilisateur AS
SELECT
    e.id AS echange_id,
    e.date_demande,
    e.date_reponse,
    se.code AS statut,
    u1.nom AS demandeur,
    u2.nom AS receveur
FROM echanges e
JOIN utilisateurs u1 ON u1.id = e.demandeur_id
JOIN utilisateurs u2 ON u2.id = e.receveur_id
JOIN statuts_echange se ON se.id = e.statut_id;

CREATE VIEW IF NOT EXISTS v_historique_objet AS
SELECT
    h.objet_id,
    o.titre,
    u.nom AS proprietaire,
    h.date_acquisition
FROM historique_proprietaire_objet h
JOIN utilisateurs u ON u.id = h.utilisateur_id
JOIN objets o ON o.id = h.objet_id
ORDER BY h.date_acquisition;
