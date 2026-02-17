<?php

namespace controllers;

use models\Configuration;
use Flight;
use PDO;

class ConfigurationController
{
    // Controller methods are static; use Flight::db() inside each method

    /**
     * Afficher la liste des configurations
     */
    public static function list(): void
    {
        $db = Flight::db();
        $configurations = Configuration::findAll($db);

        Flight::render('configuration/list', [
            'configurations' => $configurations
        ]);
    }

    /**
     * Créer une nouvelle configuration
     */
    public static function create(): void
    {
        $db = Flight::db();
        $nom = Flight::request()->data->nom;
        $valeur = Flight::request()->data->valeur;

        try {
            // Vérifier si le nom existe déjà
            $existing = Configuration::findByNom($db, $nom);
            if ($existing) {
                Flight::redirect('/configurations?error=' . urlencode('Cette configuration existe déjà'));
                return;
            }

            $config = new Configuration($nom, $valeur);
            if ($config->create($db)) {
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
    public static function update(): void
    {
        $db = Flight::db();
        $id = Flight::request()->data->id;
        $nom = Flight::request()->data->nom;
        $valeur = Flight::request()->data->valeur;

        try {
            $config = Configuration::findById($db, (int)$id);
            if (!$config) {
                Flight::redirect('/configurations?error=not_found');
                return;
            }

            $config->setNom($nom)
                   ->setValeur($valeur);

            if ($config->update($db)) {
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
    public static function delete($id): void
    {
        $db = Flight::db();
        try {
            $config = Configuration::findById($db, (int)$id);
            if (!$config) {
                Flight::redirect('/configurations?error=not_found');
                return;
            }

            if ($config->delete($db)) {
                Flight::redirect('/configurations?success=deleted');
            } else {
                Flight::redirect('/configurations?error=delete_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/configurations?error=' . urlencode($e->getMessage()));
        }
    }
}
