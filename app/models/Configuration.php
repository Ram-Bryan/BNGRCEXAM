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

    public function getValeur(): ?string
    {
        return $this->valeur;
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
     * Raccourci pour obtenir la valeur avec fallback et typage simple
     * Si $type = 'int' ou 'float' effectue un cast
     */
    public static function getValue(PDO $db, string $nom, $default = null, string $type = 'string')
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
}
