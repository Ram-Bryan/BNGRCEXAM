<?php

namespace dto;

/**
 * DTO pour afficher les dons complets avec données de la vue SQL
 */
class DTODon
{
    private ?int $id = null;
    private ?int $type_article_id = null;
    private ?int $quantite = null;
    private ?string $date_don = null;
    private ?string $donateur = null;
    private ?string $statut = null;

    private ?string $article_nom = null;
    private ?string $categorie = null;
    private ?float $prix_unitaire = null;
    private ?string $unite = null;
    private ?float $montant_total = null;

    private ?int $quantite_distribuee = null;
    private ?int $quantite_disponible = null;

    public function __construct() {}

    // ==================== GETTERS ====================
    
    public function getId(): ?int { return $this->id; }
    public function getTypeArticleId(): ?int { return $this->type_article_id; }
    public function getQuantite(): ?int { return $this->quantite; }
    public function getDateDon(): ?string { return $this->date_don; }
    public function getDonateur(): ?string { return $this->donateur; }
    public function getStatut(): ?string { return $this->statut; }
    
    public function getArticleNom(): ?string { return $this->article_nom; }
    public function getCategorie(): ?string { return $this->categorie; }
    public function getPrixUnitaire(): ?float { return $this->prix_unitaire; }
    public function getUnite(): ?string { return $this->unite; }
    public function getMontantTotal(): ?float { return $this->montant_total; }
    
    public function getQuantiteDistribuee(): ?int { return $this->quantite_distribuee; }
    public function getQuantiteDisponible(): ?int { return $this->quantite_disponible; }

    // ==================== SETTERS ====================
    
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setTypeArticleId(?int $type_article_id): self { $this->type_article_id = $type_article_id; return $this; }
    public function setQuantite(?int $quantite): self { $this->quantite = $quantite; return $this; }
    public function setDateDon(?string $date_don): self { $this->date_don = $date_don; return $this; }
    public function setDonateur(?string $donateur): self { $this->donateur = $donateur; return $this; }
    public function setStatut(?string $statut): self { $this->statut = $statut; return $this; }
    
    public function setArticleNom(?string $article_nom): self { $this->article_nom = $article_nom; return $this; }
    public function setCategorie(?string $categorie): self { $this->categorie = $categorie; return $this; }
    public function setPrixUnitaire(?float $prix_unitaire): self { $this->prix_unitaire = $prix_unitaire; return $this; }
    public function setUnite(?string $unite): self { $this->unite = $unite; return $this; }
    public function setMontantTotal(?float $montant_total): self { $this->montant_total = $montant_total; return $this; }
    
    public function setQuantiteDistribuee(?int $quantite_distribuee): self { $this->quantite_distribuee = $quantite_distribuee; return $this; }
    public function setQuantiteDisponible(?int $quantite_disponible): self { $this->quantite_disponible = $quantite_disponible; return $this; }

    public static function fromArray(array $data): DTODon
    {
        $dto = new DTODon();
        $dto->setId($data['id'] ?? null)
            ->setTypeArticleId($data['type_article_id'] ?? null)
            ->setQuantite($data['quantite'] ?? null)
            ->setDateDon($data['date_don'] ?? null)
            ->setDonateur($data['donateur'] ?? null)
            ->setStatut($data['statut'] ?? null)
            ->setArticleNom($data['article_nom'] ?? null)
            ->setCategorie($data['categorie'] ?? null)
            ->setPrixUnitaire($data['prix_unitaire'] ?? null)
            ->setUnite($data['unite'] ?? null)
            ->setMontantTotal($data['montant_total'] ?? null)
            ->setQuantiteDistribuee($data['quantite_distribuee'] ?? null)
            ->setQuantiteDisponible($data['quantite_disponible'] ?? null);

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

    public function getDateFormatee(): string
    {
        if (!$this->date_don) return '';
        return date('d/m/Y', strtotime($this->date_don));
    }

    public function getQuantiteFormatee(): string
    {
        if ($this->quantite === null) return '0';
        return number_format($this->quantite, 0, ',', ' ');
    }

    public function getQuantiteDistribueeFormatee(): string
    {
        if ($this->quantite_distribuee === null) return '0';
        return number_format($this->quantite_distribuee, 0, ',', ' ');
    }

    public function getQuantiteDisponibleFormatee(): string
    {
        if ($this->quantite_disponible === null) return '0';
        return number_format($this->quantite_disponible, 0, ',', ' ');
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

    public function isDisponible(): bool
    {
        return ($this->quantite_disponible ?? 0) > 0;
    }

    public function isPeutSupprimer(): bool
    {
        return $this->quantite_disponible == $this->quantite;
    }
}

