<?php

namespace dto;

/**
 * DTO pour afficher les dons complets avec données de la vue SQL
 */
class DTODon
{
    public ?int $id = null;
    public ?int $besoin_id = null;
    public ?int $quantite_don = null;
    public ?string $date_livraison = null;
    
    public ?float $quantite_besoin = null;
    public ?string $date_demande = null;
    public ?int $ville_id = null;
    public ?int $type_article_id = null;
    
    public ?string $ville_nom = null;
    public ?int $nbsinistres = null;
    public ?int $region_id = null;
    public ?string $region_nom = null;
    
    public ?string $article_nom = null;
    public ?string $categorie = null;
    public ?float $prix_unitaire = null;
    public ?string $unite = null;

    public function __construct()
    {
    }

    public static function fromArray(array $data): DTODon
    {
        $dto = new DTODon();
        $dto->id = $data['id'] ?? null;
        $dto->besoin_id = $data['besoin_id'] ?? null;
        $dto->quantite_don = $data['quantite_don'] ?? null;
        $dto->date_livraison = $data['date_livraison'] ?? null;
        
        $dto->quantite_besoin = $data['quantite_besoin'] ?? null;
        $dto->date_demande = $data['date_demande'] ?? null;
        $dto->ville_id = $data['ville_id'] ?? null;
        $dto->type_article_id = $data['type_article_id'] ?? null;
        
        $dto->ville_nom = $data['ville_nom'] ?? null;
        $dto->nbsinistres = $data['nbsinistres'] ?? null;
        $dto->region_id = $data['region_id'] ?? null;
        $dto->region_nom = $data['region_nom'] ?? null;
        
        $dto->article_nom = $data['article_nom'] ?? null;
        $dto->categorie = $data['categorie'] ?? null;
        $dto->prix_unitaire = $data['prix_unitaire'] ?? null;
        $dto->unite = $data['unite'] ?? null;
        
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

    public function getDateLivraisonFormatee(): string
    {
        if (!$this->date_livraison) return '';
        return date('d/m/Y', strtotime($this->date_livraison));
    }

    public function getDateDemandeFormatee(): string
    {
        if (!$this->date_demande) return '';
        return date('d/m/Y', strtotime($this->date_demande));
    }

    public function getMontantDon(): string
    {
        if ($this->quantite_don === null || $this->prix_unitaire === null) return '0,00';
        return number_format($this->quantite_don * $this->prix_unitaire, 2, ',', ' ');
    }
}
