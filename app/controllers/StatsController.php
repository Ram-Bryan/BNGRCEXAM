<?php

namespace controllers;

use models\Besoin;
use dto\DTOBesoin;
use Flight;
use PDO;

class StatsController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    /**
     * Afficher la liste des villes avec stats globales
     */
    public function listVilles()
    {
        $sql = "SELECT * FROM vue_stats_villes ORDER BY region_nom, ville_nom";
        $stmt = $this->db->query($sql);
        $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        Flight::render('stats/villes', [
            'villes' => $villes
        ]);
    }

    /**
     * Afficher les besoins d'une ville avec ratio de satisfaction
     */
    public function showVilleDetail($id)
    {
        // Info de la ville
        $sqlVille = "SELECT * FROM vue_stats_villes WHERE ville_id = :id";
        $stmt = $this->db->prepare($sqlVille);
        $stmt->execute([':id' => $id]);
        $ville = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$ville) {
            Flight::redirect('/stats?error=ville_not_found');
            return;
        }
        
        // Besoins de cette ville avec satisfaction
        $besoinsData = Besoin::findBesoinsSatisfactionByVille($this->db, $id);
        $besoins = DTOBesoin::fromArrayMultiple($besoinsData);
        
        Flight::render('stats/ville_detail', [
            'ville' => $ville,
            'besoins' => $besoins
        ]);
    }
}
