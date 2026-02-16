<?php

namespace controllers;

use models\Don;
use models\Besoin;
use dto\DTODon;
use dto\DTOBesoin;
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
        $dons = DTODon::fromArrayMultiple($donsData);
        
        Flight::render('don/list', [
            'dons' => $dons
        ]);
    }

    /**
     * Afficher le formulaire d'ajout de don
     * Les besoins non satisfaits sont triés par date_demande ASC (priorité aux plus anciens)
     */
    public function showForm()
    {
        // Récupérer les besoins non satisfaits triés par ancienneté
        $besoinsData = Besoin::findBesoinsNonSatisfaits($this->db);
        $besoins = DTOBesoin::fromArrayMultiple($besoinsData);
        
        Flight::render('don/form', [
            'besoins' => $besoins
        ]);
    }

    /**
     * Créer un nouveau don
     * Logique de distribution : les besoins les plus anciens sont prioritaires
     */
    public function create()
    {
        $idbesoins = Flight::request()->data->idbesoins;
        $quantite = Flight::request()->data->quantite;
        $date_livraison = Flight::request()->data->date_livraison;

        try {
            // Vérifier que le besoin existe et n'est pas déjà complètement satisfait
            $besoinData = Besoin::findCompleteById($this->db, $idbesoins);
            if (!$besoinData) {
                Flight::redirect('/dons/ajout?error=besoin_not_found');
                return;
            }

            // Vérifier la quantité restante
            $totalDonsExistants = Don::getTotalDonsByBesoin($this->db, $idbesoins);
            $quantiteRestante = $besoinData['quantite'] - $totalDonsExistants;
            
            if ($quantite > $quantiteRestante) {
                Flight::redirect('/dons/ajout?error=' . urlencode("Quantité trop élevée. Restant: $quantiteRestante"));
                return;
            }

            $don = new Don();
            $don->setIdbesoins($idbesoins)
                ->setQuantite($quantite)
                ->setDateLivraison($date_livraison);
            
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
