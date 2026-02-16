-- ============================================================
-- VUES SQL pour l'application BNGRC
-- ============================================================

-- Vue complète des villes avec leur région
CREATE OR REPLACE VIEW vue_villes_completes AS
SELECT 
    v.id,
    v.nom AS ville_nom,
    v.idregion,
    v.nbsinistres,
    r.nom AS region_nom
FROM ville v
JOIN region r ON v.idregion = r.id
ORDER BY r.nom, v.nom;

-- Vue complète des besoins avec ville, région et article
CREATE OR REPLACE VIEW vue_besoins_complets AS
SELECT 
    b.id,
    b.ville_id,
    b.type_article_id,
    b.quantite,
    b.date_demande,
    v.nom AS ville_nom,
    v.nbsinistres,
    r.id AS region_id,
    r.nom AS region_nom,
    ta.nom AS article_nom,
    ta.categorie,
    ta.prix_unitaire,
    ta.unite
FROM besoin b
JOIN ville v ON b.ville_id = v.id
JOIN region r ON v.idregion = r.id
JOIN type_articles ta ON b.type_article_id = ta.id;

-- Vue de l'historique des besoins avec détails
CREATE OR REPLACE VIEW vue_historique_besoins AS
SELECT 
    hb.id,
    hb.besoin_id,
    hb.quantite,
    hb.date_enregistrement,
    b.ville_id,
    b.type_article_id,
    b.date_demande,
    v.nom AS ville_nom,
    r.nom AS region_nom,
    ta.nom AS article_nom,
    ta.unite
FROM historique_besoin hb
JOIN besoin b ON hb.besoin_id = b.id
JOIN ville v ON b.ville_id = v.id
JOIN region r ON v.idregion = r.id
JOIN type_articles ta ON b.type_article_id = ta.id;

-- Vue des dons avec détails du besoin associé
CREATE OR REPLACE VIEW vue_dons_complets AS
SELECT 
    d.id,
    d.idbesoins AS besoin_id,
    d.quantite AS quantite_don,
    d.date_livraison,
    b.quantite AS quantite_besoin,
    b.date_demande,
    b.ville_id,
    b.type_article_id,
    v.nom AS ville_nom,
    v.nbsinistres,
    r.id AS region_id,
    r.nom AS region_nom,
    ta.nom AS article_nom,
    ta.categorie,
    ta.prix_unitaire,
    ta.unite
FROM dons d
JOIN besoin b ON d.idbesoins = b.id
JOIN ville v ON b.ville_id = v.id
JOIN region r ON v.idregion = r.id
JOIN type_articles ta ON b.type_article_id = ta.id;

-- Vue des besoins avec ratio de satisfaction (dons reçus vs quantité demandée)
CREATE OR REPLACE VIEW vue_besoins_satisfaction AS
SELECT 
    b.id,
    b.ville_id,
    b.type_article_id,
    b.quantite AS quantite_demandee,
    b.date_demande,
    v.nom AS ville_nom,
    v.nbsinistres,
    r.id AS region_id,
    r.nom AS region_nom,
    ta.nom AS article_nom,
    ta.categorie,
    ta.prix_unitaire,
    ta.unite,
    COALESCE(SUM(d.quantite), 0) AS quantite_recue,
    (b.quantite - COALESCE(SUM(d.quantite), 0)) AS quantite_restante,
    ROUND(COALESCE(SUM(d.quantite), 0) * 100.0 / b.quantite, 2) AS ratio_satisfaction
FROM besoin b
JOIN ville v ON b.ville_id = v.id
JOIN region r ON v.idregion = r.id
JOIN type_articles ta ON b.type_article_id = ta.id
LEFT JOIN dons d ON d.idbesoins = b.id
GROUP BY b.id, b.ville_id, b.type_article_id, b.quantite, b.date_demande,
         v.nom, v.nbsinistres, r.id, r.nom, ta.nom, ta.categorie, ta.prix_unitaire, ta.unite;

-- Vue statistiques par ville
CREATE OR REPLACE VIEW vue_stats_villes AS
SELECT 
    v.id AS ville_id,
    v.nom AS ville_nom,
    v.nbsinistres,
    r.id AS region_id,
    r.nom AS region_nom,
    COUNT(DISTINCT b.id) AS nombre_besoins,
    COALESCE(SUM(b.quantite), 0) AS total_quantite_demandee,
    COALESCE(SUM(sub.total_dons), 0) AS total_quantite_recue,
    CASE 
        WHEN COALESCE(SUM(b.quantite), 0) = 0 THEN 0
        ELSE ROUND(COALESCE(SUM(sub.total_dons), 0) * 100.0 / SUM(b.quantite), 2)
    END AS ratio_satisfaction_global
FROM ville v
JOIN region r ON v.idregion = r.id
LEFT JOIN besoin b ON b.ville_id = v.id
LEFT JOIN (
    SELECT idbesoins, SUM(quantite) AS total_dons
    FROM dons
    GROUP BY idbesoins
) sub ON sub.idbesoins = b.id
GROUP BY v.id, v.nom, v.nbsinistres, r.id, r.nom;
