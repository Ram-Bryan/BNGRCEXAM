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

    public static function simulerDistribution(PDO $db, string $logic = 'ancien'): array
    {
        self::supprimerSimulations($db);

        $besoins = self::recupererBesoins($db);

        if ($logic === 'proportionnel') {
            return self::simulerProportionnel($db, $besoins);
        }

        return self::simulerAncien($db, $besoins);
    }

    private static function recupererBesoins(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_besoins_avec_articles WHERE categorie != 'argent'";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function simulerProportionnel(PDO $db, array $besoins): array
    {
        $resultats = [];
        $types = [];

        foreach ($besoins as $besoin) {
            $types[$besoin['type_article_id']][] = $besoin;
        }

        foreach ($types as $type_article_id => $besoinsType) {

            $sqlDons = "SELECT * FROM v_bngrc_dons_disponibles_par_type 
                    WHERE type_article_id = :type_article_id 
                    ORDER BY date_don ASC, id ASC";
            $stmtDons = $db->prepare($sqlDons);
            $stmtDons->execute([':type_article_id' => $type_article_id]);
            $dons = $stmtDons->fetchAll(PDO::FETCH_ASSOC);

            $besoinsRestants = [];
            $totalRestant = 0;

            foreach ($besoinsType as $besoin) {
                $sqlRecu = "SELECT COALESCE(SUM(quantite),0) 
                        FROM bngrc_distribution 
                        WHERE besoin_id = :besoin_id 
                        AND est_simulation = FALSE";
                $stmtRecu = $db->prepare($sqlRecu);
                $stmtRecu->execute([':besoin_id' => $besoin['id']]);

                $reste = $besoin['quantite'] - (int)$stmtRecu->fetchColumn();

                if ($reste > 0) {
                    $besoinsRestants[$besoin['id']] = $reste;
                    $totalRestant += $reste;
                }
            }

            if ($totalRestant == 0) continue;

            foreach ($dons as $don) {

                $qDispo = $don['quantite_disponible'];
                $resteARepartir = $qDispo;
                $affectations = [];

                foreach ($besoinsRestants as $besoinId => $qBesoin) {
                    $qAffecte = floor($qDispo * ($qBesoin / $totalRestant));
                    $qAffecte = min($qAffecte, $besoinsRestants[$besoinId]);
                    $affectations[$besoinId] = $qAffecte;
                    $resteARepartir -= $qAffecte;
                }

                if ($resteARepartir > 0) {
                    arsort($besoinsRestants);
                    foreach (array_keys($besoinsRestants) as $besoinId) {
                        if ($resteARepartir <= 0) break;
                        if ($affectations[$besoinId] < $besoinsRestants[$besoinId]) {
                            $affectations[$besoinId]++;
                            $resteARepartir--;
                        }
                    }
                }

                foreach ($affectations as $besoinId => $qAffecte) {
                    if ($qAffecte <= 0) continue;

                    $dist = new Distribution();
                    $dist->setDonId($don['id'])
                        ->setBesoinId($besoinId)
                        ->setQuantite($qAffecte)
                        ->setDateDistribution(date('Y-m-d'))
                        ->setEstSimulation(true)
                        ->create($db);

                    $resultats[] = [
                        'don_id' => $don['id'],
                        'besoin_id' => $besoinId,
                        'quantite' => $qAffecte
                    ];

                    $besoinsRestants[$besoinId] -= $qAffecte;
                }
            }
        }

        return $resultats;
    }

    private static function simulerAncien(PDO $db, array $besoins): array
    {
        $resultats = [];

        usort($besoins, function ($a, $b) {
            return strtotime($a['date_demande']) <=> strtotime($b['date_demande'])
                ?: ($a['id'] <=> $b['id']);
        });

        foreach ($besoins as $besoin) {

            $sqlRecu = "SELECT COALESCE(SUM(quantite),0) 
                    FROM bngrc_distribution 
                    WHERE besoin_id = :besoin_id 
                    AND est_simulation = FALSE";
            $stmtRecu = $db->prepare($sqlRecu);
            $stmtRecu->execute([':besoin_id' => $besoin['id']]);

            $reste = $besoin['quantite'] - (int)$stmtRecu->fetchColumn();
            if ($reste <= 0) continue;

            $sqlDons = "SELECT * FROM v_bngrc_dons_disponibles_par_type 
                    WHERE type_article_id = :type_article_id 
                    ORDER BY date_don ASC, id ASC";
            $stmtDons = $db->prepare($sqlDons);
            $stmtDons->execute([':type_article_id' => $besoin['type_article_id']]);
            $dons = $stmtDons->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dons as $don) {
                if ($reste <= 0) break;

                $q = min($reste, $don['quantite_disponible']);
                if ($q <= 0) continue;

                $dist = new Distribution();
                $dist->setDonId($don['id'])
                    ->setBesoinId($besoin['id'])
                    ->setQuantite($q)
                    ->setDateDistribution(date('Y-m-d'))
                    ->setEstSimulation(true)
                    ->create($db);

                $resultats[] = [
                    'don_id' => $don['id'],
                    'besoin_id' => $besoin['id'],
                    'quantite' => $q
                ];

                $reste -= $q;
            }
        }

        return $resultats;
    }


    /**
     * Récupérer un résumé de la simulation actuelle
     */
    public static function getResumeSimulation(PDO $db): array
    {
        $sql = "SELECT * FROM v_bngrc_resume_simulation";
        $stmt = $db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
            'nb_distributions' => 0,
            'total_quantite' => 0,
            'total_montant' => 0
        ];
    }

    public function delete(PDO $db): bool
    {
        $sql = "DELETE FROM bngrc_distribution WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }
}
