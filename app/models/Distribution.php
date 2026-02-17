<?php

namespace models;

use PDO;

class Distribution
{
    private ?int $id = null;
    private ?int $don_id = null;
    private ?int $besoin_id = null;
    private ?int $quantite = null;
    private ?string $date_distribution = null;
    private ?bool $est_simulation = true;

    public function __construct() {}

    // ==================== GETTERS ====================

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getDonId(): ?int
    {
        return $this->don_id;
    }
    public function getBesoinId(): ?int
    {
        return $this->besoin_id;
    }
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }
    public function getDateDistribution(): ?string
    {
        return $this->date_distribution;
    }
    public function isSimulation(): ?bool
    {
        return $this->est_simulation;
    }

    // ==================== SETTERS ====================

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function setDonId(?int $don_id): self
    {
        $this->don_id = $don_id;
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
    public function setDateDistribution(?string $date_distribution): self
    {
        $this->date_distribution = $date_distribution;
        return $this;
    }
    public function setEstSimulation(?bool $est_simulation): self
    {
        $this->est_simulation = $est_simulation;
        return $this;
    }

    // ==================== MÉTHODES DE BASE DE DONNÉES ====================

    public function create(PDO $db): bool
    {
        $sql = "INSERT INTO bngrc_distribution (don_id, besoin_id, quantite, date_distribution, est_simulation) 
            VALUES (:don_id, :besoin_id, :quantite, :date_distribution, :est_simulation)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':don_id' => $this->don_id,
            ':besoin_id' => $this->besoin_id,
            ':quantite' => $this->quantite,
            ':date_distribution' => $this->date_distribution ?? date('Y-m-d'),
            ':est_simulation' => $this->est_simulation ? 1 : 0
        ]);
        if ($result) {
            $this->id = $db->lastInsertId();
        }
        return $result;
    }

    public static function findById(PDO $db, int $id): ?Distribution
    {
        $sql = "SELECT * FROM bngrc_distribution WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        if (!$data) return null;

        return self::fromArray($data);
    }

    public static function fromArray(array $data): Distribution
    {
        $dist = new Distribution();
        $dist->setId($data['id'] ?? null)
            ->setDonId($data['don_id'] ?? null)
            ->setBesoinId($data['besoin_id'] ?? null)
            ->setQuantite($data['quantite'] ?? null)
            ->setDateDistribution($data['date_distribution'] ?? null)
            ->setEstSimulation((bool)($data['est_simulation'] ?? true));
        return $dist;
    }

    /**
     * Récupérer toutes les distributions (avec détails)
     */
    public static function findAllComplete(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_distributions ORDER BY date_distribution DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les distributions en simulation
     */
    public static function findSimulations(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_distributions WHERE est_simulation = TRUE ORDER BY date_distribution DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les distributions validées
     */
    public static function findValidees(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_distributions WHERE est_simulation = FALSE ORDER BY date_distribution DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprimer toutes les simulations (pour recommencer)
     */
    public static function supprimerSimulations(PDO $db): bool
    {
        $sql = "DELETE FROM bngrc_distribution WHERE est_simulation = TRUE";
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Valider toutes les simulations (les rendre définitives)
     */
    public static function validerSimulations(PDO $db): bool
    {
        $sql = "UPDATE bngrc_distribution SET est_simulation = FALSE WHERE est_simulation = TRUE";
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Créer les distributions automatiquement (simulation)
     * Dispatch par ordre de date de demande (besoins les plus anciens en premier)
     * Puis par ordre de date de don (dons les plus anciens en premier)
     */
    public static function simulerDistribution(PDO $db): array
    {
        // D'abord, supprimer les anciennes simulations
        self::supprimerSimulations($db);

        $resultats = [];

        // Récupérer les besoins non satisfaits, triés par date (les plus anciens en premier)
        $sqlBesoins = "SELECT b.*, ta.categorie, ta.prix_unitaire
                   FROM bngrc_besoin b
                   JOIN bngrc_type_articles ta ON b.type_article_id = ta.id
                   ORDER BY b.date_demande ASC, b.id ASC";
        $stmtBesoins = $db->query($sqlBesoins);
        $besoins = $stmtBesoins->fetchAll(PDO::FETCH_ASSOC);

        foreach ($besoins as $besoin) {
            // Quantité déjà reçue pour ce besoin (distributions validées)
            $sqlRecu = "SELECT COALESCE(SUM(quantite), 0) FROM bngrc_distribution 
                        WHERE besoin_id = :besoin_id AND est_simulation = FALSE";
            $stmtRecu = $db->prepare($sqlRecu);
            $stmtRecu->execute([':besoin_id' => $besoin['id']]);
            $quantiteRecue = (int)$stmtRecu->fetchColumn();

            $quantiteRestante = $besoin['quantite'] - $quantiteRecue;

            if ($quantiteRestante <= 0) {
                continue; // Besoin déjà satisfait
            }

            // Récupérer les dons disponibles du même type d'article
            $sqlDons = "SELECT d.*, 
                        d.quantite - COALESCE((
                            SELECT SUM(dist.quantite) FROM bngrc_distribution dist 
                            WHERE dist.don_id = d.id
                        ), 0) AS quantite_disponible
                        FROM bngrc_dons d
                        WHERE d.type_article_id = :type_article_id
                        HAVING quantite_disponible > 0
                        ORDER BY d.date_don ASC, d.id ASC";
            $stmtDons = $db->prepare($sqlDons);
            $stmtDons->execute([':type_article_id' => $besoin['type_article_id']]);
            $dons = $stmtDons->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dons as $don) {
                if ($quantiteRestante <= 0) break;

                $quantiteADistribuer = min($quantiteRestante, $don['quantite_disponible']);

                if ($quantiteADistribuer > 0) {
                    // Créer la distribution (simulation)
                    $dist = new Distribution();
                    $dist->setDonId($don['id'])
                        ->setBesoinId($besoin['id'])
                        ->setQuantite($quantiteADistribuer)
                        ->setDateDistribution(date('Y-m-d'))
                        ->setEstSimulation(true);
                    $dist->create($db);

                    $resultats[] = [
                        'don_id' => $don['id'],
                        'besoin_id' => $besoin['id'],
                        'quantite' => $quantiteADistribuer
                    ];

                    $quantiteRestante -= $quantiteADistribuer;
                }
            }
        }

        return $resultats;
    }

    /**
     * Récupérer un résumé de la simulation actuelle
     */
    public static function getResumeSimulation(PDO $db): array
    {
        $sql = "SELECT 
                    COUNT(*) AS nb_distributions,
                    SUM(dist.quantite) AS total_quantite,
                    SUM(dist.quantite * ta.prix_unitaire) AS total_montant
                FROM bngrc_distribution dist
                JOIN bngrc_dons d ON dist.don_id = d.id
                JOIN bngrc_type_articles ta ON d.type_article_id = ta.id
                WHERE dist.est_simulation = TRUE";
        $stmt = $db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete(PDO $db): bool
    {
        $sql = "DELETE FROM bngrc_distribution WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }
}
