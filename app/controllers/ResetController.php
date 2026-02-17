<?php

namespace controllers;

use utils\Reset;
use Flight;
use PDO;

/**
 * Contrôleur pour la réinitialisation des données du système
 */
class ResetController
{
    /**
     * Réinitialiser les données du système aux valeurs initiales
     * Route: POST /reset
     * Retourne JSON
     */
    public static function reset(): void
    {
        $db = Flight::db();

        try {
            // Vérifier d'abord que les données initiales existent
            $check = Reset::checkInitialDataExists($db);
            
            if (!$check['exists']) {
                Flight::json([
                    'success' => false,
                    'message' => 'Les données initiales n\'existent pas. Veuillez d\'abord exécuter le script donneeinitial.sql'
                ]);
                return;
            }

            // Obtenir les stats avant réinitialisation
            $statsBefore = Reset::getResetStats($db);

            // Exécuter la réinitialisation
            $result = Reset::resetToInitialData($db);

            // Ajouter les stats dans la réponse
            if ($result['success']) {
                $result['stats'] = [
                    'before' => $statsBefore['current'],
                    'after' => [
                        'besoins' => $statsBefore['initial']['besoins'],
                        'dons' => $statsBefore['initial']['dons'],
                        'distributions' => 0,
                        'achats' => 0
                    ]
                ];
            }

            Flight::json($result);

        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenir les statistiques de réinitialisation
     * Route: GET /reset/stats
     * Retourne JSON
     */
    public static function getStats(): void
    {
        $db = Flight::db();

        try {
            $stats = Reset::getResetStats($db);
            $check = Reset::checkInitialDataExists($db);

            Flight::json([
                'success' => true,
                'stats' => $stats,
                'initial_data_exists' => $check['exists'],
                'message' => $check['message']
            ]);

        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ]);
        }
    }
}
