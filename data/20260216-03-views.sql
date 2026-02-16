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

-- Vue des dons avec détails de l'article
CREATE OR REPLACE VIEW vue_dons_complets AS
SELECT 
    d.id,
    d.type_article_id,
    d.quantite,
    d.date_don,
    d.donateur,
    d.statut,
    ta.nom AS article_nom,
    ta.categorie,
    ta.prix_unitaire,
    ta.unite,
    (d.quantite * ta.prix_unitaire) AS montant_total,
    -- Quantité déjà distribuée (validée)
    COALESCE((
        SELECT SUM(dist.quantite) 
        FROM distribution dist 
        WHERE dist.don_id = d.id AND dist.est_simulation = FALSE
    ), 0) AS quantite_distribuee,
    -- Quantité restante disponible
    d.quantite - COALESCE((
        SELECT SUM(dist.quantite) 
        FROM distribution dist 
        WHERE dist.don_id = d.id AND dist.est_simulation = FALSE
    ), 0) AS quantite_disponible
FROM dons d
JOIN type_articles ta ON d.type_article_id = ta.id;

-- Vue des distributions avec détails
CREATE OR REPLACE VIEW vue_distributions AS
SELECT 
    dist.id,
    dist.don_id,
    dist.besoin_id,
    dist.quantite,
    dist.date_distribution,
    dist.est_simulation,
    d.type_article_id,
    d.donateur,
    b.ville_id,
    v.nom AS ville_nom,
    r.nom AS region_nom,
    ta.nom AS article_nom,
    ta.categorie,
    ta.prix_unitaire,
    ta.unite
FROM distribution dist
JOIN dons d ON dist.don_id = d.id
JOIN besoin b ON dist.besoin_id = b.id
JOIN ville v ON b.ville_id = v.id
JOIN region r ON v.idregion = r.id
JOIN type_articles ta ON d.type_article_id = ta.id;

-- Vue des besoins avec ratio de satisfaction (distributions validées uniquement)
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
    COALESCE(SUM(CASE WHEN dist.est_simulation = FALSE THEN dist.quantite ELSE 0 END), 0) AS quantite_recue,
    GREATEST(0, b.quantite - COALESCE(SUM(CASE WHEN dist.est_simulation = FALSE THEN dist.quantite ELSE 0 END), 0)) AS quantite_restante,
    LEAST(100, ROUND(COALESCE(SUM(CASE WHEN dist.est_simulation = FALSE THEN dist.quantite ELSE 0 END), 0) * 100.0 / b.quantite, 2)) AS ratio_satisfaction
FROM besoin b
JOIN ville v ON b.ville_id = v.id
JOIN region r ON v.idregion = r.id
JOIN type_articles ta ON b.type_article_id = ta.id
LEFT JOIN distribution dist ON dist.besoin_id = b.id
GROUP BY b.id, b.ville_id, b.type_article_id, b.quantite, b.date_demande,
         v.nom, v.nbsinistres, r.id, r.nom, ta.nom, ta.categorie, ta.prix_unitaire, ta.unite;

-- Vue statistiques par ville (distributions validées uniquement)
CREATE OR REPLACE VIEW vue_stats_villes AS
SELECT 
    v.id AS ville_id,
    v.nom AS ville_nom,
    v.nbsinistres,
    r.id AS region_id,
    r.nom AS region_nom,
    COUNT(DISTINCT b.id) AS nombre_besoins,
    COALESCE(SUM(b.quantite), 0) AS total_quantite_demandee,
    COALESCE(SUM(sub.total_distribue), 0) AS total_quantite_recue,
    CASE 
        WHEN COALESCE(SUM(b.quantite), 0) = 0 THEN 0
        ELSE ROUND(COALESCE(SUM(sub.total_distribue), 0) * 100.0 / SUM(b.quantite), 2)
    END AS ratio_satisfaction_global
FROM ville v
JOIN region r ON v.idregion = r.id
LEFT JOIN besoin b ON b.ville_id = v.id
LEFT JOIN (
    SELECT besoin_id, SUM(quantite) AS total_distribue
    FROM distribution
    WHERE est_simulation = FALSE
    GROUP BY besoin_id
) sub ON sub.besoin_id = b.id
GROUP BY v.id, v.nom, v.nbsinistres, r.id, r.nom;
