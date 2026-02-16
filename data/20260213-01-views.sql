-- ========= Vues SQL pour Takalo =========

-- Vue des objets avec toutes les infos (proprietaire, categorie, etat, statut)
CREATE OR REPLACE VIEW v_objets AS
SELECT
    o.id,
    o.titre,
    o.description,
    o.prix_estime,
    o.proprietaire_id,
    u.nom AS proprietaire_nom,
    u.email AS proprietaire_email,
    o.categorie_id,
    c.libelle AS categorie_libelle,
    o.etat_id,
    eo.libelle AS etat_libelle,
    o.statut_id,
    so.code AS statut_code,
    so.libelle AS statut_libelle
FROM objets o
JOIN utilisateurs u ON u.id = o.proprietaire_id
JOIN categories c ON c.id = o.categorie_id
JOIN etats_objet eo ON eo.id = o.etat_id
JOIN statuts_objet so ON so.id = o.statut_id;

-- Vue des echanges avec infos completes
CREATE OR REPLACE VIEW v_echanges AS
SELECT
    e.id,
    e.demandeur_id,
    u1.nom AS demandeur_nom,
    e.receveur_id,
    u2.nom AS receveur_nom,
    e.statut_id,
    se.code AS statut_code,
    se.libelle AS statut_libelle,
    e.date_demande,
    e.date_reponse
FROM echanges e
JOIN utilisateurs u1 ON u1.id = e.demandeur_id
JOIN utilisateurs u2 ON u2.id = e.receveur_id
JOIN statuts_echange se ON se.id = e.statut_id;

-- Vue des objets d'un echange
CREATE OR REPLACE VIEW v_echange_objets AS
SELECT
    eo.id,
    eo.echange_id,
    eo.objet_id,
    eo.direction,
    o.titre AS objet_titre,
    o.prix_estime AS objet_prix,
    o.proprietaire_id,
    u.nom AS proprietaire_nom,
    c.libelle AS categorie_libelle
FROM echange_objets eo
JOIN objets o ON o.id = eo.objet_id
JOIN utilisateurs u ON u.id = o.proprietaire_id
JOIN categories c ON c.id = o.categorie_id;

-- Vue historique proprietaire
CREATE OR REPLACE VIEW v_historique_objet AS
SELECT
    h.id,
    h.objet_id,
    o.titre,
    h.utilisateur_id,
    u.nom AS proprietaire,
    h.echange_id,
    h.date_acquisition
FROM historique_proprietaire_objet h
JOIN utilisateurs u ON u.id = h.utilisateur_id
JOIN objets o ON o.id = h.objet_id
ORDER BY h.date_acquisition;

-- Vue photo principale par objet
CREATE OR REPLACE VIEW v_photo_principale AS
SELECT
    p.id,
    p.objet_id,
    p.chemin,
    p.ordre
FROM photos_objet p
WHERE p.est_principale = 1;
