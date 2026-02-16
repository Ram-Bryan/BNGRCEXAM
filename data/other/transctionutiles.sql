-- ========= Transaction pour ajouter un objet =========
/*
Workflow:
Étape 1 – INSERT dans la table objets
Étape 2 – INSERT dans la table photos_objet
Étape 3 – INSERT dans la table historique_proprietaire_objet
*/

START TRANSACTION;

-- 1. Insert object
INSERT INTO objets (
    proprietaire_id,
    categorie_id,
    etat_id,
    statut_id,
    titre,
    description,
    prix_estime
)
VALUES (
    :user_id,
    :categorie_id,
    :etat_id,
    :statut_disponible,
    :titre,
    :description,
    :prix
);

SET @objet_id := LAST_INSERT_ID();

-- 2. Insert photos (loop in PHP)
-- First photo is main (or selected one)
INSERT INTO photos_objet (objet_id, chemin, ordre, est_principale)
VALUES (@objet_id, :chemin, :ordre, :est_principale);

-- (repeat for each image)

-- 3. Ownership history (initial acquisition)
INSERT INTO historique_proprietaire_objet (
    objet_id,
    utilisateur_id,
    echange_id
)
VALUES (
    @objet_id,
    :user_id,
    NULL
);

COMMIT;

-- ========= Transaction pour proposer un échange (EN_ATTENTE) =========
/*
Workflow :
Étape 1 – INSERT dans la table echanges
Étape 2 – INSERT dans la table echange_objets
Étape 3 – UPDATE dans la table objets
*/


START TRANSACTION;

-- 1. Create exchange
INSERT INTO echanges (
    demandeur_id,
    receveur_id,
    statut_id
)
VALUES (
    :demandeur_id,
    :receveur_id,
    1 -- EN_ATTENTE
);

SET @echange_id := LAST_INSERT_ID();

-- 2. Insert exchange objects
INSERT INTO echange_objets (echange_id, objet_id, direction)
VALUES
(@echange_id, :objet_offert, 'OFFERT'),
(@echange_id, :objet_demande, 'DEMANDE');

-- 3. Reserve objects
UPDATE objets
SET statut_id = 2 -- RESERVE
WHERE id IN (:objet_offert, :objet_demande);

COMMIT;

-- ========= Transaction pour refuser un échange =========
/*
Workflow :
Étape 1 – Vérifier que l’échange est en_attente
Étape 2 – UPDATE dans la table echanges
Étape 3 – UPDATE dans la table objets
*/

START TRANSACTION;

-- 1. Lock exchange
SELECT statut_id
FROM echanges
WHERE id = :echange_id
FOR UPDATE;

-- 2. Refuse exchange
UPDATE echanges
SET statut_id = 3, date_reponse = NOW()
WHERE id = :echange_id
AND statut_id = 1;

-- 3. Restore object availability
UPDATE objets
SET statut_id = 1 -- DISPONIBLE
WHERE id IN (
    SELECT objet_id
    FROM echange_objets
    WHERE echange_id = :echange_id
);

COMMIT;


-- ========= Transaction pour accepter un échange =========
/*
Workflow :
Étape 1 – Vérifier et verrouiller l’échange
Étape 2 – UPDATE dans la table echanges
Étape 3 – UPDATE dans la table objets
Étape 4 – INSERT dans la table historique_proprietaire_objet
*/

START TRANSACTION;

-- 1. Lock exchange
SELECT statut_id
FROM echanges
WHERE id = :echange_id
FOR UPDATE;

-- 2. Accept exchange
UPDATE echanges
SET statut_id = 2, date_reponse = NOW()
WHERE id = :echange_id
AND statut_id = 1;

-- 3. Transfer ownership (OFFERT → receveur)
UPDATE objets o
JOIN echange_objets eo ON eo.objet_id = o.id
SET o.proprietaire_id = :receveur_id,
    o.statut_id = 3 -- ECHANGE
WHERE eo.echange_id = :echange_id
AND eo.direction = 'OFFERT';

-- 4. Transfer ownership (DEMANDE → demandeur)
UPDATE objets o
JOIN echange_objets eo ON eo.objet_id = o.id
SET o.proprietaire_id = :demandeur_id,
    o.statut_id = 3 -- ECHANGE
WHERE eo.echange_id = :echange_id
AND eo.direction = 'DEMANDE';

-- 5. Insert ownership history
INSERT INTO historique_proprietaire_objet (
    objet_id,
    utilisateur_id,
    echange_id
)
SELECT
    eo.objet_id,
    CASE
        WHEN eo.direction = 'OFFERT' THEN :receveur_id
        ELSE :demandeur_id
    END,
    :echange_id
FROM echange_objets eo
WHERE eo.echange_id = :echange_id;

COMMIT;

