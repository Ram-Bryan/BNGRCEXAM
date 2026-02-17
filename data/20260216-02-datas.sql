-- Données de démonstration pour BNGRC
-- Contient des INSERTs pour : region, ville, type_articles
-- Ne contient PAS de données pour : besoin, historique_besoin, dons

USE bngrc;

-- Désactiver temporairement les checks FK pour faciliter le rechargement
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE bngrc_type_articles;
TRUNCATE TABLE bngrc_ville;
TRUNCATE TABLE bngrc_region;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------
-- Regions
-- --------------------------------------------------
INSERT INTO bngrc_region (nom) VALUES
('Analamanga'),
('Atsimo-Andrefana'),
('Sava'),
('Diana'),
('Vatovavy-Fitovinany');

-- --------------------------------------------------
-- Villes (rattachement aux régions ci-dessus)
-- --------------------------------------------------
INSERT INTO bngrc_ville (nom, idregion, nbsinistres) VALUES
('Antananarivo', 1, 1200),
('Ambohidratrimo', 1, 250),
('Toliara', 2, 480),
('Betroka', 2, 140),
('Sambava', 3, 90),
('Antalaha', 3, 70),
('Diego Suarez', 4, 150),
('Manakara', 5, 200);

-- --------------------------------------------------
-- Types d'articles
-- categorie = 'nature' | 'argent' | 'material'
-- --------------------------------------------------
INSERT INTO bngrc_type_articles (nom, categorie, prix_unitaire, unite) VALUES
('Eau potable (litre)', 'nature', 0.50, 'litre'),
('Riz (sac 50kg)', 'nature', 35000.00, 'sac'),
('Tentes', 'material', 120000.00, 'unité'),
('Couvertures', 'material', 8000.00, 'pièce'),
('Kits hygiène', 'material', 15000.00, 'kit'),
('Aide financière (espèces)', 'argent', 1.00, 'Ar'),
('Matériel médical', 'material', 25000.00, 'lot');

INSERT INTO bngrc_configuration (nom, valeur) VALUES ('FRAIS_ACHAT_PERCENT', '10')
ON DUPLICATE KEY UPDATE valeur = VALUES(valeur);

-- Fin du fichier
