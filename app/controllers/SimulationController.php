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
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    /**
     * Afficher la page de simulation
     */
    public function showSimulation()
    {
        // Besoins non satisfaits
        $besoinsData = Besoin::findBesoinsNonSatisfaits($this->db);

        // Dons disponibles
        $donsData = Don::findAllDisponibles($this->db);

        // Distributions en simulation
        $simulationsData = Distribution::findSimulations($this->db);

        // Distributions validées
        $distribueesData = Distribution::findValidees($this->db);

        // Résumé de la simulation actuelle
        $resumeSimulation = Distribution::getResumeSimulation($this->db);

        Flight::render('simulation/index', [
            'besoins' => $besoinsData,
            'dons' => $donsData,
            'simulations' => $simulationsData,
            'distribuees' => $distribueesData,
            'resume' => $resumeSimulation
        ]);
    }

    /**
     * Lancer la simulation (créer les distributions en mode simulation)
     */
    public function simuler()
    {
        try {
            $resultats = Distribution::simulerDistribution($this->db);
            $resume = Distribution::getResumeSimulation($this->db);

            // Récupérer les détails de la simulation
            $simulations = Distribution::findSimulations($this->db);

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
     * Valider la simulation (rendre les distributions définitives)
     */
    public function valider()
    {
        try {
            // Valider les distributions simulées
            Distribution::validerSimulations($this->db);

            // Valider aussi les achats en attente
            Achat::validerTous($this->db);

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
    public function annuler()
    {
        try {
            Distribution::supprimerSimulations($this->db);

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
    public function getEtat()
    {
        $simulations = Distribution::findSimulations($this->db);
        $resume = Distribution::getResumeSimulation($this->db);
        $besoins = Besoin::findBesoinsNonSatisfaits($this->db);

        Flight::json([
            'success' => true,
            'simulations' => $simulations,
            'resume' => $resume,
            'besoins' => $besoins
        ]);
    }
}
