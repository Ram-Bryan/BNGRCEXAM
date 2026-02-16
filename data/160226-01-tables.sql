CREATE TABLE region (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE ville (
    id INT PRIMARY KEY AUTO_INCREMENT,
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
    idbesoins INT NOT NULL,
    quantite INT NOT NULL,
    date_livraison DATE NOT NULL,
    FOREIGN KEY (idbesoins) REFERENCES besoin(id)
);