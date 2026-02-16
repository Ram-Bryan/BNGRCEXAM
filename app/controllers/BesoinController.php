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
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    /**
     * Afficher le formulaire de demande de besoin
     */
    public function showForm()
    {
        $villes = Ville::findAllComplete($this->db);
        $typeArticles = TypeArticle::findAllArray($this->db);
        
        Flight::render('besoin/form', [
            'villes' => $villes,
            'typeArticles' => $typeArticles
        ]);
    }

    /**
     * Créer un nouveau besoin
     */
    public function create()
    {
        $ville_id = Flight::request()->data->ville_id;
        $type_article_id = Flight::request()->data->type_article_id;
        $quantite = Flight::request()->data->quantite;
        $date_demande = Flight::request()->data->date_demande;

        try {
            $besoin = new Besoin();
            $besoin->setVilleId($ville_id)
                   ->setTypeArticleId($type_article_id)
                   ->setQuantite($quantite)
                   ->setDateDemande($date_demande);
            
            if ($besoin->create($this->db)) {
                $historique = new HistoriqueBesoin();
                $historique->setBesoinId($besoin->getId())
                          ->setQuantite($quantite);
                $historique->create($this->db);
                
                Flight::redirect('/besoins?success=created');
            } else {
                Flight::redirect('/besoins/ajout?error=creation_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/besoins/ajout?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Afficher la liste des besoins
     */
    public function listBesoins()
    {
        $besoinsData = Besoin::findAllComplete($this->db);
        $besoins = DTOBesoin::fromArrayMultiple($besoinsData);
        
        Flight::render('besoin/list', [
            'besoins' => $besoins
        ]);
    }

    /**
     * Afficher le formulaire de modification d'un besoin
     */
    public function showEditForm($id)
    {
        $besoinData = Besoin::findCompleteById($this->db, $id);
        if (!$besoinData) {
            Flight::redirect('/besoins?error=not_found');
            return;
        }
        $besoin = DTOBesoin::fromArray($besoinData);
        
        Flight::render('besoin/edit', [
            'besoin' => $besoin
        ]);
    }

    /**
     * Mettre à jour un besoin
     */
    public function update($id)
    {
        $quantite = Flight::request()->data->quantite;

        try {
            $besoin = Besoin::findById($this->db, $id);
            if (!$besoin) {
                Flight::redirect('/besoins?error=not_found');
                return;
            }
            
            $besoin->setQuantite($quantite);
            
            if ($besoin->updateQuantite($this->db)) {
                $historique = new HistoriqueBesoin();
                $historique->setBesoinId($id)
                          ->setQuantite($quantite);
                $historique->create($this->db);
                
                Flight::redirect('/besoins?success=updated');
            } else {
                Flight::redirect('/besoins/' . $id . '/edit?error=update_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/besoins/' . $id . '/edit?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Supprimer un besoin
     */
    public function delete($id)
    {
        try {
            $besoin = Besoin::findById($this->db, $id);
            if (!$besoin) {
                Flight::redirect('/besoins?error=not_found');
                return;
            }
            
            if ($besoin->delete($this->db)) {
                Flight::redirect('/besoins?success=deleted');
            } else {
                Flight::redirect('/besoins?error=delete_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/besoins?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Afficher l'historique d'un besoin
     */
    public function showHistorique($id)
    {
        $besoinData = Besoin::findCompleteById($this->db, $id);
        if (!$besoinData) {
            Flight::redirect('/besoins?error=not_found');
            return;
        }
        $besoin = DTOBesoin::fromArray($besoinData);
        $historique = HistoriqueBesoin::findByBesoinId($this->db, $id);
        
        Flight::render('besoin/historique', [
            'besoin' => $besoin,
            'historique' => $historique
        ]);
    }
}
