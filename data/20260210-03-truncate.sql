-- Script to truncate all tables in the takalo database with foreign key handling
-- Run this script to empty all data from the tables while keeping the structure
-- This script properly handles foreign key constraints

-- Disable foreign key checks to allow truncation of tables with relationships
SET FOREIGN_KEY_CHECKS = 0;

-- Truncate tables in correct order (child tables first, then parent tables)
TRUNCATE TABLE historique_proprietaire_objet;
TRUNCATE TABLE echange_objets;
TRUNCATE TABLE echanges;
TRUNCATE TABLE photos_objet;
TRUNCATE TABLE objets;
TRUNCATE TABLE statuts_echange;
TRUNCATE TABLE statuts_objet;
TRUNCATE TABLE etats_objet;
TRUNCATE TABLE categories;
TRUNCATE TABLE utilisateurs;
TRUNCATE TABLE roles;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Optional: Reset auto-increment counters to 1
ALTER TABLE utilisateurs AUTO_INCREMENT = 1;
ALTER TABLE roles AUTO_INCREMENT = 1;
ALTER TABLE categories AUTO_INCREMENT = 1;
ALTER TABLE objets AUTO_INCREMENT = 1;
ALTER TABLE echanges AUTO_INCREMENT = 1;
ALTER TABLE photos_objet AUTO_INCREMENT = 1;
ALTER TABLE historique_proprietaire_objet AUTO_INCREMENT = 1;