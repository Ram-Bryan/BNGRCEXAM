<?php

namespace controllers;

use dto\DTORecap;
use Flight;
use PDO;

class RecapController
{
    /**
     * Afficher la page de récapitulation
     */
    public static function showRecap(): void
    {
        $db = Flight::db();
        $dtoRecap = DTORecap::getRecapComplet($db);
        $recap = $dtoRecap->toArray();

        // Calculer la classe CSS pour le ratio global
        $ratioGlobal = $recap['ratio_global'] ?? 0;
        $ratioClass = $ratioGlobal >= 100 ? 'ratio-complete' : 
                     ($ratioGlobal >= 75 ? 'ratio-high' :
                     ($ratioGlobal >= 50 ? 'ratio-partial' : 'ratio-low'));

        // Calculer les classes pour chaque catégorie
        $statsParCategorie = $recap['stats_par_categorie'] ?? [];
        foreach ($statsParCategorie as &$stat) {
            $ratio = $stat['ratio_satisfaction'] ?? 0;
            $stat['ratio_class'] = $ratio >= 100 ? 'ratio-complete' :
                                  ($ratio >= 75 ? 'ratio-high' :
                                  ($ratio >= 50 ? 'ratio-partial' : 'ratio-low'));
        }
        unset($stat);

        Flight::render('recap/index', [
            'recap' => $recap,
            'dto' => $dtoRecap,
            'ratioClass' => $ratioClass,
            'statsParCategorie' => $statsParCategorie
        ]);
    }

    /**
     * API pour actualisation AJAX
     */
    public static function getRecapAjax(): void
    {
        $db = Flight::db();
        $dtoRecap = DTORecap::getRecapComplet($db);
        $recap = $dtoRecap->toArray();

        Flight::json([
            'success' => true,
            'data' => $recap
        ]);
    }
}
