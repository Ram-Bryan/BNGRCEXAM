<?php

namespace controllers;

use models\Don;
use models\TypeArticle;
use Flight;
use PDO;

class DonController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    /**
     * Afficher la liste des dons
     */
    public function listDons()
    {
        $donsData = Don::findAllComplete($this->db);

        Flight::render('don/list', [
            'dons' => $donsData
        ]);
    }

    /**
     * Afficher le formulaire d'ajout de don
     */
    public function showForm()
    {
        $typeArticles = TypeArticle::findAll($this->db);

        Flight::render('don/form', [
            'typeArticles' => $typeArticles
        ]);
    }

    /**
     * Créer un nouveau don
     */
    public function create()
    {
        $type_article_id = Flight::request()->data->type_article_id;
        $quantite = Flight::request()->data->quantite;
        $date_don = Flight::request()->data->date_don;
        $donateur = Flight::request()->data->donateur;

        try {
            $don = new Don();
            $don->setTypeArticleId((int)$type_article_id)
                ->setQuantite((int)$quantite)
                ->setDateDon($date_don)
                ->setDonateur($donateur ?: 'Anonyme')
                ->setStatut('disponible');

            if ($don->create($this->db)) {
                Flight::redirect('/dons?success=created');
            } else {
                Flight::redirect('/dons/ajout?error=creation_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/dons/ajout?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Supprimer un don
     */
    public function delete($id)
    {
        try {
            $don = Don::findById($this->db, $id);
            if (!$don) {
                Flight::redirect('/dons?error=not_found');
                return;
            }

            if ($don->delete($this->db)) {
                Flight::redirect('/dons?success=deleted');
            } else {
                Flight::redirect('/dons?error=delete_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/dons?error=' . urlencode($e->getMessage()));
        }
    }
}
