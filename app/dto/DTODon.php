<?php

namespace dto;

/**
 * DTO pour afficher les dons complets avec données de la vue SQL
 */
class DTODon
{
    public ?int $id = null;
    public ?int $type_article_id = null;
    public ?int $quantite = null;
    public ?string $date_don = null;
    public ?string $donateur = null;
    public ?string $statut = null;

    public ?string $article_nom = null;
    public ?string $categorie = null;
    public ?float $prix_unitaire = null;
    public ?string $unite = null;
    public ?float $montant_total = null;

    public ?int $quantite_distribuee = null;
    public ?int $quantite_disponible = null;

    public function __construct() {}

    public static function fromArray(array $data): DTODon
    {
        $dto = new DTODon();
        $dto->id = $data['id'] ?? null;
        $dto->type_article_id = $data['type_article_id'] ?? null;
        $dto->quantite = $data['quantite'] ?? null;
        $dto->date_don = $data['date_don'] ?? null;
        $dto->donateur = $data['donateur'] ?? null;
        $dto->statut = $data['statut'] ?? null;

        $dto->article_nom = $data['article_nom'] ?? null;
        $dto->categorie = $data['categorie'] ?? null;
        $dto->prix_unitaire = $data['prix_unitaire'] ?? null;
        $dto->unite = $data['unite'] ?? null;
        $dto->montant_total = $data['montant_total'] ?? null;

        $dto->quantite_distribuee = $data['quantite_distribuee'] ?? null;
        $dto->quantite_disponible = $data['quantite_disponible'] ?? null;

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
        return match ($this->categorie) {
            'nature' => 'badge-nature',
            'argent' => 'badge-argent',
            'material' => 'badge-material',
            default => 'badge-default'
        };
    }

    public function getDateDonFormatee(): string
    {
        if (!$this->date_don) return '';
        return date('d/m/Y', strtotime($this->date_don));
    }

    public function getMontantFormate(): string
    {
        if ($this->montant_total === null) return '0,00';
        return number_format($this->montant_total, 2, ',', ' ');
    }

    public function getStatutClass(): string
    {
        return match ($this->statut) {
            'disponible' => 'badge-success',
            'distribue' => 'badge-secondary',
            default => 'badge-default'
        };
    }
}
