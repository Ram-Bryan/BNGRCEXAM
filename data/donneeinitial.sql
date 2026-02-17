-- ============================================================
-- DONNÉES INITIALES - Tables de Sauvegarde
-- ============================================================
-- Ces tables conservent les données de base pour permettre
-- la réinitialisation de l'application à tout moment.
-- ============================================================

-- ============================================================
-- CRÉATION DES TABLES INITIALES
-- ============================================================

-- Table de sauvegarde des besoins initiaux
CREATE TABLE IF NOT EXISTS bngrc_besoin_initial (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ville_id INT NOT NULL,
    type_article_id INT NOT NULL,
    quantite INT NOT NULL,
    date_demande DATE NOT NULL,
    FOREIGN KEY (ville_id) REFERENCES bngrc_ville(id) ON DELETE CASCADE,
    FOREIGN KEY (type_article_id) REFERENCES bngrc_type_articles(id) ON DELETE CASCADE
);

-- Table de sauvegarde des dons initiaux
CREATE TABLE IF NOT EXISTS bngrc_dons_initial (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_article_id INT NOT NULL,
    quantite INT NOT NULL,
    date_don DATE NOT NULL,
    donateur VARCHAR(200) DEFAULT 'Anonyme',
    statut ENUM('disponible', 'distribue') DEFAULT 'disponible',
    FOREIGN KEY (type_article_id) REFERENCES bngrc_type_articles(id) ON DELETE CASCADE
);

-- ============================================================
-- INSERTION DES DONNÉES INITIALES - BESOINS
-- ============================================================

-- Vider les tables avant insertion
TRUNCATE TABLE bngrc_besoin_initial;
TRUNCATE TABLE bngrc_dons_initial;

-- Besoins initiaux (10 besoins répartis dans différentes villes)
INSERT INTO bngrc_besoin_initial (ville_id, type_article_id, quantite, date_demande) VALUES
-- Antananarivo (ville_id=1) - Riz et Eau
(1, 1, 50, '2026-02-10'),  -- 50 sacs de Riz
(1, 3, 200, '2026-02-10'), -- 200 bouteilles d'Eau

-- Toamasina (ville_id=2) - Médicaments et Couvertures
(2, 4, 100, '2026-02-11'), -- 100 boîtes de Médicaments
(2, 5, 80, '2026-02-11'),  -- 80 couvertures

-- Antsirabe (ville_id=3) - Riz, Tentes et Argent
(3, 1, 30, '2026-02-12'),  -- 30 sacs de Riz
(3, 6, 15, '2026-02-12'),  -- 15 tentes
(3, 2, 5000000, '2026-02-12'), -- 5 000 000 Ar

-- Fianarantsoa (ville_id=4) - Eau et Médicaments
(4, 3, 150, '2026-02-13'), -- 150 bouteilles d'Eau
(4, 4, 50, '2026-02-13'),  -- 50 boîtes de Médicaments

-- Mahajanga (ville_id=5) - Riz et Couvertures
(5, 1, 40, '2026-02-14'),  -- 40 sacs de Riz
(5, 5, 60, '2026-02-14');  -- 60 couvertures

-- ============================================================
-- INSERTION DES DONNÉES INITIALES - DONS
-- ============================================================

-- Dons initiaux (12 dons de différents types)
INSERT INTO bngrc_dons_initial (type_article_id, quantite, date_don, donateur, statut) VALUES
-- Dons de Riz
(1, 80, '2026-02-09', 'ONG Secours Alimentaire', 'disponible'),
(1, 50, '2026-02-10', 'Croix-Rouge Madagascar', 'disponible'),

-- Don d'Argent
(2, 10000000, '2026-02-09', 'Banque Centrale', 'disponible'), -- 10 000 000 Ar

-- Dons d'Eau
(3, 300, '2026-02-09', 'JIRAMA', 'disponible'),
(3, 150, '2026-02-11', 'Commune Urbaine Antananarivo', 'disponible'),

-- Dons de Médicaments
(4, 120, '2026-02-10', 'Pharmacie Humanitaire', 'disponible'),
(4, 80, '2026-02-12', 'Médecins Sans Frontières', 'disponible'),

-- Dons de Couvertures
(5, 100, '2026-02-10', 'Association Mianatra', 'disponible'),
(5, 70, '2026-02-13', 'Secours Catholique', 'disponible'),

-- Dons de Tentes
(6, 20, '2026-02-11', 'UNICEF Madagascar', 'disponible'),
(6, 10, '2026-02-13', 'Solidarité Internationale', 'disponible'),

-- Don supplémentaire d'argent
(2, 3000000, '2026-02-14', 'Diaspora Malagasy', 'disponible'); -- 3 000 000 Ar

-- ============================================================
-- VÉRIFICATION DES DONNÉES
-- ============================================================

-- Afficher un résumé des données initiales
SELECT 
    'BESOINS INITIAUX' AS Type,
    COUNT(*) AS Total,
    SUM(quantite) AS Quantite_Totale
FROM bngrc_besoin_initial
UNION ALL
SELECT 
    'DONS INITIAUX' AS Type,
    COUNT(*) AS Total,
    SUM(quantite) AS Quantite_Totale
FROM bngrc_dons_initial;

-- ============================================================
-- NOTES D'UTILISATION
-- ============================================================
-- 1. Exécuter ce script une seule fois après la création des tables principales
-- 2. Ces tables ne doivent PAS être modifiées en production
-- 3. Pour réinitialiser le système, utiliser le bouton "Réinitialiser" 
--    ou appeler la route POST /reset
-- 4. La réinitialisation copie ces données dans les tables principales
--    et supprime toutes les transactions (distributions, achats, historique)
-- ============================================================
