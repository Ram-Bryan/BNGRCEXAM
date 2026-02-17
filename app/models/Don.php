<?php

namespace models;

use PDO;
use dto\DTODon;

class Don
{
    private ?int $id = null;
    private ?int $type_article_id = null;
    private ?int $quantite = null;
    private ?string $date_don = null;
    private ?string $donateur = null;
    private ?string $statut = 'disponible';

    public function __construct() {}

    // ==================== GETTERS ====================

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTypeArticleId(): ?int
    {
        return $this->type_article_id;
    }
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }
    public function getDateDon(): ?string
    {
        return $this->date_don;
    }
    public function getDonateur(): ?string
    {
        return $this->donateur;
    }
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    // ==================== SETTERS ====================

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function setTypeArticleId(?int $type_article_id): self
    {
        $this->type_article_id = $type_article_id;
        return $this;
    }
    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }
    public function setDateDon(?string $date_don): self
    {
        $this->date_don = $date_don;
        return $this;
    }
    public function setDonateur(?string $donateur): self
    {
        $this->donateur = $donateur;
        return $this;
    }
    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    // ==================== MÉTHODES DE BASE DE DONNÉES ====================

    public function create(PDO $db): bool
    {
        $sql = "INSERT INTO bngrc_dons (type_article_id, quantite, date_don, donateur, statut) 
            VALUES (:type_article_id, :quantite, :date_don, :donateur, :statut)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':type_article_id' => $this->type_article_id,
            ':quantite' => $this->quantite,
            ':date_don' => $this->date_don,
            ':donateur' => $this->donateur ?? 'Anonyme',
            ':statut' => $this->statut ?? 'disponible'
        ]);
        if ($result) {
            $this->id = $db->lastInsertId();
        }
        return $result;
    }

    public static function findById(PDO $db, int $id): ?Don
    {
        $sql = "SELECT * FROM bngrc_dons WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        if (!$data) return null;

        $don = new Don();
        $don->setId($data['id'])
            ->setTypeArticleId($data['type_article_id'])
            ->setQuantite($data['quantite'])
            ->setDateDon($data['date_don'])
            ->setDonateur($data['donateur'])
            ->setStatut($data['statut']);
        return $don;
    }

    public static function findAll(PDO $db): array
    {
        $sql = "SELECT * FROM bngrc_dons ORDER BY date_don DESC";
        $stmt = $db->query($sql);
        $results = [];
        while ($data = $stmt->fetch()) {
            $don = new Don();
            $don->setId($data['id'])
                ->setTypeArticleId($data['type_article_id'])
                ->setQuantite($data['quantite'])
                ->setDateDon($data['date_don'])
                ->setDonateur($data['donateur'])
                ->setStatut($data['statut']);
            $results[] = $don;
        }
        return $results;
    }

    public static function findAllComplete(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_dons_complets ORDER BY date_don DESC";
        $stmt = $db->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convertir en DTODon
        return DTODon::fromArrayMultiple($data);
    }

 
    /**
     * Récupérer tous les dons disponibles
     */
    public static function findAllDisponibles(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_dons_complets WHERE quantite_disponible > 0 ORDER BY date_don ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function update(PDO $db): bool
    {
        $sql = "UPDATE bngrc_dons SET type_article_id = :type_article_id, quantite = :quantite, 
            date_don = :date_don, donateur = :donateur, statut = :statut WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':type_article_id' => $this->type_article_id,
            ':quantite' => $this->quantite,
            ':date_don' => $this->date_don,
            ':donateur' => $this->donateur,
            ':statut' => $this->statut,
            ':id' => $this->id
        ]);
    }

    public function delete(PDO $db): bool
    {
        $sql = "DELETE FROM bngrc_dons WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }
}
