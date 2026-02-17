<?php

namespace controllers;

use models\Besoin;
use models\Don;
use models\Distribution;
use models\Achat;
use Flight;
use PDO;

class SimulationController
{
    /**
     * Afficher la page de simulation
     */
    public static function showSimulation()
    {
        $db = Flight::db();

        // Vérifier s'il y a des simulations en cours
        $simulationsData = Distribution::findSimulations($db);
        $hasSimulation = !empty($simulationsData);

        // Si simulation en cours, utiliser la vue avec simulation pour voir la projection
        // Sinon utiliser la vue standard
        if ($hasSimulation) {
            $besoinsData = Besoin::findBesoinsAvecSimulation($db);
        } else {
            $besoinsData = Besoin::findBesoinsNonSatisfaits($db);
        }

        // Calculer la progression pour chaque besoin
        foreach ($besoinsData as &$besoin) {
            if ($hasSimulation && isset($besoin['ratio_satisfaction_avec_simulation'])) {
                $besoin['ratio_display'] = $besoin['ratio_satisfaction_avec_simulation'];
                $besoin['is_projected'] = true;
            } else {
                $besoin['ratio_display'] = $besoin['ratio_satisfaction'];
                $besoin['is_projected'] = false;
            }
            $besoin['progress_class'] = $besoin['ratio_display'] >= 100 ? 'progress-complete' : 
                                       ($besoin['ratio_display'] >= 50 ? 'progress-partial' : 'progress-low');
            $besoin['progress_width'] = min($besoin['ratio_display'], 100);
        }
        unset($besoin);

        // Dons disponibles
        $donsData = Don::findAllDisponibles($db);

        // Distributions validées
        $distribueesData = Distribution::findValidees($db);

        // Résumé de la simulation actuelle
        $resumeSimulation = Distribution::getResumeSimulation($db);

        Flight::render('simulation/index', [
            'besoins' => $besoinsData,
            'dons' => $donsData,
            'simulations' => $simulationsData,
            'distribuees' => $distribueesData,
            'resume' => $resumeSimulation,
            'hasSimulation' => $hasSimulation
        ]);
    }

    /**
     * Lancer la simulation (créer les distributions en mode simulation)
     */
    public static function simuler()
    {
        try {
            $db = Flight::db();
            $resultats = Distribution::simulerDistribution($db);
            $resume = Distribution::getResumeSimulation($db);

            // Récupérer les détails de la simulation
            $simulations = Distribution::findSimulations($db);

            Flight::json([
                'success' => true,
                'message' => count($resultats) . ' distribution(s) simulée(s)',
                'nb_distributions' => count($resultats),
                'resume' => $resume,
                'simulations' => $simulations
            ]);
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lancer la simulation avec priorité aux petites quantités
     */
    public static function simulerPlusPetit()
    {
        try {
            $db = Flight::db();
            $resultats = Distribution::simulerDistributionPlusPetit($db);
            $resume = Distribution::getResumeSimulation($db);

            // Récupérer les détails de la simulation
            $simulations = Distribution::findSimulations($db);

            Flight::json([
                'success' => true,
                'message' => count($resultats) . ' distribution(s) simulée(s) - Priorité aux petites quantités',
                'nb_distributions' => count($resultats),
                'resume' => $resume,
                'simulations' => $simulations
            ]);
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Valider la simulation (rendre les distributions définitives)
     */
    public static function valider()
    {
        try {
            $db = Flight::db();
            // Valider les distributions simulées
            Distribution::validerSimulations($db);

            // Valider aussi les achats en attente
            Achat::validerTous($db);

            Flight::json([
                'success' => true,
                'message' => 'Distribution validée avec succès !'
            ]);
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Annuler la simulation
     */
    public static function annuler()
    {
        try {
            $db = Flight::db();
            Distribution::supprimerSimulations($db);

            Flight::json([
                'success' => true,
                'message' => 'Simulation annulée'
            ]);
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer l'état actuel de la simulation (AJAX)
     */
    public static function getEtat()
    {
        $db = Flight::db();
        $simulations = Distribution::findSimulations($db);
        $resume = Distribution::getResumeSimulation($db);
        $besoins = Besoin::findBesoinsNonSatisfaits($db);

        Flight::json([
            'success' => true,
            'simulations' => $simulations,
            'resume' => $resume,
            'besoins' => $besoins
        ]);
    }
}
