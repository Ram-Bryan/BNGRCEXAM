-- Drop all BNGRC views and tables (safe order)
-- Désactive temporairement les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Drop views
DROP VIEW IF EXISTS v_bngrc_villes_completes;
DROP VIEW IF EXISTS v_bngrc_besoins_complets;
DROP VIEW IF EXISTS v_bngrc_historique_besoins;
DROP VIEW IF EXISTS v_bngrc_dons_complets;
DROP VIEW IF EXISTS v_bngrc_distributions;
DROP VIEW IF EXISTS v_bngrc_besoins_satisfaction;
DROP VIEW IF EXISTS v_bngrc_stats_villes;
DROP VIEW IF EXISTS v_bngrc_besoins_satisfaction_avec_simulation;
DROP VIEW IF EXISTS v_bngrc_achats_complets;
DROP VIEW IF EXISTS v_bngrc_argent_disponible;
DROP VIEW IF EXISTS v_bngrc_recapitulatif_besoins;

-- Drop tables in dependency-safe order
DROP TABLE IF EXISTS bngrc_distribution;
DROP TABLE IF EXISTS bngrc_historique_besoin;
DROP TABLE IF EXISTS bngrc_achat;
DROP TABLE IF EXISTS bngrc_dons;
DROP TABLE IF EXISTS bngrc_besoin;
DROP TABLE IF EXISTS bngrc_type_articles;
DROP TABLE IF EXISTS bngrc_ville;
DROP TABLE IF EXISTS bngrc_region;
DROP TABLE IF EXISTS bngrc_configuration;

-- Réactive les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;
