-- ============================================================
-- Tables et vues supplémentaires pour les achats
-- ============================================================

-- Table des achats (utilisation de dons argent pour acheter des besoins en nature/material)
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

-- Vue des achats avec détails
CREATE OR REPLACE VIEW vue_achats_complets AS
SELECT 
    a.id,
    a.besoin_id,
    a.quantite,
    a.montant_ht,
    a.frais_percent,
    a.montant_frais,
    a.montant_total,
    a.date_achat,
    a.valide,
    a.date_validation,
    b.ville_id,
    b.type_article_id,
    b.quantite AS quantite_besoin,
    b.date_demande,
    v.nom AS ville_nom,
    r.id AS region_id,
    r.nom AS region_nom,
    ta.nom AS article_nom,
    ta.categorie,
    ta.prix_unitaire,
    ta.unite
FROM achat a
JOIN besoin b ON a.besoin_id = b.id
JOIN ville v ON b.ville_id = v.id
JOIN region r ON v.idregion = r.id
JOIN type_articles ta ON b.type_article_id = ta.id;

-- Vue des dons en argent disponibles (dons argent - distributions validées)
CREATE OR REPLACE VIEW vue_argent_disponible AS
SELECT 
    COALESCE(SUM(d.quantite * ta.prix_unitaire), 0) AS total_dons_argent,
    COALESCE((SELECT SUM(montant_total) FROM achat WHERE valide = TRUE), 0) AS total_achats_utilises,
    COALESCE(SUM(d.quantite * ta.prix_unitaire), 0) - COALESCE((SELECT SUM(montant_total) FROM achat WHERE valide = TRUE), 0) AS argent_disponible
FROM dons d
JOIN type_articles ta ON d.type_article_id = ta.id
WHERE ta.categorie = 'argent';

-- Vue récapitulative des besoins (totaux, satisfaits, restants) - HORS ARGENT
CREATE OR REPLACE VIEW vue_recapitulatif_besoins AS
SELECT 
    COALESCE(SUM(CASE WHEN ta.categorie != 'argent' THEN b.quantite * ta.prix_unitaire ELSE 0 END), 0) AS montant_total_besoins,
    COALESCE((
        SELECT SUM(dist.quantite * ta2.prix_unitaire)
        FROM distribution dist
        JOIN besoin b2 ON dist.besoin_id = b2.id
        JOIN type_articles ta2 ON b2.type_article_id = ta2.id
        WHERE dist.est_simulation = FALSE AND ta2.categorie != 'argent'
    ), 0) + COALESCE((
        SELECT SUM(a.quantite * ta3.prix_unitaire)
        FROM achat a
        JOIN besoin b3 ON a.besoin_id = b3.id
        JOIN type_articles ta3 ON b3.type_article_id = ta3.id
        WHERE a.valide = TRUE
    ), 0) AS montant_satisfait,
    COALESCE(SUM(CASE WHEN ta.categorie != 'argent' THEN b.quantite * ta.prix_unitaire ELSE 0 END), 0) - 
    COALESCE((
        SELECT SUM(dist.quantite * ta2.prix_unitaire)
        FROM distribution dist
        JOIN besoin b2 ON dist.besoin_id = b2.id
        JOIN type_articles ta2 ON b2.type_article_id = ta2.id
        WHERE dist.est_simulation = FALSE AND ta2.categorie != 'argent'
    ), 0) - COALESCE((
        SELECT SUM(a.quantite * ta3.prix_unitaire)
        FROM achat a
        JOIN besoin b3 ON a.besoin_id = b3.id
        JOIN type_articles ta3 ON b3.type_article_id = ta3.id
        WHERE a.valide = TRUE
    ), 0) AS montant_restant,
    COUNT(CASE WHEN ta.categorie != 'argent' THEN b.id END) AS nombre_besoins,
    
    (SELECT argent_disponible FROM vue_argent_disponible) AS argent_disponible
FROM besoin b
JOIN type_articles ta ON b.type_article_id = ta.id;
