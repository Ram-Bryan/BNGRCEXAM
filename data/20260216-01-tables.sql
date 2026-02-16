CREATE TABLE region (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE ville (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    idregion INT NOT NULL,
    nbsinistres INT DEFAULT 0,
    FOREIGN KEY (idregion) REFERENCES region(id)
);

CREATE TABLE type_articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    categorie ENUM('nature', 'argent', 'material') NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    unite VARCHAR(50) NOT NULL
);

CREATE TABLE besoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ville_id INT NOT NULL,
    type_article_id INT NOT NULL,
    quantite INT NOT NULL,
    date_demande DATE NOT NULL,
    FOREIGN KEY (ville_id) REFERENCES ville(id),
    FOREIGN KEY (type_article_id) REFERENCES type_articles(id)
);

CREATE TABLE dons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type_article_id INT NOT NULL,
    quantite INT NOT NULL,
    date_don DATE NOT NULL,
    donateur VARCHAR(200) DEFAULT 'Anonyme',
    statut ENUM('disponible', 'distribue') DEFAULT 'disponible',
    FOREIGN KEY (type_article_id) REFERENCES type_articles(id)
);

CREATE TABLE distribution (
    id INT PRIMARY KEY AUTO_INCREMENT,
    don_id INT NOT NULL,
    besoin_id INT NOT NULL,
    quantite INT NOT NULL,
    date_distribution DATE NOT NULL,
    est_simulation BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (don_id) REFERENCES dons(id) ON DELETE CASCADE,
    FOREIGN KEY (besoin_id) REFERENCES besoin(id) ON DELETE CASCADE
);

CREATE TABLE historique_besoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    besoin_id INT NOT NULL,
    quantite INT NOT NULL,
    date_enregistrement DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (besoin_id) REFERENCES besoin(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS achat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    besoin_id INT NOT NULL,
    quantite INT NOT NULL,
    montant_ht DECIMAL(15, 2) NOT NULL,
    frais_percent DECIMAL(5, 2) NOT NULL,
    montant_frais DECIMAL(15, 2) NOT NULL,
    montant_total DECIMAL(15, 2) NOT NULL,
    date_achat DATE NOT NULL,
    valide BOOLEAN DEFAULT FALSE,
    date_validation DATETIME NULL,
    FOREIGN KEY (besoin_id) REFERENCES besoin(id) ON DELETE CASCADE
);