<?php

namespace models;

use PDO;

class TypeArticle
{
    private ?int $id = null;
    private ?string $nom = null;
    private ?string $categorie = null;
    private ?float $prix_unitaire = null;
    private ?string $unite = null;

    public function __construct()
    {
    }

    // ==================== GETTERS ====================
    
    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function getCategorie(): ?string { return $this->categorie; }
    public function getPrixUnitaire(): ?float { return $this->prix_unitaire; }
    public function getUnite(): ?string { return $this->unite; }

    // ==================== SETTERS ====================
    
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setNom(?string $nom): self { $this->nom = $nom; return $this; }
    public function setCategorie(?string $categorie): self { $this->categorie = $categorie; return $this; }
    public function setPrixUnitaire(?float $prix_unitaire): self { $this->prix_unitaire = $prix_unitaire; return $this; }
    public function setUnite(?string $unite): self { $this->unite = $unite; return $this; }

    // ==================== MÉTHODES DE BASE DE DONNÉES ====================

    public function create(PDO $db): bool
    {
        $sql = "INSERT INTO type_articles (nom, categorie, prix_unitaire, unite) 
                VALUES (:nom, :categorie, :prix_unitaire, :unite)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':nom' => $this->nom,
            ':categorie' => $this->categorie,
            ':prix_unitaire' => $this->prix_unitaire,
            ':unite' => $this->unite
        ]);
        if ($result) {
            $this->id = $db->lastInsertId();
        }
        return $result;
    }

    public static function findById(PDO $db, int $id): ?TypeArticle
    {
        $sql = "SELECT * FROM type_articles WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        if (!$data) return null;
        
        $article = new TypeArticle();
        $article->setId($data['id'])
                ->setNom($data['nom'])
                ->setCategorie($data['categorie'])
                ->setPrixUnitaire($data['prix_unitaire'])
                ->setUnite($data['unite']);
        return $article;
    }

    public static function findAll(PDO $db): array
    {
        $sql = "SELECT * FROM type_articles ORDER BY categorie, nom";
        $stmt = $db->query($sql);
        $results = [];
        while ($data = $stmt->fetch()) {
            $article = new TypeArticle();
            $article->setId($data['id'])
                    ->setNom($data['nom'])
                    ->setCategorie($data['categorie'])
                    ->setPrixUnitaire($data['prix_unitaire'])
                    ->setUnite($data['unite']);
            $results[] = $article;
        }
        return $results;
    }

    public static function findAllArray(PDO $db): array
    {
        $sql = "SELECT * FROM type_articles ORDER BY categorie, nom";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(PDO $db): bool
    {
        $sql = "UPDATE type_articles SET nom = :nom, categorie = :categorie, prix_unitaire = :prix_unitaire, unite = :unite WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':nom' => $this->nom,
            ':categorie' => $this->categorie,
            ':prix_unitaire' => $this->prix_unitaire,
            ':unite' => $this->unite,
            ':id' => $this->id
        ]);
    }

    public function delete(PDO $db): bool
    {
        $sql = "DELETE FROM type_articles WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }
}
