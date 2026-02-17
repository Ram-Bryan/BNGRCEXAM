<?php

namespace controllers;

use models\Configuration;
use Flight;
use PDO;

class ConfigurationController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    /**
     * Afficher la liste des configurations
     */
    public function list()
    {
        $configurations = Configuration::findAll($this->db);

        Flight::render('configuration/list', [
            'configurations' => $configurations
        ]);
    }

    /**
     * Créer une nouvelle configuration
     */
    public function create()
    {
        $nom = Flight::request()->data->nom;
        $valeur = Flight::request()->data->valeur;

        try {
            // Vérifier si le nom existe déjà
            $existing = Configuration::findByNom($this->db, $nom);
            if ($existing) {
                Flight::redirect('/configurations?error=' . urlencode('Cette configuration existe déjà'));
                return;
            }

            $config = new Configuration($nom, $valeur);
            if ($config->create($this->db)) {
                Flight::redirect('/configurations?success=created');
            } else {
                Flight::redirect('/configurations?error=creation_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/configurations?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Mettre à jour une configuration
     */
    public function update()
    {
        $id = Flight::request()->data->id;
        $nom = Flight::request()->data->nom;
        $valeur = Flight::request()->data->valeur;

        try {
            $config = Configuration::findById($this->db, (int)$id);
            if (!$config) {
                Flight::redirect('/configurations?error=not_found');
                return;
            }

            $config->setNom($nom)
                   ->setValeur($valeur);

            if ($config->update($this->db)) {
                Flight::redirect('/configurations?success=updated');
            } else {
                Flight::redirect('/configurations?error=update_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/configurations?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Supprimer une configuration
     */
    public function delete($id)
    {
        try {
            $config = Configuration::findById($this->db, (int)$id);
            if (!$config) {
                Flight::redirect('/configurations?error=not_found');
                return;
            }

            if ($config->delete($this->db)) {
                Flight::redirect('/configurations?success=deleted');
            } else {
                Flight::redirect('/configurations?error=delete_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/configurations?error=' . urlencode($e->getMessage()));
        }
    }
}
