<?php

namespace controllers;

use models\Besoin;
use models\HistoriqueBesoin;
use models\Ville;
use models\TypeArticle;
use dto\DTOBesoin;
use Flight;
use PDO;

class BesoinController
{
    /**
     * Afficher le formulaire de demande de besoin
     */
    public static function showForm($message = null): void
    {
        $db = Flight::db();
        $villes = Ville::findAllComplete($db);
        $typeArticles = TypeArticle::findAll($db);
        
        Flight::render('besoin/form', [
            'villes' => $villes,
            'typeArticles' => $typeArticles,
            'message' => $message
        ]);
    }

    /**
     * Créer un nouveau besoin
     */
    public static function create(): void
    {
        $db = Flight::db();
        $request = Flight::request();
        
        $ville_id = $request->data->ville_id;
        $type_article_id = $request->data->type_article_id;
        $quantite = $request->data->quantite;
        $date_demande = $request->data->date_demande;

        try {
            $besoin = new Besoin();
            $besoin->setVilleId((int)$ville_id)
                   ->setTypeArticleId((int)$type_article_id)
                   ->setQuantite((float)$quantite)
                   ->setDateDemande($date_demande);
            
            if ($besoin->create($db)) {
                $historique = new HistoriqueBesoin();
                $historique->setBesoinId($besoin->getId())
                          ->setQuantite((float)$quantite);
                $historique->create($db);

                // Render liste avec message (pas d'URL)
                $besoinsData = Besoin::findAllComplete($db);
                $besoins = DTOBesoin::fromArrayMultiple($besoinsData);
                Flight::render('besoin/list', [
                    'besoins' => $besoins,
                    'message' => 'Besoin créé avec succès'
                ]);
                return;
            } else {
                $villes = Ville::findAllComplete($db);
                $typeArticles = TypeArticle::findAll($db);

                Flight::render('besoin/form', [
                    'villes' => $villes,
                    'typeArticles' => $typeArticles,
                    'message' => 'Erreur lors de la création'
                ]);
                return;
            }
        } catch (\Exception $e) {
            $villes = Ville::findAllComplete($db);
            $typeArticles = TypeArticle::findAll($db);

            Flight::render('besoin/form', [
                'villes' => $villes,
                'typeArticles' => $typeArticles,
                'message' => 'Erreur : ' . $e->getMessage()
            ]);
            return;
        }
    }

    /**
     * Afficher la liste des besoins
     */
    public static function listBesoins($message = null): void
    {
        $db = Flight::db();
        $besoinsData = Besoin::findAllComplete($db);
        $besoins = DTOBesoin::fromArrayMultiple($besoinsData);
        
        Flight::render('besoin/list', [
            'besoins' => $besoins,
            'message' => $message
        ]);
    }

    /**
     * Afficher le formulaire de modification d'un besoin
     */
    public static function showEditForm($id, $message = null): void
    {
        $db = Flight::db();
        $besoinData = Besoin::findCompleteById($db, $id);
        if (!$besoinData) {
            $besoinsData = Besoin::findAllComplete($db);
            $besoins = DTOBesoin::fromArrayMultiple($besoinsData);

            Flight::render('besoin/list', [
                'besoins' => $besoins,
                'message' => 'Besoin non trouvé'
            ]);
            return;
        }
        $besoin = DTOBesoin::fromArray($besoinData);
        $villes = Ville::findAllComplete($db);
        
        Flight::render('besoin/edit', [
            'besoin' => $besoin,
            'villes' => $villes,
            'message' => $message
        ]);
    }

