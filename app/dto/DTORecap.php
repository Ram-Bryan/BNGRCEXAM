<?php

namespace dto;

use PDO;

/**
 * DTO pour la récapitulation globale
 * Encapsule toutes les statistiques du système
 */
class DTORecap
{
    // Propriétés principales
    private ?float $montant_total_besoins = null;
    private ?float $montant_satisfait = null;
    private ?float $montant_restant = null;
    private ?int $nombre_besoins = null;
    private ?int $nombre_besoins_satisfaits = null;
    
    // Dons
    private ?float $total_dons_disponibles = null;
    private ?float $total_dons_nature = null;
    private ?float $total_dons_argent = null;
    private ?float $argent_disponible = null;
    
    // Achats
    private ?float $total_achats_valides = null;
    private ?float $total_achats_attente = null;
    
    // Simulations
    private ?int $nb_simulations = null;
    private ?float $total_simulations = null;
    
    // Statistiques par catégorie
    private array $stats_par_categorie = [];
    
    // Ratio global
    private ?float $ratio_global = null;

    public function __construct() {}

    // ==================== GETTERS ====================

    public function getMontantTotalBesoins(): ?float
    {
        return $this->montant_total_besoins;
    }

    public function getMontantSatisfait(): ?float
    {
        return $this->montant_satisfait;
    }

    public function getMontantRestant(): ?float
    {
        return $this->montant_restant;
    }

    public function getNombreBesoins(): ?int
    {
        return $this->nombre_besoins;
    }

    public function getNombreBesoinsSatisfaits(): ?int
    {
        return $this->nombre_besoins_satisfaits;
    }

    public function getTotalDonsDisponibles(): ?float
    {
        return $this->total_dons_disponibles;
    }

    public function getTotalDonsNature(): ?float
    {
        return $this->total_dons_nature;
    }

    public function getTotalDonsArgent(): ?float
    {
        return $this->total_dons_argent;
    }

    public function getArgentDisponible(): ?float
    {
        return $this->argent_disponible;
    }

    public function getTotalAchatsValides(): ?float
    {
        return $this->total_achats_valides;
    }

    public function getTotalAchatsAttente(): ?float
    {
        return $this->total_achats_attente;
    }

    public function getNbSimulations(): ?int
    {
        return $this->nb_simulations;
    }

    public function getTotalSimulations(): ?float
    {
        return $this->total_simulations;
    }

    public function getStatsParCategorie(): array
    {
        return $this->stats_par_categorie;
    }

    public function getRatioGlobal(): ?float
    {
        return $this->ratio_global;
    }

    // ==================== SETTERS ====================

    public function setMontantTotalBesoins(?float $montant_total_besoins): self
    {
        $this->montant_total_besoins = $montant_total_besoins;
        return $this;
    }

    public function setMontantSatisfait(?float $montant_satisfait): self
    {
        $this->montant_satisfait = $montant_satisfait;
        return $this;
    }

    public function setMontantRestant(?float $montant_restant): self
    {
        $this->montant_restant = $montant_restant;
        return $this;
    }

    public function setNombreBesoins(?int $nombre_besoins): self
    {
        $this->nombre_besoins = $nombre_besoins;
        return $this;
    }

    public function setNombreBesoinsSatisfaits(?int $nombre_besoins_satisfaits): self
    {
        $this->nombre_besoins_satisfaits = $nombre_besoins_satisfaits;
        return $this;
    }

    public function setTotalDonsDisponibles(?float $total_dons_disponibles): self
    {
        $this->total_dons_disponibles = $total_dons_disponibles;
        return $this;
    }

    public function setTotalDonsNature(?float $total_dons_nature): self
    {
        $this->total_dons_nature = $total_dons_nature;
        return $this;
    }

    public function setTotalDonsArgent(?float $total_dons_argent): self
    {
        $this->total_dons_argent = $total_dons_argent;
        return $this;
    }

    public function setArgentDisponible(?float $argent_disponible): self
    {
        $this->argent_disponible = $argent_disponible;
        return $this;
    }

