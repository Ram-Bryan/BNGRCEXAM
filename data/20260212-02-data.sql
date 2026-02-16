-- Test data for takalo database

-- Roles
INSERT INTO roles (code, libelle) VALUES ('user', 'Utilisateur');
INSERT INTO roles (code, libelle) VALUES ('admin', 'Administrateur');

-- Utilisateurs
/*
jean123
marie123
pierre123
admin
*/
INSERT INTO utilisateurs (nom, email, password_hash, tel, role_id) VALUES ('Jean', 'jean@gmail.com', '$2y$10$cDoDpXattpZT2kp2ZqIJGuzrInR8eXPvzFF2BBRStTyzHgW.h2Ngi', '1234567890', 1);
INSERT INTO utilisateurs (nom, email, password_hash, tel, role_id) VALUES ('Marie', 'marie@gmail.com', '$2y$10$y2ffuDIWbdKtE1kyU0662u6MC/orZyKD8T1N5W2AqPYCV.JSCGvA2', '0987654321', 1);
INSERT INTO utilisateurs (nom, email, password_hash, tel, role_id) VALUES ('Pierre', 'pierre@gmail.com', '$2y$10$2cdDTjrYW7I8s/AzF5y3ruYo7v/TL.lwZdrChE30.e3aIV5Lx8Fbu', '1122334455', 1);
INSERT INTO utilisateurs (nom, email, password_hash, tel, role_id) VALUES ('Admin', 'admin@gmail.com', '$2y$10$hpgcoFH.dxkymO9Qdwfp8.TcwuoRnNXZmzbon1BWjvwdksrV.7GHq', '9988776655', 2);

-- Categorie
INSERT INTO categories (libelle, symbole, description) VALUES ('Electronique', 'e', 'Téléphones, ordinateurs, appareils électroniques et gadgets technologiques');
INSERT INTO categories (libelle, symbole, description) VALUES ('Vetements', 'v', 'Vêtements, chaussures et accessoires de mode pour hommes et femmes');
INSERT INTO categories (libelle, symbole, description) VALUES ('Livres', 'l', 'Romans, manuels scolaires, bandes dessinées et ouvrages littéraires');
INSERT INTO categories (libelle, symbole, description) VALUES ('Meubles', 'm', 'Mobilier de maison, décoration et divers meubles');
INSERT INTO categories (libelle, symbole, description) VALUES ('Sports', 's', 'Équipements sportifs, vélos, raquettes et articles de loisirs actifs');

-- Etats objet
INSERT INTO etats_objet (code, libelle) VALUES ('neuf', 'Neuf');
INSERT INTO etats_objet (code, libelle) VALUES ('bon_etat', 'Bon état');
INSERT INTO etats_objet (code, libelle) VALUES ('etat_moyen', 'État moyen');
INSERT INTO etats_objet (code, libelle) VALUES ('mauvais_etat', 'Mauvais état');

-- Statuts objet
INSERT INTO statuts_objet (code, libelle) VALUES ('disponible', 'Disponible');
INSERT INTO statuts_objet (code, libelle) VALUES ('reserve', 'Réservé');
INSERT INTO statuts_objet (code, libelle) VALUES ('vendu', 'Vendu');

-- Statuts echange
INSERT INTO statuts_echange (code, libelle) VALUES ('en_attente', 'En attente');
INSERT INTO statuts_echange (code, libelle) VALUES ('accepte', 'Accepté');
INSERT INTO statuts_echange (code, libelle) VALUES ('refuse', 'Refusé');