    /**
     * Mettre à jour un besoin
     */
    public static function update($id): void
    {
        $db = Flight::db();
        $request = Flight::request();
        
        $ville_id = $request->data->ville_id;
        $quantite = $request->data->quantite;

        try {
            $besoin = Besoin::findById($db, $id);
            if (!$besoin) {
                $besoinsData = Besoin::findAllComplete($db);
                $besoins = DTOBesoin::fromArrayMultiple($besoinsData);

                Flight::render('besoin/list', [
                    'besoins' => $besoins,
                    'message' => 'Besoin non trouvé'
                ]);
                return;
            }
            
            $besoin->setVilleId((int)$ville_id)
                   ->setQuantite((float)$quantite);
            
            if ($besoin->update($db)) {
                $historique = new HistoriqueBesoin();
                $historique->setBesoinId((int)$id)
                          ->setQuantite((float)$quantite);
                $historique->create($db);

                $besoinsData = Besoin::findAllComplete($db);
                $besoins = DTOBesoin::fromArrayMultiple($besoinsData);

                Flight::render('besoin/list', [
                    'besoins' => $besoins,
                    'message' => 'Besoin mis à jour avec succès'
                ]);
                return;
            } else {
                $besoinData2 = Besoin::findCompleteById($db, $id);
                $besoinDto2 = DTOBesoin::fromArray($besoinData2);
                $villes = Ville::findAllComplete($db);

                Flight::render('besoin/edit', [
                    'besoin' => $besoinDto2,
                    'villes' => $villes,
                    'message' => 'Erreur lors de la mise à jour'
                ]);
                return;
            }
        } catch (\Exception $e) {
            $besoinData3 = Besoin::findCompleteById($db, $id);
            $besoinDto3 = DTOBesoin::fromArray($besoinData3);
            $villes = Ville::findAllComplete($db);

            Flight::render('besoin/edit', [
                'besoin' => $besoinDto3,
                'villes' => $villes,
                'message' => 'Erreur : ' . $e->getMessage()
            ]);
            return;
        }
    }

    /**
     * Supprimer un besoin
     */
    public static function delete($id): void
    {
        $db = Flight::db();
        
        try {
            $besoin = Besoin::findById($db, $id);
            if (!$besoin) {
                $besoinsData = Besoin::findAllComplete($db);
                $besoins = DTOBesoin::fromArrayMultiple($besoinsData);

                Flight::render('besoin/list', [
                    'besoins' => $besoins,
                    'message' => 'Besoin non trouvé'
                ]);
                return;
            }
            
            if ($besoin->delete($db)) {
                $besoinsData = Besoin::findAllComplete($db);
                $besoins = DTOBesoin::fromArrayMultiple($besoinsData);

                Flight::render('besoin/list', [
                    'besoins' => $besoins,
                    'message' => 'Besoin supprimé avec succès'
                ]);
                return;
            } else {
                $besoinsData = Besoin::findAllComplete($db);
                $besoins = DTOBesoin::fromArrayMultiple($besoinsData);

                Flight::render('besoin/list', [
                    'besoins' => $besoins,
                    'message' => 'Erreur lors de la suppression'
                ]);
                return;
            }
        } catch (\Exception $e) {
            $besoinsData = Besoin::findAllComplete($db);
            $besoins = DTOBesoin::fromArrayMultiple($besoinsData);

            Flight::render('besoin/list', [
                'besoins' => $besoins,
                'message' => 'Erreur : ' . $e->getMessage()
            ]);
            return;
        }
    }

    /**
     * Afficher l'historique d'un besoin
     */
    public static function showHistorique($id, $message = null): void
    {
        $db = Flight::db();
        $besoinData = Besoin::findCompleteById($db, $id);
        if (!$besoinData) {
            $besoinsData = Besoin::findAllComplete($db);
            $besoins = DTOBesoin::fromArrayMultiple($besoinsData);

            Flight::render('besoin/list', [
                'besoins' => $besoins,
                'message' => 'Besoin non trouvé'
            ]);
            return;
        }
        $besoin = DTOBesoin::fromArray($besoinData);
        $historique = HistoriqueBesoin::findByBesoinId($db, $id);
        
        Flight::render('besoin/historique', [
            'besoin' => $besoin,
            'historique' => $historique,
            'message' => $message
        ]);
    }
}
