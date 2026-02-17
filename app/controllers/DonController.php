<?php

namespace controllers;

use models\Don;
use models\TypeArticle;
use Flight;
use PDO;

class DonController
{
    /**
     * Afficher la liste des dons
     */
    public static function listDons($message = null): void
    {
        $db = Flight::db();
        $dons = Don::findAllComplete($db);

        // Calculer les statistiques côté backend
        $totalDons = count($dons);
        $totalDisponible = 0;
        $totalDistribue = 0;
        
        foreach ($dons as $don) {
            $totalDisponible += $don->getQuantiteDisponible() ?? 0;
            $totalDistribue += $don->getQuantiteDistribuee() ?? 0;
        }

        Flight::render('don/list', [
            'dons' => $dons,
            'message' => $message,
            'totalDons' => $totalDons,
            'totalDisponible' => $totalDisponible,
            'totalDistribue' => $totalDistribue
        ]);
    }

    /**
     * Afficher le formulaire d'ajout de don
     */
    public static function showForm($message = null): void
    {
        $db = Flight::db();
        $typeArticles = TypeArticle::findAll($db);

        Flight::render('don/form', [
            'typeArticles' => $typeArticles,
            'message' => $message
        ]);
    }

    /**
     * Créer un nouveau don
     */
    public static function create(): void
    {
        $db = Flight::db();
        $request = Flight::request();
        
        $type_article_id = $request->data->type_article_id;
        $quantite = $request->data->quantite;
        $date_don = $request->data->date_don;
        $donateur = $request->data->donateur;

        try {
            $don = new Don();
            $don->setTypeArticleId((int)$type_article_id)
                ->setQuantite((int)$quantite)
                ->setDateDon($date_don)
                ->setDonateur($donateur ?: 'Anonyme')
                ->setStatut('disponible');

            if ($don->create($db)) {
                self::listDons('Don enregistré avec succès !');
            } else {
                self::showForm('Erreur lors de la création du don');
            }
        } catch (\Exception $e) {
            self::showForm('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un don
     */
    public static function delete($id): void
    {
        $db = Flight::db();
        
        try {
            $don = Don::findById($db, $id);
            if (!$don) {
                self::listDons('Don non trouvé');
                return;
            }

            if ($don->delete($db)) {
                self::listDons('Don supprimé avec succès !');
            } else {
                self::listDons('Erreur lors de la suppression');
            }
        } catch (\Exception $e) {
            self::listDons('Erreur : ' . $e->getMessage());
        }
    }
}