-- Objets (20 objets)
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (1, 1, 1, 1, 'Ordinateur portable', 'Ordinateur portable neuf', 800.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (1, 2, 2, 1, 'T-shirt noire', 'T-shirt en coton', 15.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (2, 3, 1, 1, 'Livre de programmation', 'Guide complet PHP', 25.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (2, 4, 3, 1, 'Chaise de bureau', 'Chaise ergonomique', 120.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (3, 5, 2, 1, 'Ballon de football', 'Ballon professionnel', 30.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (1, 1, 1, 1, 'Smartphone', 'Dernier modèle', 600.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (3, 2, 2, 1, 'Jean bleu', 'Jean slim fit', 40.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (3, 3, 1, 1, 'Roman policier', 'Thriller captivant', 12.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (1, 4, 3, 1, 'Table basse', 'Table en bois', 150.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (2, 5, 2, 1, 'Raquette de tennis', 'Raquette Wilson', 80.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (3, 1, 1, 1, 'Casque audio', 'Casque sans fil', 100.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (1, 2, 2, 1, 'Pull-over', 'Pull en laine', 35.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (2, 3, 1, 1, 'Manuel de maths', 'Pour étudiants', 20.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (3, 4, 3, 1, 'Armoire', 'Armoire ancienne', 200.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (1, 5, 2, 1, 'Vélo', 'Vélo de course', 250.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (2, 1, 1, 1, 'Tablette', 'Tablette Android', 300.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (3, 2, 2, 1, 'Robe', 'Robe de printemps', 50.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (1, 3, 1, 1, 'BD', 'Bande dessinée', 8.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (2, 4, 3, 1, 'Lit', 'Lit double', 400.00);
INSERT INTO objets (proprietaire_id, categorie_id, etat_id, statut_id, titre, description, prix_estime) VALUES (3, 5, 2, 1, 'Skis', 'Paire de skis', 150.00);

-- Photos objets (liens vers 
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (1, 'ordinateur.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (2, 'tshirt.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (3, 'livre.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (4, 'chaise.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (5, 'ballon.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (6, 'smartphone.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (7, 'jean.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (8, 'roman.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (9, 'table.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (10, 'raquette.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (11, 'casque.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (12, 'pullover.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (13, 'manuel.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (14, 'armoire.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (15, 'velo.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (16, 'tablette.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (17, 'robe.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (18, 'bd.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (19, 'lit.jpg', 1, true);
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale) VALUES (20, 'skis.jpg', 1, true);

-- Echanges
INSERT INTO echanges (demandeur_id, receveur_id, statut_id) VALUES (1, 2, 1); -- Jean demande a Marie
INSERT INTO echanges (demandeur_id, receveur_id, statut_id) VALUES (3, 2, 2); -- Pierre demande a Marie, accepte

-- Echange objets
INSERT INTO echange_objets (echange_id, objet_id, direction) VALUES (1, 1, 'OFFERT'); -- Jean offre ordinateur
INSERT INTO echange_objets (echange_id, objet_id, direction) VALUES (1, 3, 'DEMANDE'); -- Jean demande livre de Marie
INSERT INTO echange_objets (echange_id, objet_id, direction) VALUES (2, 5, 'OFFERT'); -- Pierre offre ballon
INSERT INTO echange_objets (echange_id, objet_id, direction) VALUES (2, 7, 'DEMANDE'); -- Pierre demande jean bleu de Marie

-- Historique proprietaire objet (acquisition initiale pour chaque objet)
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (1, 1, '2024-01-01 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (2, 1, '2024-01-02 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (3, 2, '2024-01-03 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (4, 2, '2024-01-04 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (5, 3, '2024-01-05 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (6, 1, '2024-01-06 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (7, 2, '2024-01-07 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (8, 3, '2024-01-08 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (9, 1, '2024-01-09 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (10, 2, '2024-01-10 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (11, 3, '2024-01-11 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (12, 1, '2024-01-12 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (13, 2, '2024-01-13 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (14, 3, '2024-01-14 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (15, 1, '2024-01-15 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (16, 2, '2024-01-16 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (17, 3, '2024-01-17 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (18, 1, '2024-01-18 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (19, 2, '2024-01-19 10:00:00');
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, date_acquisition) VALUES (20, 3, '2024-01-20 10:00:00');

-- Transferts pour echange accepte (id 2)
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, echange_id, date_acquisition) VALUES (5, 2, 2, '2024-02-01 10:00:00'); -- Ballon de Pierre a Marie
INSERT INTO historique_proprietaire_objet (objet_id, utilisateur_id, echange_id, date_acquisition) VALUES (7, 3, 2, '2024-02-01 10:00:00'); -- Jean bleu de Marie a Pierre
