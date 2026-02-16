-- Drop all tables and views for Takalo database

-- Drop views first
DROP VIEW IF EXISTS v_historique_objet;
DROP VIEW IF EXISTS v_echanges_utilisateur;

-- Drop tables in reverse order of dependencies
DROP TABLE IF EXISTS historique_proprietaire_objet;
DROP TABLE IF EXISTS echange_objets;
DROP TABLE IF EXISTS echanges;
DROP TABLE IF EXISTS statuts_echange;
DROP TABLE IF EXISTS photos_objet;
DROP TABLE IF EXISTS objets;
DROP TABLE IF EXISTS statuts_objet;
DROP TABLE IF EXISTS etats_objet;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS utilisateurs;
DROP TABLE IF EXISTS roles;
