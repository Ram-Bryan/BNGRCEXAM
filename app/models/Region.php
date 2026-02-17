<?php

namespace models;

use PDO;

class Region
{
    private ?int $id = null;
    private ?string $nom = null;

    public function __construct()
    {
    }

    // ==================== GETTERS ====================
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    // ==================== SETTERS ====================
    
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    // ==================== MÉTHODES DE BASE DE DONNÉES ====================

    public function create(PDO $db): bool
    {
        $sql = "INSERT INTO bngrc_region (nom) VALUES (:nom)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([':nom' => $this->nom]);
        if ($result) {
            $this->id = $db->lastInsertId();
        }
        return $result;
    }

    public static function findById(PDO $db, int $id): ?Region
    {
        $sql = "SELECT * FROM bngrc_region WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        if (!$data) return null;
        
        $region = new Region();
        $region->setId($data['id'])->setNom($data['nom']);
        return $region;
    }

    public static function findAll(PDO $db): array
    {
        $sql = "SELECT * FROM bngrc_region ORDER BY nom";
        $stmt = $db->query($sql);
        $results = [];
        while ($data = $stmt->fetch()) {
            $region = new Region();
            $region->setId($data['id'])->setNom($data['nom']);
            $results[] = $region;
        }
        return $results;
    }

    public function update(PDO $db): bool
    {
        $sql = "UPDATE bngrc_region SET nom = :nom WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':nom' => $this->nom, ':id' => $this->id]);
    }

    public function delete(PDO $db): bool
    {
        $sql = "DELETE FROM bngrc_region WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }
}
