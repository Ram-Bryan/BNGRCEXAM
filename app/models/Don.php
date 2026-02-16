<?php

namespace models;

use PDO;

class Don
{
    private ?int $id = null;
    private ?int $idbesoins = null;
    private ?int $quantite = null;
    private ?string $date_livraison = null;

    public function __construct()
    {
    }

    // ==================== GETTERS ====================
    
    public function getId(): ?int { return $this->id; }
    public function getIdbesoins(): ?int { return $this->idbesoins; }
    public function getQuantite(): ?int { return $this->quantite; }
    public function getDateLivraison(): ?string { return $this->date_livraison; }

    // ==================== SETTERS ====================
    
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setIdbesoins(?int $idbesoins): self { $this->idbesoins = $idbesoins; return $this; }
    public function setQuantite(?int $quantite): self { $this->quantite = $quantite; return $this; }
    public function setDateLivraison(?string $date_livraison): self { $this->date_livraison = $date_livraison; return $this; }

    // ==================== MÉTHODES DE BASE DE DONNÉES ====================

    public function create(PDO $db): bool
    {
        $sql = "INSERT INTO dons (idbesoins, quantite, date_livraison) 
                VALUES (:idbesoins, :quantite, :date_livraison)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':idbesoins' => $this->idbesoins,
            ':quantite' => $this->quantite,
            ':date_livraison' => $this->date_livraison
        ]);
        if ($result) {
            $this->id = $db->lastInsertId();
        }
        return $result;
    }

    public static function findById(PDO $db, int $id): ?Don
    {
        $sql = "SELECT * FROM dons WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        if (!$data) return null;
        
        $don = new Don();
        $don->setId($data['id'])
            ->setIdbesoins($data['idbesoins'])
            ->setQuantite($data['quantite'])
            ->setDateLivraison($data['date_livraison']);
        return $don;
    }

    public static function findAll(PDO $db): array
    {
        $sql = "SELECT * FROM dons ORDER BY date_livraison DESC";
        $stmt = $db->query($sql);
        $results = [];
        while ($data = $stmt->fetch()) {
            $don = new Don();
            $don->setId($data['id'])
                ->setIdbesoins($data['idbesoins'])
                ->setQuantite($data['quantite'])
                ->setDateLivraison($data['date_livraison']);
            $results[] = $don;
        }
        return $results;
    }

    /**
     * Récupérer tous les dons avec détails complets via la vue SQL
     */
    public static function findAllComplete(PDO $db): array
    {
        $sql = "SELECT * FROM vue_dons_complets ORDER BY date_livraison DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les dons d'un besoin spécifique
     */
    public static function findByBesoinId(PDO $db, int $besoin_id): array
    {
        $sql = "SELECT * FROM vue_dons_complets WHERE besoin_id = :besoin_id ORDER BY date_livraison DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':besoin_id' => $besoin_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer le total des dons pour un besoin donné
     */
    public static function getTotalDonsByBesoin(PDO $db, int $besoin_id): int
    {
        $sql = "SELECT COALESCE(SUM(quantite), 0) AS total FROM dons WHERE idbesoins = :besoin_id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':besoin_id' => $besoin_id]);
        return (int) $stmt->fetchColumn();
    }

    public function update(PDO $db): bool
    {
        $sql = "UPDATE dons SET idbesoins = :idbesoins, quantite = :quantite, date_livraison = :date_livraison WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idbesoins' => $this->idbesoins,
            ':quantite' => $this->quantite,
            ':date_livraison' => $this->date_livraison,
            ':id' => $this->id
        ]);
    }

    public function delete(PDO $db): bool
    {
        $sql = "DELETE FROM dons WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }
}
