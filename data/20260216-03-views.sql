-- ============================================================
-- VUES SQL pour l'application BNGRC
-- ============================================================

-- Vue complète des villes avec leur région
CREATE OR REPLACE VIEW v_bngrc_villes_completes AS
SELECT 
    v.id,
    v.nom AS ville_nom,
    v.idregion,
    v.nbsinistres,
    r.nom AS region_nom
FROM bngrc_ville v
JOIN bngrc_region r ON v.idregion = r.id
ORDER BY r.nom, v.nom;

-- Vue complète des besoins avec ville, région et article
CREATE OR REPLACE VIEW v_bngrc_besoins_complets AS
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
FROM bngrc_besoin b
JOIN bngrc_ville v ON b.ville_id = v.id
JOIN bngrc_region r ON v.idregion = r.id
JOIN bngrc_type_articles ta ON b.type_article_id = ta.id;

-- Vue de l'historique des besoins avec détails
CREATE OR REPLACE VIEW v_bngrc_historique_besoins AS
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
FROM bngrc_historique_besoin hb
JOIN bngrc_besoin b ON hb.besoin_id = b.id
JOIN bngrc_ville v ON b.ville_id = v.id
JOIN bngrc_region r ON v.idregion = r.id
JOIN bngrc_type_articles ta ON b.type_article_id = ta.id;

-- Vue des dons avec détails de l'article
CREATE OR REPLACE VIEW v_bngrc_dons_complets AS
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
        FROM bngrc_distribution dist 
        WHERE dist.don_id = d.id AND dist.est_simulation = FALSE
    ), 0) AS quantite_distribuee,
    -- Quantité restante disponible
    d.quantite - COALESCE((
        SELECT SUM(dist.quantite) 
        FROM bngrc_distribution dist 
        WHERE dist.don_id = d.id AND dist.est_simulation = FALSE
    ), 0) AS quantite_disponible
FROM bngrc_dons d
JOIN bngrc_type_articles ta ON d.type_article_id = ta.id;

-- Vue des distributions avec détails
CREATE OR REPLACE VIEW v_bngrc_distributions AS
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
FROM bngrc_distribution dist
JOIN bngrc_dons d ON dist.don_id = d.id
JOIN bngrc_besoin b ON dist.besoin_id = b.id
JOIN bngrc_ville v ON b.ville_id = v.id
JOIN bngrc_region r ON v.idregion = r.id
JOIN bngrc_type_articles ta ON d.type_article_id = ta.id;

CREATE OR REPLACE VIEW v_bngrc_besoins_satisfaction AS
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
FROM bngrc_besoin b
JOIN bngrc_ville v ON b.ville_id = v.id
JOIN bngrc_region r ON v.idregion = r.id
JOIN bngrc_type_articles ta ON b.type_article_id = ta.id
LEFT JOIN bngrc_distribution dist ON dist.besoin_id = b.id
GROUP BY b.id, b.ville_id, b.type_article_id, b.quantite, b.date_demande,
         v.nom, v.nbsinistres, r.id, r.nom, ta.nom, ta.categorie, ta.prix_unitaire, ta.unite;

-- Vue statistiques par ville (distributions validées uniquement)
CREATE OR REPLACE VIEW v_bngrc_stats_villes AS
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
FROM bngrc_ville v
JOIN bngrc_region r ON v.idregion = r.id
LEFT JOIN bngrc_besoin b ON b.ville_id = v.id
LEFT JOIN (
    SELECT besoin_id, SUM(quantite) AS total_distribue
    FROM bngrc_distribution
    WHERE est_simulation = FALSE
    GROUP BY besoin_id
) sub ON sub.besoin_id = b.id
GROUP BY v.id, v.nom, v.nbsinistres, r.id, r.nom;

CREATE OR REPLACE VIEW v_bngrc_besoins_satisfaction_avec_simulation AS
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
    COALESCE(SUM(dist.quantite), 0) AS quantite_recue_avec_simulation,
    GREATEST(0, b.quantite - COALESCE(SUM(CASE WHEN dist.est_simulation = FALSE THEN dist.quantite ELSE 0 END), 0)) AS quantite_restante,
    GREATEST(0, b.quantite - COALESCE(SUM(dist.quantite), 0)) AS quantite_restante_avec_simulation,
    LEAST(100, ROUND(COALESCE(SUM(CASE WHEN dist.est_simulation = FALSE THEN dist.quantite ELSE 0 END), 0) * 100.0 / b.quantite, 2)) AS ratio_satisfaction,
    LEAST(100, ROUND(COALESCE(SUM(dist.quantite), 0) * 100.0 / b.quantite, 2)) AS ratio_satisfaction_avec_simulation
