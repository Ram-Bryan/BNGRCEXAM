<?php

namespace dto;

/**
 * DTO pour afficher les besoins complets avec données de la vue SQL
 */
class DTOBesoin
{
    public ?int $id = null;
    public ?int $ville_id = null;
    public ?int $type_article_id = null;
    public ?float $quantite = null;
    public ?string $date_demande = null;
    
    public ?string $ville_nom = null;
    public ?int $nbsinistres = null;
    
    public ?int $region_id = null;
    public ?string $region_nom = null;
    
    public ?string $article_nom = null;
    public ?string $categorie = null;
    public ?float $prix_unitaire = null;
    public ?string $unite = null;
    
    public ?float $montant_total = null;

    // Champs de satisfaction (optionnels, peuplés depuis vue_besoins_satisfaction)
    public ?float $quantite_recue = null;
    public ?float $quantite_restante = null;
    public ?float $ratio_satisfaction = null;

    public function __construct()
    {
    }

    public static function fromArray(array $data): DTOBesoin
    {
        $dto = new DTOBesoin();
        $dto->id = $data['id'] ?? null;
        $dto->ville_id = $data['ville_id'] ?? null;
        $dto->type_article_id = $data['type_article_id'] ?? null;
        $dto->quantite = $data['quantite'] ?? ($data['quantite_demandee'] ?? null);
        $dto->date_demande = $data['date_demande'] ?? null;
        
        $dto->ville_nom = $data['ville_nom'] ?? null;
        $dto->nbsinistres = $data['nbsinistres'] ?? null;
        
        $dto->region_id = $data['region_id'] ?? null;
        $dto->region_nom = $data['region_nom'] ?? null;
        
        $dto->article_nom = $data['article_nom'] ?? null;
        $dto->categorie = $data['categorie'] ?? null;
        $dto->prix_unitaire = $data['prix_unitaire'] ?? null;
        $dto->unite = $data['unite'] ?? null;
        
        // Satisfaction
        $dto->quantite_recue = $data['quantite_recue'] ?? null;
        $dto->quantite_restante = $data['quantite_restante'] ?? null;
        $dto->ratio_satisfaction = $data['ratio_satisfaction'] ?? null;
        
        if ($dto->quantite !== null && $dto->prix_unitaire !== null) {
            $dto->montant_total = $dto->quantite * $dto->prix_unitaire;
        }
        
        return $dto;
    }

    public static function fromArrayMultiple(array $dataArray): array
    {
        $results = [];
        foreach ($dataArray as $data) {
            $results[] = self::fromArray($data);
        }
        return $results;
    }

    public function getCategorieClass(): string
    {
        return match($this->categorie) {
            'nature' => 'badge-nature',
            'argent' => 'badge-argent',
            'material' => 'badge-material',
            default => 'badge-default'
        };
    }

    public function getDateFormatee(): string
    {
        if (!$this->date_demande) return '';
        return date('d/m/Y', strtotime($this->date_demande));
    }

    public function getMontantTotalFormate(): string
    {
        if ($this->montant_total === null) return '0,00';
        return number_format($this->montant_total, 2, ',', ' ');
    }

    public function getQuantiteFormatee(): string
    {
        if ($this->quantite === null) return '0';
        return number_format($this->quantite, 0, ',', ' ');
    }

    public function getPrixUnitaireFormate(): string
    {
        if ($this->prix_unitaire === null) return '0,00';
        return number_format($this->prix_unitaire, 2, ',', ' ');
    }

    public function getRatioClass(): string
    {
        if ($this->ratio_satisfaction === null) return 'ratio-none';
        if ($this->ratio_satisfaction >= 100) return 'ratio-complete';
        if ($this->ratio_satisfaction >= 50) return 'ratio-partial';
        return 'ratio-low';
    }

    public function getRatioFormate(): string
    {
        if ($this->ratio_satisfaction === null) return '0%';
        return number_format($this->ratio_satisfaction, 1, ',', ' ') . '%';
    }
}
