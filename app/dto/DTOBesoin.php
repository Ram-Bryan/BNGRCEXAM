<?php

namespace dto;

/**
 * DTO pour afficher les besoins complets avec données de la vue SQL
 */
class DTOBesoin
{
    private ?int $id = null;
    private ?int $ville_id = null;
    private ?int $type_article_id = null;
    private ?float $quantite = null;
    private ?string $date_demande = null;
    
    private ?string $ville_nom = null;
    private ?int $nbsinistres = null;
    
    private ?int $region_id = null;
    private ?string $region_nom = null;
    
    private ?string $article_nom = null;
    private ?string $categorie = null;
    private ?float $prix_unitaire = null;
    private ?string $unite = null;
    
    private ?float $montant_total = null;

    // Champs de satisfaction (optionnels, peuplés depuis vue_besoins_satisfaction)
    private ?float $quantite_recue = null;
    private ?float $quantite_restante = null;
    private ?float $ratio_satisfaction = null;

    public function __construct()
    {
    }

    // ==================== GETTERS ====================
    
    public function getId(): ?int { return $this->id; }
    public function getVilleId(): ?int { return $this->ville_id; }
    public function getTypeArticleId(): ?int { return $this->type_article_id; }
    public function getQuantite(): ?float { return $this->quantite; }
    public function getDateDemande(): ?string { return $this->date_demande; }
    
    public function getVilleNom(): ?string { return $this->ville_nom; }
    public function getNbsinistres(): ?int { return $this->nbsinistres; }
    
    public function getRegionId(): ?int { return $this->region_id; }
    public function getRegionNom(): ?string { return $this->region_nom; }
    
    public function getArticleNom(): ?string { return $this->article_nom; }
    public function getCategorie(): ?string { return $this->categorie; }
    public function getPrixUnitaire(): ?float { return $this->prix_unitaire; }
    public function getUnite(): ?string { return $this->unite; }
    
    public function getMontantTotal(): ?float { return $this->montant_total; }
    public function getQuantiteRecue(): ?float { return $this->quantite_recue; }
    public function getQuantiteRestante(): ?float { return $this->quantite_restante; }
    public function getRatioSatisfaction(): ?float { return $this->ratio_satisfaction; }

    // ==================== SETTERS ====================
    
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setVilleId(?int $ville_id): self { $this->ville_id = $ville_id; return $this; }
    public function setTypeArticleId(?int $type_article_id): self { $this->type_article_id = $type_article_id; return $this; }
    public function setQuantite(?float $quantite): self { $this->quantite = $quantite; return $this; }
    public function setDateDemande(?string $date_demande): self { $this->date_demande = $date_demande; return $this; }
    
    public function setVilleNom(?string $ville_nom): self { $this->ville_nom = $ville_nom; return $this; }
    public function setNbsinistres(?int $nbsinistres): self { $this->nbsinistres = $nbsinistres; return $this; }
    
    public function setRegionId(?int $region_id): self { $this->region_id = $region_id; return $this; }
    public function setRegionNom(?string $region_nom): self { $this->region_nom = $region_nom; return $this; }
    
    public function setArticleNom(?string $article_nom): self { $this->article_nom = $article_nom; return $this; }
    public function setCategorie(?string $categorie): self { $this->categorie = $categorie; return $this; }
    public function setPrixUnitaire(?float $prix_unitaire): self { $this->prix_unitaire = $prix_unitaire; return $this; }
    public function setUnite(?string $unite): self { $this->unite = $unite; return $this; }
    
    public function setMontantTotal(?float $montant_total): self { $this->montant_total = $montant_total; return $this; }
    public function setQuantiteRecue(?float $quantite_recue): self { $this->quantite_recue = $quantite_recue; return $this; }
    public function setQuantiteRestante(?float $quantite_restante): self { $this->quantite_restante = $quantite_restante; return $this; }
    public function setRatioSatisfaction(?float $ratio_satisfaction): self { $this->ratio_satisfaction = $ratio_satisfaction; return $this; }

    public static function fromArray(array $data): DTOBesoin
    {
        $dto = new DTOBesoin();
        $dto->setId($data['id'] ?? null)
            ->setVilleId($data['ville_id'] ?? null)
            ->setTypeArticleId($data['type_article_id'] ?? null)
            ->setQuantite($data['quantite'] ?? ($data['quantite_demandee'] ?? null))
            ->setDateDemande($data['date_demande'] ?? null)
            ->setVilleNom($data['ville_nom'] ?? null)
            ->setNbsinistres($data['nbsinistres'] ?? null)
            ->setRegionId($data['region_id'] ?? null)
            ->setRegionNom($data['region_nom'] ?? null)
            ->setArticleNom($data['article_nom'] ?? null)
            ->setCategorie($data['categorie'] ?? null)
            ->setPrixUnitaire($data['prix_unitaire'] ?? null)
            ->setUnite($data['unite'] ?? null)
            ->setQuantiteRecue($data['quantite_recue'] ?? null)
            ->setQuantiteRestante($data['quantite_restante'] ?? null)
            ->setRatioSatisfaction($data['ratio_satisfaction'] ?? null);
        
        if ($dto->getQuantite() !== null && $dto->getPrixUnitaire() !== null) {
            $dto->setMontantTotal($dto->getQuantite() * $dto->getPrixUnitaire());
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

    /**
     * Retourne true si le besoin est considéré comme satisfait.
     * Critères : quantite_restante <= 0 ou ratio_satisfaction >= 100
     */
    public function isSatisfait(): bool
    {
        if ($this->quantite_restante !== null) {
            return $this->quantite_restante <= 0;
        }
        if ($this->ratio_satisfaction !== null) {
            return $this->ratio_satisfaction >= 100;
        }
        return false;
    }

    public function getSatisfactionLabel(): string
    {
        return $this->isSatisfait() ? 'Satisfait' : 'Non satisfait';
    }

    public function getSatisfactionClass(): string
    {
        return $this->isSatisfait() ? 'badge-satisfied' : 'badge-not-satisfied';
    }
}
