<?php

namespace models;

use PDO;

class Besoin
{
    private ?int $id = null;
    private ?int $ville_id = null;
    private ?int $type_article_id = null;
    private ?float $quantite = null;
    private ?string $date_demande = null;

    public function __construct()
    {
    }

    // ==================== GETTERS ====================
    
    public function getId(): ?int { return $this->id; }
    public function getVilleId(): ?int { return $this->ville_id; }
    public function getTypeArticleId(): ?int { return $this->type_article_id; }
    public function getQuantite(): ?float { return $this->quantite; }
    public function getDateDemande(): ?string { return $this->date_demande; }

    // ==================== SETTERS ====================
    
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setVilleId(?int $ville_id): self { $this->ville_id = $ville_id; return $this; }
    public function setTypeArticleId(?int $type_article_id): self { $this->type_article_id = $type_article_id; return $this; }
    public function setQuantite(?float $quantite): self { $this->quantite = $quantite; return $this; }
    public function setDateDemande(?string $date_demande): self { $this->date_demande = $date_demande; return $this; }

    // ==================== MÉTHODES DE BASE DE DONNÉES ====================

    public function create(PDO $db): bool
    {
        $sql = "INSERT INTO besoin (ville_id, type_article_id, quantite, date_demande) 
                VALUES (:ville_id, :type_article_id, :quantite, :date_demande)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':ville_id' => $this->ville_id,
            ':type_article_id' => $this->type_article_id,
            ':quantite' => $this->quantite,
            ':date_demande' => $this->date_demande
        ]);
        if ($result) {
            $this->id = $db->lastInsertId();
        }
        return $result;
    }

    public static function findById(PDO $db, int $id): ?Besoin
    {
        $sql = "SELECT * FROM besoin WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        if (!$data) return null;
        
        $besoin = new Besoin();
        $besoin->setId($data['id'])
               ->setVilleId($data['ville_id'])
               ->setTypeArticleId($data['type_article_id'])
               ->setQuantite($data['quantite'])
               ->setDateDemande($data['date_demande']);
        return $besoin;
    }

    public static function findAll(PDO $db): array
    {
        $sql = "SELECT * FROM besoin ORDER BY date_demande DESC";
        $stmt = $db->query($sql);
        $results = [];
        while ($data = $stmt->fetch()) {
            $besoin = new Besoin();
            $besoin->setId($data['id'])
                   ->setVilleId($data['ville_id'])
                   ->setTypeArticleId($data['type_article_id'])
                   ->setQuantite($data['quantite'])
                   ->setDateDemande($data['date_demande']);
            $results[] = $besoin;
        }
        return $results;
    }

    public static function findAllComplete(PDO $db): array
    {
        $sql = "SELECT * FROM vue_besoins_complets ORDER BY date_demande DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findCompleteById(PDO $db, int $id): ?array
    {
        $sql = "SELECT * FROM vue_besoins_complets WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public static function findBesoinsNonSatisfaits(PDO $db): array
    {
        $sql = "SELECT * FROM vue_besoins_satisfaction 
                WHERE quantite_restante > 0 
                ORDER BY date_demande ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findBesoinsSatisfactionByVille(PDO $db, int $ville_id): array
    {
        $sql = "SELECT * FROM vue_besoins_satisfaction 
                WHERE ville_id = :ville_id 
                ORDER BY date_demande ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':ville_id' => $ville_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(PDO $db): bool
    {
        $updates = [];
        $params = [':id' => $this->id];
        
        if ($this->ville_id !== null) {
            $updates[] = "ville_id = :ville_id";
            $params[':ville_id'] = $this->ville_id;
        }
        
        if ($this->type_article_id !== null) {
            $updates[] = "type_article_id = :type_article_id";
            $params[':type_article_id'] = $this->type_article_id;
        }
        
        if ($this->quantite !== null) {
            $updates[] = "quantite = :quantite";
            $params[':quantite'] = $this->quantite;
        }
        
        if ($this->date_demande !== null) {
            $updates[] = "date_demande = :date_demande";
            $params[':date_demande'] = $this->date_demande;
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql = "UPDATE besoin SET " . implode(", ", $updates) . " WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateQuantite(PDO $db): bool
    {
        $sql = "UPDATE besoin SET quantite = :quantite WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':quantite' => $this->quantite,
            ':id' => $this->id
        ]);
    }

    public function delete(PDO $db): bool
    {
        $sql = "DELETE FROM besoin WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }

    public static function findBesoinsRestantsAchats(PDO $db, ?int $ville_id = null): array
    {
        $sql = "SELECT * FROM vue_besoins_satisfaction 
                WHERE quantite_restante > 0 AND categorie IN ('nature', 'material')";
        if ($ville_id) {
            $sql .= " AND ville_id = :ville_id";
        }
        $sql .= " ORDER BY date_demande ASC";

        $stmt = $db->prepare($sql);
        if ($ville_id) {
            $stmt->execute([':ville_id' => $ville_id]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findBesoinsAvecSimulation(PDO $db): array
    {
        $sql = "SELECT * FROM vue_besoins_satisfaction_avec_simulation 
                WHERE quantite_restante_avec_simulation > 0 
                ORDER BY date_demande ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
