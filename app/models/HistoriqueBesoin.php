<?php

namespace models;

use PDO;

class HistoriqueBesoin
{
    private ?int $id = null;
    private ?int $besoin_id = null;
    private ?float $quantite = null;
    private ?string $date_enregistrement = null;

    public function __construct()
    {
    }

    // ==================== GETTERS ====================
    
    public function getId(): ?int { return $this->id; }
    public function getBesoinId(): ?int { return $this->besoin_id; }
    public function getQuantite(): ?float { return $this->quantite; }
    public function getDateEnregistrement(): ?string { return $this->date_enregistrement; }

    // ==================== SETTERS ====================
    
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setBesoinId(?int $besoin_id): self { $this->besoin_id = $besoin_id; return $this; }
    public function setQuantite(?float $quantite): self { $this->quantite = $quantite; return $this; }
    public function setDateEnregistrement(?string $date_enregistrement): self { $this->date_enregistrement = $date_enregistrement; return $this; }

    // ==================== MÉTHODES DE BASE DE DONNÉES ====================

    public function create(PDO $db): bool
    {
        $sql = "INSERT INTO historique_besoin (besoin_id, quantite) VALUES (:besoin_id, :quantite)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':besoin_id' => $this->besoin_id,
            ':quantite' => $this->quantite
        ]);
        if ($result) {
            $this->id = $db->lastInsertId();
        }
        return $result;
    }

    public static function findByBesoinId(PDO $db, int $besoin_id): array
    {
        $sql = "SELECT * FROM vue_historique_besoins WHERE besoin_id = :besoin_id ORDER BY date_enregistrement DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':besoin_id' => $besoin_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findAllComplete(PDO $db): array
    {
        $sql = "SELECT * FROM vue_historique_besoins ORDER BY date_enregistrement DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findAll(PDO $db): array
    {
        $sql = "SELECT * FROM historique_besoin ORDER BY date_enregistrement DESC";
        $stmt = $db->query($sql);
        $results = [];
        while ($data = $stmt->fetch()) {
            $historique = new HistoriqueBesoin();
            $historique->setId($data['id'])
                      ->setBesoinId($data['besoin_id'])
                      ->setQuantite($data['quantite'])
                      ->setDateEnregistrement($data['date_enregistrement']);
            $results[] = $historique;
        }
        return $results;
    }
}