    public function setTotalAchatsValides(?float $total_achats_valides): self
    {
        $this->total_achats_valides = $total_achats_valides;
        return $this;
    }

    public function setTotalAchatsAttente(?float $total_achats_attente): self
    {
        $this->total_achats_attente = $total_achats_attente;
        return $this;
    }

    public function setNbSimulations(?int $nb_simulations): self
    {
        $this->nb_simulations = $nb_simulations;
        return $this;
    }

    public function setTotalSimulations(?float $total_simulations): self
    {
        $this->total_simulations = $total_simulations;
        return $this;
    }

    public function setStatsParCategorie(array $stats_par_categorie): self
    {
        $this->stats_par_categorie = $stats_par_categorie;
        return $this;
    }

    public function setRatioGlobal(?float $ratio_global): self
    {
        $this->ratio_global = $ratio_global;
        return $this;
    }

    // ==================== MÉTHODES DAO STATIQUES ====================

    /**
     * Récupérer le récapitulatif depuis la vue principale
     */
    public static function getRecapitulatifBesoins(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_recapitulatif_besoins";
        $stmt = $db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Total des dons disponibles (toutes catégories)
     */
    public static function getTotalDonsDisponiblesData(PDO $db): float
    {
        $sql = "SELECT total FROM v_bngrc_total_dons_disponibles";
        $stmt = $db->query($sql);
        return (float)$stmt->fetchColumn();
    }

    /**
     * Total des dons en nature/material (non argent)
     */
    public static function getTotalDonsNatureData(PDO $db): float
    {
        $sql = "SELECT total FROM v_bngrc_total_dons_nature";
        $stmt = $db->query($sql);
        return (float)$stmt->fetchColumn();
    }

    /**
     * Total des dons en argent
     */
    public static function getTotalDonsArgentData(PDO $db): float
    {
        $sql = "SELECT total FROM v_bngrc_total_dons_argent";
        $stmt = $db->query($sql);
        return (float)$stmt->fetchColumn();
    }

    /**
     * Argent disponible (non utilisé dans achats)
     */
    public static function getArgentDisponibleData(PDO $db): float
    {
        $sql = "SELECT argent_disponible FROM v_bngrc_argent_disponible";
        $stmt = $db->query($sql);
        return (float)($stmt->fetchColumn() ?: 0);
    }

    /**
     * Total des achats validés
     */
    public static function getTotalAchatsValidesData(PDO $db): float
    {
        $sql = "SELECT COALESCE(SUM(montant_total), 0) AS total FROM bngrc_achat WHERE valide = TRUE";
        $stmt = $db->query($sql);
        return (float)$stmt->fetchColumn();
    }

    /**
     * Total des achats en attente
     */
    public static function getTotalAchatsAttenteData(PDO $db): float
    {
        $sql = "SELECT COALESCE(SUM(montant_total), 0) AS total FROM bngrc_achat WHERE valide = FALSE";
        $stmt = $db->query($sql);
        return (float)$stmt->fetchColumn();
    }

    /**
     * Distributions en simulation
     */
    public static function getSimulationsData(PDO $db): array
    {
        $sql = "SELECT COUNT(*) as nb, COALESCE(SUM(quantite), 0) as total 
                FROM bngrc_distribution WHERE est_simulation = TRUE";
        $stmt = $db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['nb' => 0, 'total' => 0];
    }

    /**
     * Statistiques par catégorie avec distribution
     */
    public static function getStatsParCategorieData(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_stats_par_categorie";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Nombre total de besoins
     */
    public static function getNombreBesoinsData(PDO $db): int
    {
        $sql = "SELECT COUNT(*) FROM bngrc_besoin";
        $stmt = $db->query($sql);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Nombre de besoins satisfaits (100%)
     */
    public static function getNombreBesoinsSatisfaitsData(PDO $db): int
    {
        $sql = "SELECT COUNT(*) FROM v_bngrc_besoins_satisfaction WHERE ratio_satisfaction >= 100";
        $stmt = $db->query($sql);
        return (int)$stmt->fetchColumn();
    }

    // ==================== MÉTHODE PRINCIPALE ====================

    /**
     * Récupérer toutes les données de récapitulation
     * Assemble toutes les statistiques en un seul objet
     */
    public static function getRecapComplet(PDO $db): DTORecap
    {
        $recap = self::getRecapitulatifBesoins($db);
        $simulations = self::getSimulationsData($db);
        
        $montantTotal = (float)($recap['montant_total_besoins'] ?? 0);
        $montantSatisfait = (float)($recap['montant_satisfait'] ?? 0);
        $montantRestant = (float)($recap['montant_restant'] ?? $montantTotal - $montantSatisfait);

        $dto = new DTORecap();
        $dto->setMontantTotalBesoins($montantTotal)
            ->setMontantSatisfait($montantSatisfait)
            ->setMontantRestant($montantRestant)
            ->setNombreBesoins(self::getNombreBesoinsData($db))
            ->setNombreBesoinsSatisfaits(self::getNombreBesoinsSatisfaitsData($db))
            ->setArgentDisponible(self::getArgentDisponibleData($db))
            ->setTotalDonsDisponibles(self::getTotalDonsDisponiblesData($db))
            ->setTotalDonsNature(self::getTotalDonsNatureData($db))
            ->setTotalDonsArgent(self::getTotalDonsArgentData($db))
            ->setTotalAchatsValides(self::getTotalAchatsValidesData($db))
            ->setTotalAchatsAttente(self::getTotalAchatsAttenteData($db))
            ->setNbSimulations((int)($simulations['nb'] ?? 0))
            ->setTotalSimulations((float)($simulations['total'] ?? 0))
            ->setStatsParCategorie(self::getStatsParCategorieData($db))
            ->setRatioGlobal($montantTotal > 0
                ? round(($montantSatisfait / $montantTotal) * 100, 2)
                : 0);

        return $dto;
    }

    /**
     * Convertir en tableau pour la vue
     */
    public function toArray(): array
    {
        return [
            'montant_total_besoins' => $this->montant_total_besoins,
            'montant_satisfait' => $this->montant_satisfait,
            'montant_restant' => $this->montant_restant,
            'nombre_besoins' => $this->nombre_besoins,
            'nombre_besoins_satisfaits' => $this->nombre_besoins_satisfaits,
            'argent_disponible' => $this->argent_disponible,
            'total_dons_disponibles' => $this->total_dons_disponibles,
            'total_dons_nature' => $this->total_dons_nature,
            'total_dons_argent' => $this->total_dons_argent,
            'total_achats_valides' => $this->total_achats_valides,
            'total_achats_attente' => $this->total_achats_attente,
            'nb_simulations' => $this->nb_simulations,
            'total_simulations' => $this->total_simulations,
            'stats_par_categorie' => $this->stats_par_categorie,
            'ratio_global' => $this->ratio_global
        ];
    }

    // ==================== HELPERS FORMATAGE ====================

    public function getMontantTotalBesoinsFormate(): string
    {
        return number_format($this->montant_total_besoins ?? 0, 0, '', ' ') . ' Ar';
    }

    public function getMontantSatisfaitFormate(): string
    {
        return number_format($this->montant_satisfait ?? 0, 0, '', ' ') . ' Ar';
    }

    public function getMontantRestantFormate(): string
    {
        return number_format($this->montant_restant ?? 0, 0, '', ' ') . ' Ar';
    }

    public function getArgentDisponibleFormate(): string
    {
        return number_format($this->argent_disponible ?? 0, 0, '', ' ') . ' Ar';
    }

    public function getRatioGlobalFormate(): string
    {
        return number_format($this->ratio_global ?? 0, 1) . '%';
    }

    public function getRatioClass(): string
    {
        $ratio = $this->ratio_global ?? 0;
        if ($ratio >= 100) return 'success';
        if ($ratio >= 75) return 'warning';
        if ($ratio >= 50) return 'partial';
        return 'danger';
    }
}
