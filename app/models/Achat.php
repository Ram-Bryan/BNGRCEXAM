<?php

namespace models;

use PDO;

class Achat
{
    private ?int $id = null;
    private ?int $besoin_id = null;
    private ?int $quantite = null;
    private ?float $montant_ht = null;
    private ?float $frais_percent = null;
    private ?float $montant_frais = null;
    private ?float $montant_total = null;
    private ?string $date_achat = null;
    private ?bool $valide = false;
    private ?string $date_validation = null;

    public function __construct() {}

    // ==================== GETTERS ====================

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getBesoinId(): ?int
    {
        return $this->besoin_id;
    }
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }
    public function getMontantHt(): ?float
    {
        return $this->montant_ht;
    }
    public function getFraisPercent(): ?float
    {
        return $this->frais_percent;
    }
    public function getMontantFrais(): ?float
    {
        return $this->montant_frais;
    }
    public function getMontantTotal(): ?float
    {
        return $this->montant_total;
    }
    public function getDateAchat(): ?string
    {
        return $this->date_achat;
    }
    public function isValide(): ?bool
    {
        return $this->valide;
    }
    public function getDateValidation(): ?string
    {
        return $this->date_validation;
    }

    // ==================== SETTERS ====================

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function setBesoinId(?int $besoin_id): self
    {
        $this->besoin_id = $besoin_id;
        return $this;
    }
    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }
    public function setMontantHt(?float $montant_ht): self
    {
        $this->montant_ht = $montant_ht;
        return $this;
    }
    public function setFraisPercent(?float $frais_percent): self
    {
        $this->frais_percent = $frais_percent;
        return $this;
    }
    public function setMontantFrais(?float $montant_frais): self
    {
        $this->montant_frais = $montant_frais;
        return $this;
    }
    public function setMontantTotal(?float $montant_total): self
    {
        $this->montant_total = $montant_total;
        return $this;
    }
    public function setDateAchat(?string $date_achat): self
    {
        $this->date_achat = $date_achat;
        return $this;
    }
    public function setValide(?bool $valide): self
    {
        $this->valide = $valide;
        return $this;
    }
    public function setDateValidation(?string $date_validation): self
    {
        $this->date_validation = $date_validation;
        return $this;
    }

    // ==================== MÉTHODES DE BASE DE DONNÉES ====================

    public function create(PDO $db): bool
    {
        $sql = "INSERT INTO bngrc_achat (besoin_id, quantite, montant_ht, frais_percent, montant_frais, montant_total, date_achat, valide) 
            VALUES (:besoin_id, :quantite, :montant_ht, :frais_percent, :montant_frais, :montant_total, :date_achat, :valide)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':besoin_id' => $this->besoin_id,
            ':quantite' => $this->quantite,
            ':montant_ht' => $this->montant_ht,
            ':frais_percent' => $this->frais_percent,
            ':montant_frais' => $this->montant_frais,
            ':montant_total' => $this->montant_total,
            ':date_achat' => $this->date_achat,
            ':valide' => $this->valide ? 1 : 0
        ]);
        if ($result) {
            $this->id = $db->lastInsertId();
        }
        return $result;
    }

    public static function findById(PDO $db, int $id): ?Achat
    {
        $sql = "SELECT * FROM bngrc_achat WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        if (!$data) return null;

        return self::fromArray($data);
    }

    public static function fromArray(array $data): Achat
    {
        $achat = new Achat();
        $achat->setId($data['id'] ?? null)
            ->setBesoinId($data['besoin_id'] ?? null)
            ->setQuantite($data['quantite'] ?? null)
            ->setMontantHt($data['montant_ht'] ?? null)
            ->setFraisPercent($data['frais_percent'] ?? null)
            ->setMontantFrais($data['montant_frais'] ?? null)
            ->setMontantTotal($data['montant_total'] ?? null)
            ->setDateAchat($data['date_achat'] ?? null)
            ->setValide((bool)($data['valide'] ?? false))
            ->setDateValidation($data['date_validation'] ?? null);
        return $achat;
    }

    public static function findAll(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_achats_complets ORDER BY date_achat DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findAllByVille(PDO $db, int $ville_id): array
    {
        $sql = "SELECT * FROM v_bngrc_achats_complets WHERE ville_id = :ville_id ORDER BY date_achat DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':ville_id' => $ville_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findNonValides(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_achats_complets WHERE valide = FALSE ORDER BY date_achat DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer l'argent disponible pour les achats
     */
    public static function getArgentDisponible(PDO $db): float
    {
        $sql = "SELECT argent_disponible FROM v_bngrc_argent_disponible";
        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) ($result['argent_disponible'] ?? 0);
    }

    /**
     * Valider un achat
     */
    public function valider(PDO $db): bool
    {
        $sql = "UPDATE bngrc_achat SET valide = TRUE, date_validation = NOW() WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }

    /**
     * Valider tous les achats non validés
     * Crée automatiquement un don BNGRC et une distribution pour chaque achat validé
     */
    public static function validerTous(PDO $db): bool
    {
        try {
            $db->beginTransaction();

            // Récupérer tous les achats non validés avec les infos du besoin
            $sqlAchats = "SELECT a.*, b.type_article_id, b.ville_id 
                          FROM bngrc_achat a
                          JOIN bngrc_besoin b ON a.besoin_id = b.id
                          WHERE a.valide = FALSE";
            $stmtAchats = $db->query($sqlAchats);
            $achats = $stmtAchats->fetchAll(PDO::FETCH_ASSOC);

            foreach ($achats as $achat) {
                // 1. Créer un don BNGRC pour cet achat
                $sqlDon = "INSERT INTO bngrc_dons (type_article_id, quantite, date_don) 
                           VALUES (:type_article_id, :quantite, :date_don)";
                $stmtDon = $db->prepare($sqlDon);
                $stmtDon->execute([
                    ':type_article_id' => $achat['type_article_id'],
                    ':quantite' => $achat['quantite'],
                    ':date_don' => $achat['date_achat']
                ]);
                $donId = $db->lastInsertId();

                // 2. Créer une distribution pour satisfaire le besoin
                $sqlDist = "INSERT INTO bngrc_distribution (don_id, besoin_id, quantite, date_distribution, est_simulation)
                            VALUES (:don_id, :besoin_id, :quantite, :date_distribution, FALSE)";
                $stmtDist = $db->prepare($sqlDist);
                $stmtDist->execute([
                    ':don_id' => $donId,
                    ':besoin_id' => $achat['besoin_id'],
                    ':quantite' => $achat['quantite'],
                    ':date_distribution' => date('Y-m-d')
                ]);
            }

            // 3. Marquer tous les achats comme validés
            $sqlValidate = "UPDATE bngrc_achat SET valide = TRUE, date_validation = NOW() WHERE valide = FALSE";
            $stmtValidate = $db->prepare($sqlValidate);
            $stmtValidate->execute();

            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Supprimer tous les achats non validés (annuler simulation)
     */
    public static function annulerSimulation(PDO $db): bool
    {
        $sql = "DELETE FROM bngrc_achat WHERE valide = FALSE";
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }

    public function delete(PDO $db): bool
    {
        $sql = "DELETE FROM bngrc_achat WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }

    /**
     * Vérifier si un achat non validé existe déjà pour ce besoin
     */
    public static function existeAchatNonValide(PDO $db, int $besoin_id): bool
    {
        $sql = "SELECT COUNT(*) FROM bngrc_achat WHERE besoin_id = :besoin_id AND valide = FALSE";
        $stmt = $db->prepare($sql);
        $stmt->execute([':besoin_id' => $besoin_id]);
        return (int)$stmt->fetchColumn() > 0;
    }
}
