<?php

namespace models;

use PDO;

class Ville
{
    private ?int $id = null;
    private ?string $nom = null;
    private ?int $idregion = null;
    private ?int $nbsinistres = null;

    public function __construct()
    {
    }

    // ==================== GETTERS ====================
    
    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function getIdregion(): ?int { return $this->idregion; }
    public function getNbsinistres(): ?int { return $this->nbsinistres; }

    // ==================== SETTERS ====================
    
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setNom(?string $nom): self { $this->nom = $nom; return $this; }
    public function setIdregion(?int $idregion): self { $this->idregion = $idregion; return $this; }
    public function setNbsinistres(?int $nbsinistres): self { $this->nbsinistres = $nbsinistres; return $this; }

    // ==================== MÉTHODES DE BASE DE DONNÉES ====================

    public function create(PDO $db): bool
    {
        $sql = "INSERT INTO bngrc_ville (nom, idregion, nbsinistres) VALUES (:nom, :idregion, :nbsinistres)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':nom' => $this->nom,
            ':idregion' => $this->idregion,
            ':nbsinistres' => $this->nbsinistres ?? 0
        ]);
        if ($result) {
            $this->id = $db->lastInsertId();
        }
        return $result;
    }

    public static function findById(PDO $db, int $id): ?Ville
    {
        $sql = "SELECT * FROM bngrc_ville WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        if (!$data) return null;
        
        $ville = new Ville();
        $ville->setId($data['id'])
              ->setNom($data['nom'])
              ->setIdregion($data['idregion'])
              ->setNbsinistres($data['nbsinistres']);
        return $ville;
    }

    public static function findAllComplete(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_villes_completes ORDER BY region_nom, ville_nom";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findAll(PDO $db): array
    {
        $sql = "SELECT * FROM bngrc_ville ORDER BY nom";
        $stmt = $db->query($sql);
        $results = [];
        while ($data = $stmt->fetch()) {
            $ville = new Ville();
            $ville->setId($data['id'])
                  ->setNom($data['nom'])
                  ->setIdregion($data['idregion'])
                  ->setNbsinistres($data['nbsinistres']);
            $results[] = $ville;
        }
        return $results;
    }

    public function update(PDO $db): bool
    {
        $sql = "UPDATE bngrc_ville SET nom = :nom, idregion = :idregion, nbsinistres = :nbsinistres WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':nom' => $this->nom,
            ':idregion' => $this->idregion,
            ':nbsinistres' => $this->nbsinistres,
            ':id' => $this->id
        ]);
    }

    public function delete(PDO $db): bool
    {
        $sql = "DELETE FROM bngrc_ville WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }
}
