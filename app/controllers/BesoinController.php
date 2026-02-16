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
    public static function showForm(): void
    {
        $db = Flight::db();
        $villes = Ville::findAllComplete($db);
        $typeArticles = TypeArticle::findAll($db);
        
        Flight::render('besoin/form', [
            'villes' => $villes,
            'typeArticles' => $typeArticles
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
                
                Flight::redirect('/besoins?message=' . urlencode('Besoin créé avec succès'));
            } else {
                Flight::redirect('/besoins/ajout?message=' . urlencode('Erreur lors de la création'));
            }
        } catch (\Exception $e) {
            Flight::redirect('/besoins/ajout?message=' . urlencode('Erreur : ' . $e->getMessage()));
        }
    }

    /**
     * Afficher la liste des besoins
     */
    public static function listBesoins(): void
    {
        $db = Flight::db();
        $besoinsData = Besoin::findAllComplete($db);
        $besoins = DTOBesoin::fromArrayMultiple($besoinsData);
        
        Flight::render('besoin/list', [
            'besoins' => $besoins
        ]);
    }

    /**
     * Afficher le formulaire de modification d'un besoin
     */
    public static function showEditForm($id): void
    {
        $db = Flight::db();
        $besoinData = Besoin::findCompleteById($db, $id);
        if (!$besoinData) {
            Flight::redirect('/besoins?message=' . urlencode('Besoin non trouvé'));
            return;
        }
        $besoin = DTOBesoin::fromArray($besoinData);
        $villes = Ville::findAllComplete($db);
        
        Flight::render('besoin/edit', [
            'besoin' => $besoin,
            'villes' => $villes
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
                Flight::redirect('/besoins?message=' . urlencode('Besoin non trouvé'));
                return;
            }
            
            $besoin->setVilleId((int)$ville_id)
                   ->setQuantite((float)$quantite);
            
            if ($besoin->update($db)) {
                $historique = new HistoriqueBesoin();
                $historique->setBesoinId((int)$id)
                          ->setQuantite((float)$quantite);
                $historique->create($db);
                
                Flight::redirect('/besoins?message=' . urlencode('Besoin mis à jour avec succès'));
            } else {
                Flight::redirect('/besoins/' . $id . '/edit?message=' . urlencode('Erreur lors de la mise à jour'));
            }
        } catch (\Exception $e) {
            Flight::redirect('/besoins/' . $id . '/edit?message=' . urlencode('Erreur : ' . $e->getMessage()));
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
                Flight::redirect('/besoins?message=' . urlencode('Besoin non trouvé'));
                return;
            }
            
            if ($besoin->delete($db)) {
                Flight::redirect('/besoins?message=' . urlencode('Besoin supprimé avec succès'));
            } else {
                Flight::redirect('/besoins?message=' . urlencode('Erreur lors de la suppression'));
            }
        } catch (\Exception $e) {
            Flight::redirect('/besoins?message=' . urlencode('Erreur : ' . $e->getMessage()));
        }
    }

    /**
     * Afficher l'historique d'un besoin
     */
    public static function showHistorique($id): void
    {
        $db = Flight::db();
        $besoinData = Besoin::findCompleteById($db, $id);
        if (!$besoinData) {
            Flight::redirect('/besoins?message=' . urlencode('Besoin non trouvé'));
            return;
        }
        $besoin = DTOBesoin::fromArray($besoinData);
        $historique = HistoriqueBesoin::findByBesoinId($db, $id);
        
        Flight::render('besoin/historique', [
            'besoin' => $besoin,
            'historique' => $historique
        ]);
    }
}
