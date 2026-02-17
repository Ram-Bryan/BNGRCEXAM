<?php

namespace controllers;

use Flight;
use PDO;

class RecapController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    /**
     * Afficher la page de récapitulation
     */
    public function showRecap()
    {
        $recap = $this->getRecapData();

        Flight::render('recap/index', [
            'recap' => $recap
        ]);
    }

    /**
     * API pour actualisation AJAX
     */
    public function getRecapAjax()
    {
        $recap = $this->getRecapData();

        Flight::json([
            'success' => true,
            'data' => $recap
        ]);
    }

    /**
     * Récupérer les données de récapitulation
     */
    private function getRecapData(): array
    {
        // Récupérer le récapitulatif depuis la vue
        $sql = "SELECT * FROM v_bngrc_recapitulatif_besoins";
        $stmt = $this->db->query($sql);
        $recap = $stmt->fetch(PDO::FETCH_ASSOC);

        // Total des dons disponibles (toutes catégories)
        $sqlDonsDisponibles = "SELECT 
            COALESCE(SUM(
                (d.quantite - COALESCE(dist.quantite_distribuee, 0)) * COALESCE(ta.prix_unitaire, 1)
            ), 0) AS total
            FROM bngrc_dons d
            JOIN bngrc_type_articles ta ON d.type_article_id = ta.id
            LEFT JOIN (
                SELECT don_id, SUM(quantite) AS quantite_distribuee
                FROM bngrc_distribution WHERE est_simulation = FALSE
                GROUP BY don_id
            ) dist ON dist.don_id = d.id";
        $stmt = $this->db->query($sqlDonsDisponibles);
        $totalDonsDisponibles = (float)$stmt->fetchColumn();

        // Total des dons en nature/material (non argent)
        $sqlDonsNature = "SELECT COALESCE(SUM(d.quantite * COALESCE(ta.prix_unitaire, 1)), 0) AS total
                          FROM bngrc_dons d
                          JOIN bngrc_type_articles ta ON d.type_article_id = ta.id
                          WHERE ta.categorie != 'argent'";
        $stmt = $this->db->query($sqlDonsNature);
        $totalDonsNature = (float)$stmt->fetchColumn();

        // Total des dons en argent
        $sqlDonsArgent = "SELECT COALESCE(SUM(d.quantite), 0) AS total
                          FROM bngrc_dons d
                          JOIN bngrc_type_articles ta ON d.type_article_id = ta.id
                          WHERE ta.categorie = 'argent'";
        $stmt = $this->db->query($sqlDonsArgent);
        $totalDonsArgent = (float)$stmt->fetchColumn();

        // Argent disponible (non utilisé dans achats)
        $sqlArgentDispo = "SELECT argent_disponible FROM v_bngrc_argent_disponible";
        $stmt = $this->db->query($sqlArgentDispo);
        $argentDisponible = (float)($stmt->fetchColumn() ?: 0);

        // Total des achats validés
        $sqlAchats = "SELECT COALESCE(SUM(montant_total), 0) AS total FROM bngrc_achat WHERE valide = TRUE";
        $stmt = $this->db->query($sqlAchats);
        $totalAchatsValides = (float)$stmt->fetchColumn();

        // Total des achats en attente
        $sqlAchatsAttente = "SELECT COALESCE(SUM(montant_total), 0) AS total FROM bngrc_achat WHERE valide = FALSE";
        $stmt = $this->db->query($sqlAchatsAttente);
        $totalAchatsAttente = (float)$stmt->fetchColumn();

        // Distributions en simulation
        $sqlSimulations = "SELECT COUNT(*) as nb, COALESCE(SUM(quantite), 0) as total 
                   FROM bngrc_distribution WHERE est_simulation = TRUE";
        $stmt = $this->db->query($sqlSimulations);
        $simulations = $stmt->fetch(PDO::FETCH_ASSOC);

        // Statistiques par catégorie avec distribution
        $sqlByCat = "SELECT 
                        ta.categorie,
                        COALESCE(SUM(b.quantite * ta.prix_unitaire), 0) AS montant_total,
                        COALESCE(SUM(COALESCE(dist.quantite_distribuee, 0) * ta.prix_unitaire), 0) AS montant_satisfait
                     FROM bngrc_besoin b
                     JOIN bngrc_type_articles ta ON b.type_article_id = ta.id
                     LEFT JOIN (
                         SELECT besoin_id, SUM(quantite) AS quantite_distribuee
                         FROM bngrc_distribution WHERE est_simulation = FALSE
                         GROUP BY besoin_id
                     ) dist ON dist.besoin_id = b.id
                     GROUP BY ta.categorie";
        $stmt = $this->db->query($sqlByCat);
        $statsByCat = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Nombre de besoins
        $sqlNbBesoins = "SELECT COUNT(*) FROM bngrc_besoin";
        $stmt = $this->db->query($sqlNbBesoins);
        $nbBesoins = (int)$stmt->fetchColumn();

        // Nombre de besoins satisfaits (100%)
        $sqlNbSatisfaits = "SELECT COUNT(*) FROM v_bngrc_besoins_satisfaction WHERE ratio_satisfaction >= 100";
        $stmt = $this->db->query($sqlNbSatisfaits);
        $nbBesoinsSatisfaits = (int)$stmt->fetchColumn();

        $montantTotal = (float)($recap['montant_total_besoins'] ?? 0);
        $montantSatisfait = (float)($recap['montant_satisfait'] ?? 0);
        $montantRestant = (float)($recap['montant_restant'] ?? $montantTotal - $montantSatisfait);

        return [
            'montant_total_besoins' => $montantTotal,
            'montant_satisfait' => $montantSatisfait,
            'montant_restant' => $montantRestant,
            'nombre_besoins' => $nbBesoins,
            'nombre_besoins_satisfaits' => $nbBesoinsSatisfaits,
            'argent_disponible' => $argentDisponible,
            'total_dons_disponibles' => $totalDonsDisponibles,
            'total_dons_nature' => $totalDonsNature,
            'total_dons_argent' => $totalDonsArgent,
            'total_achats_valides' => $totalAchatsValides,
            'total_achats_attente' => $totalAchatsAttente,
            'nb_simulations' => (int)($simulations['nb'] ?? 0),
            'total_simulations' => (float)($simulations['total'] ?? 0),
            'stats_par_categorie' => $statsByCat,
            'ratio_global' => $montantTotal > 0
                ? round(($montantSatisfait / $montantTotal) * 100, 2)
                : 0
        ];
    }
}
