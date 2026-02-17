<?php

namespace dto;

use PDO;
use models\Besoin;

/**
 * DTO pour les statistiques par ville
 * Encapsule toutes les requêtes liées aux stats
 */
class DTOStats
{
    // Propriétés pour stats globales
    private ?int $nombre_total_villes = null;
    private ?int $nombre_total_besoins = null;
    private ?float $total_quantite_demandee = null;
    private ?float $total_quantite_recue = null;
    private ?float $ratio_satisfaction_global = null;

    // Cache optionnel
    private array $villes = [];

    public function __construct() {}

    // ==================== GETTERS ====================

    public function getNombreTotalVilles(): ?int
    {
        return $this->nombre_total_villes;
    }

    public function getNombreTotalBesoins(): ?int
    {
        return $this->nombre_total_besoins;
    }

    public function getTotalQuantiteDemandee(): ?float
    {
        return $this->total_quantite_demandee;
    }

    public function getTotalQuantiteRecue(): ?float
    {
        return $this->total_quantite_recue;
    }

    public function getRatioSatisfactionGlobal(): ?float
    {
        return $this->ratio_satisfaction_global;
    }

    public function getVilles(): array
    {
        return $this->villes;
    }

    // ==================== SETTERS ====================

    public function setNombreTotalVilles(?int $nombre_total_villes): self
    {
        $this->nombre_total_villes = $nombre_total_villes;
        return $this;
    }

    public function setNombreTotalBesoins(?int $nombre_total_besoins): self
    {
        $this->nombre_total_besoins = $nombre_total_besoins;
        return $this;
    }

    public function setTotalQuantiteDemandee(?float $total_quantite_demandee): self
    {
        $this->total_quantite_demandee = $total_quantite_demandee;
        return $this;
    }

    public function setTotalQuantiteRecue(?float $total_quantite_recue): self
    {
        $this->total_quantite_recue = $total_quantite_recue;
        return $this;
    }

    public function setRatioSatisfactionGlobal(?float $ratio_satisfaction_global): self
    {
        $this->ratio_satisfaction_global = $ratio_satisfaction_global;
        return $this;
    }

    public function setVilles(array $villes): self
    {
        $this->villes = $villes;
        return $this;
    }

    // ==================== MÉTHODES DAO STATIQUES ====================

    /**
     * Récupérer toutes les villes avec leurs statistiques
     */
    public static function getAllVilles(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_stats_villes ORDER BY region_nom, ville_nom";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une ville par son ID
     */
    public static function getVilleById(PDO $db, int $id): ?array
    {
        $sql = "SELECT * FROM v_bngrc_stats_villes WHERE ville_id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    /**
     * Récupérer les besoins d'une ville avec satisfaction
     * Retourne un tableau de DTOBesoin
     */
    public static function getBesoinsVille(PDO $db, int $ville_id): array
    {
        $besoinsData = Besoin::findBesoinsSatisfactionByVille($db, $ville_id);
        return DTOBesoin::fromArrayMultiple($besoinsData);
    }

    /**
     * Calculer les statistiques globales à partir de toutes les villes
     */
    public static function getStatsGlobales(PDO $db): array
    {
        $villes = self::getAllVilles($db);
        
        $nombreVilles = count($villes);
        $totalBesoins = 0;
        $totalDemande = 0;
        $totalRecue = 0;
        
        foreach ($villes as $ville) {
            $totalBesoins += (int)($ville['nombre_besoins'] ?? 0);
            $totalDemande += (float)($ville['total_quantite_demandee'] ?? 0);
            $totalRecue += (float)($ville['total_quantite_recue'] ?? 0);
        }
        
        $ratioGlobal = $totalDemande > 0 
            ? round(($totalRecue / $totalDemande) * 100, 1) 
            : 0;
        
        return [
            'nombre_total_villes' => $nombreVilles,
            'nombre_total_besoins' => $totalBesoins,
            'total_quantite_demandee' => $totalDemande,
            'total_quantite_recue' => $totalRecue,
            'ratio_satisfaction_global' => $ratioGlobal
        ];
    }

    // ==================== HELPERS STATIQUES ====================

    /**
     * Retourner la classe CSS appropriée pour un ratio de satisfaction
     */
    public static function getRatioClass(float $ratio): string
    {
        if ($ratio >= 100) return 'progress-complete';
        if ($ratio >= 50) return 'progress-partial';
        return 'progress-low';
    }

    /**
     * Formater une quantité avec séparateur de milliers
     */
    public static function formatQuantite(float $quantite): string
    {
        return number_format($quantite, 0, '', ' ');
    }

    /**
     * Formater un ratio en pourcentage
     */
    public static function formatRatio(float $ratio): string
    {
        return number_format($ratio, 1) . '%';
    }
}
