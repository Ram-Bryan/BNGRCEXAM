<?php

namespace controllers;

use models\Configuration;
use Flight;
use PDO;

class HomeController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    /**
     * Rendre la page d'accueil avec la configuration nécessaire
     */
    public function index()
    {
        $fraisPercent = Configuration::getValue($this->db, 'FRAIS_ACHAT_PERCENT', 10, 'int');

        Flight::render('index', [
            'fraisPercent' => $fraisPercent
        ]);
    }
}
