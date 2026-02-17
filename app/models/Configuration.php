<?php

namespace models;

use PDO;

class Configuration
{
    private ?int $id = null;
    private string $nom;
    private ?string $valeur;

    public function __construct(string $nom = '', ?string $valeur = null)
    {
        $this->nom = $nom;
        $this->valeur = $valeur;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function setValeur(?string $valeur): self
    {
        $this->valeur = $valeur;
        return $this;
    }

    public static function findByNom(PDO $db, string $nom): ?Configuration
    {
        $sql = "SELECT * FROM bngrc_configuration WHERE nom = :nom LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':nom' => $nom]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;
        $cfg = new Configuration($data['nom'], $data['valeur']);
        $cfg->id = (int)$data['id'];
        return $cfg;
    }

    /**
     * Raccourci pour obtenir la valeur d'une configuration
     * Retourne null si la configuration n'existe pas
     */
    public static function getValue(PDO $db, string $nom): ?string
    {
        $cfg = self::findByNom($db, $nom);
        if (!$cfg) return null;
        return $cfg->getValeur();
    }

    /**
     * Obtenir une valeur avec cast de type
     */
    public static function getValueAs(PDO $db, string $nom, string $type = 'string', $default = null)
    {
        $cfg = self::findByNom($db, $nom);
        if (!$cfg) return $default;
        $val = $cfg->getValeur();
        if ($val === null) return $default;
        switch ($type) {
            case 'int':
                return (int)$val;
            case 'float':
                return (float)$val;
            case 'bool':
                return in_array(strtolower($val), ['1', 'true', 'yes'], true);
            default:
                return $val;
        }
    }

    /**
     * Récupérer toutes les configurations
     */
    public static function findAll(PDO $db): array
    {
        $sql = "SELECT * FROM bngrc_configuration ORDER BY nom ASC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Créer une nouvelle configuration
     */
    public function create(PDO $db): bool
    {
        $sql = "INSERT INTO bngrc_configuration (nom, valeur) VALUES (:nom, :valeur)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            ':nom' => $this->nom,
            ':valeur' => $this->valeur
        ]);
        if ($result) {
            $this->id = (int)$db->lastInsertId();
        }
        return $result;
    }

    /**
     * Mettre à jour une configuration existante
     */
    public function update(PDO $db): bool
    {
        $sql = "UPDATE bngrc_configuration SET nom = :nom, valeur = :valeur WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id' => $this->id,
            ':nom' => $this->nom,
            ':valeur' => $this->valeur
        ]);
    }

    /**
     * Supprimer une configuration
     */
    public function delete(PDO $db): bool
    {
        $sql = "DELETE FROM bngrc_configuration WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }

    /**
     * Trouver par ID
     */
    public static function findById(PDO $db, int $id): ?Configuration
    {
        $sql = "SELECT * FROM bngrc_configuration WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;
        $cfg = new Configuration($data['nom'], $data['valeur']);
        $cfg->id = (int)$data['id'];
        return $cfg;
    }
}