FROM bngrc_besoin b
JOIN bngrc_ville v ON b.ville_id = v.id
JOIN bngrc_region r ON v.idregion = r.id
JOIN bngrc_type_articles ta ON b.type_article_id = ta.id
LEFT JOIN bngrc_distribution dist ON dist.besoin_id = b.id
GROUP BY b.id, b.ville_id, b.type_article_id, b.quantite, b.date_demande,
         v.nom, v.nbsinistres, r.id, r.nom, ta.nom, ta.categorie, ta.prix_unitaire, ta.unite;


CREATE OR REPLACE VIEW v_bngrc_achats_complets AS
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
FROM bngrc_achat a
JOIN bngrc_besoin b ON a.besoin_id = b.id
JOIN bngrc_ville v ON b.ville_id = v.id
JOIN bngrc_region r ON v.idregion = r.id
JOIN bngrc_type_articles ta ON b.type_article_id = ta.id;

-- Vue simplifiée des achats avec infos besoin (pour validerTous)
CREATE OR REPLACE VIEW v_bngrc_achats_avec_besoins AS
SELECT 
    a.*,
    b.type_article_id,
    b.ville_id
FROM bngrc_achat a
JOIN bngrc_besoin b ON a.besoin_id = b.id;

-- Vue des dons en argent disponibles (dons argent - distributions validées)
CREATE OR REPLACE VIEW v_bngrc_argent_disponible AS
SELECT 
    COALESCE(SUM(d.quantite * ta.prix_unitaire), 0) AS total_dons_argent,
    COALESCE((SELECT SUM(montant_total) FROM bngrc_achat WHERE valide = TRUE), 0) AS total_achats_utilises,
    COALESCE(SUM(d.quantite * ta.prix_unitaire), 0) - COALESCE((SELECT SUM(montant_total) FROM bngrc_achat WHERE valide = TRUE), 0) AS argent_disponible
FROM bngrc_dons d
JOIN bngrc_type_articles ta ON d.type_article_id = ta.id
WHERE ta.categorie = 'argent';

-- Vue récapitulative des besoins (totaux, satisfaits, restants) - HORS ARGENT
CREATE OR REPLACE VIEW v_bngrc_recapitulatif_besoins AS
SELECT 
    COALESCE(SUM(CASE WHEN ta.categorie != 'argent' THEN b.quantite * ta.prix_unitaire ELSE 0 END), 0) AS montant_total_besoins,
    COALESCE((
        SELECT SUM(dist.quantite * ta2.prix_unitaire)
        FROM bngrc_distribution dist
        JOIN bngrc_besoin b2 ON dist.besoin_id = b2.id
        JOIN bngrc_type_articles ta2 ON b2.type_article_id = ta2.id
        WHERE dist.est_simulation = FALSE AND ta2.categorie != 'argent'
    ), 0) + COALESCE((
        SELECT SUM(a.quantite * ta3.prix_unitaire)
        FROM bngrc_achat a
        JOIN bngrc_besoin b3 ON a.besoin_id = b3.id
        JOIN bngrc_type_articles ta3 ON b3.type_article_id = ta3.id
        WHERE a.valide = TRUE
    ), 0) AS montant_satisfait,
    COALESCE(SUM(CASE WHEN ta.categorie != 'argent' THEN b.quantite * ta.prix_unitaire ELSE 0 END), 0) - 
    COALESCE((
        SELECT SUM(dist.quantite * ta2.prix_unitaire)
        FROM bngrc_distribution dist
        JOIN bngrc_besoin b2 ON dist.besoin_id = b2.id
        JOIN bngrc_type_articles ta2 ON b2.type_article_id = ta2.id
        WHERE dist.est_simulation = FALSE AND ta2.categorie != 'argent'
    ), 0) - COALESCE((
        SELECT SUM(a.quantite * ta3.prix_unitaire)
        FROM bngrc_achat a
        JOIN bngrc_besoin b3 ON a.besoin_id = b3.id
        JOIN bngrc_type_articles ta3 ON b3.type_article_id = ta3.id
        WHERE a.valide = TRUE
    ), 0) AS montant_restant,
    COUNT(CASE WHEN ta.categorie != 'argent' THEN b.id END) AS nombre_besoins,
    
    (SELECT argent_disponible FROM v_bngrc_argent_disponible) AS argent_disponible
FROM bngrc_besoin b
JOIN bngrc_type_articles ta ON b.type_article_id = ta.id;
