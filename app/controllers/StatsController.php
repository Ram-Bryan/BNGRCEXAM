<?php

namespace controllers;

use dto\DTOStats;
use Flight;

class StatsController
{
    /**
     * Afficher la liste des villes avec stats globales
     */
    public static function listVilles(): void
    {
        $db = Flight::db();
        $villes = DTOStats::getAllVilles($db);
        $stats = DTOStats::getStatsGlobales($db);
        
        Flight::render('stats/villes', [
            'villes' => $villes,
            'stats' => $stats
        ]);
    }

    /**
     * Afficher les besoins d'une ville avec ratio de satisfaction
     */
    public static function showVilleDetail(int $id): void
    {
        $db = Flight::db();
        $ville = DTOStats::getVilleById($db, $id);
        
        if (!$ville) {
            Flight::redirect('/stats?error=ville_not_found');
            return;
        }
        
        $besoins = DTOStats::getBesoinsVille($db, $id);
        
        Flight::render('stats/ville_detail', [
            'ville' => $ville,
            'besoins' => $besoins
        ]);
    }
}
