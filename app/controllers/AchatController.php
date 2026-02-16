<?php

namespace controllers;

use models\Achat;
use models\Besoin;
use models\Ville;
use Flight;
use PDO;

class AchatController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    /**
     * Afficher la liste des achats avec filtre par ville
     */
    public function listAchats()
    {
        $ville_id = Flight::request()->query->ville_id ?? null;

        if ($ville_id) {
            $achats = Achat::findAllByVille($this->db, (int)$ville_id);
        } else {
            $achats = Achat::findAll($this->db);
        }

        // Récupérer les villes pour le filtre
        $villes = Ville::findAllComplete($this->db);

        // Argent disponible
        $argentDisponible = Achat::getArgentDisponible($this->db);

        Flight::render('achat/list', [
            'achats' => $achats,
            'villes' => $villes,
            'ville_id' => $ville_id,
            'argentDisponible' => $argentDisponible
        ]);
    }

    /**
     * Afficher la page des besoins restants pour faire des achats
     */
    public function showBesoinsRestants()
    {
        $ville_id = Flight::request()->query->ville_id ?? null;

        // Besoins non satisfaits de catégorie nature ou material
        $sql = "SELECT * FROM vue_besoins_satisfaction 
                WHERE quantite_restante > 0 AND categorie IN ('nature', 'material')";
        if ($ville_id) {
            $sql .= " AND ville_id = :ville_id";
        }
        $sql .= " ORDER BY date_demande ASC";

        $stmt = $this->db->prepare($sql);
        if ($ville_id) {
            $stmt->execute([':ville_id' => $ville_id]);
        } else {
            $stmt->execute();
        }
        $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Villes pour filtre
        $villes = Ville::findAllComplete($this->db);

        // Argent disponible
        $argentDisponible = Achat::getArgentDisponible($this->db);

        // Frais d'achat
        $fraisPercent = defined('FRAIS_ACHAT_PERCENT') ? FRAIS_ACHAT_PERCENT : 10;

        Flight::render('achat/besoins_restants', [
            'besoins' => $besoins,
            'villes' => $villes,
            'ville_id' => $ville_id,
            'argentDisponible' => $argentDisponible,
            'fraisPercent' => $fraisPercent
        ]);
    }

    /**
     * Créer un achat (simulation)
     */
    public function create()
    {
        $besoin_id = Flight::request()->data->besoin_id;
        $quantite = Flight::request()->data->quantite;

        try {
            // Vérifier si un achat non validé existe déjà pour ce besoin
            if (Achat::existeAchatNonValide($this->db, (int)$besoin_id)) {
                Flight::redirect('/achats/besoins?error=' . urlencode('Un achat en attente existe déjà pour ce besoin. Validez ou annulez d\'abord.'));
                return;
            }

            // Récupérer le besoin
            $besoinData = Besoin::findCompleteById($this->db, (int)$besoin_id);
            if (!$besoinData) {
                Flight::redirect('/achats/besoins?error=besoin_not_found');
                return;
            }

            // Vérifier que c'est un besoin nature ou material
            if ($besoinData['categorie'] === 'argent') {
                Flight::redirect('/achats/besoins?error=' . urlencode('Impossible d\'acheter un besoin de type argent'));
                return;
            }

            // Calculer le montant
            $prixUnitaire = (float)$besoinData['prix_unitaire'];
            $montantHt = $quantite * $prixUnitaire;
            $fraisPercent = defined('FRAIS_ACHAT_PERCENT') ? FRAIS_ACHAT_PERCENT : 10;
            $montantFrais = $montantHt * ($fraisPercent / 100);
            $montantTotal = $montantHt + $montantFrais;

            // Vérifier l'argent disponible
            $argentDisponible = Achat::getArgentDisponible($this->db);
            if ($montantTotal > $argentDisponible) {
                Flight::redirect('/achats/besoins?error=' . urlencode("Argent insuffisant. Disponible: " . number_format($argentDisponible, 2) . " Ar, Requis: " . number_format($montantTotal, 2) . " Ar"));
                return;
            }

            // Créer l'achat (non validé = simulation)
            $achat = new Achat();
            $achat->setBesoinId((int)$besoin_id)
                ->setQuantite((int)$quantite)
                ->setMontantHt($montantHt)
                ->setFraisPercent($fraisPercent)
                ->setMontantFrais($montantFrais)
                ->setMontantTotal($montantTotal)
                ->setDateAchat(date('Y-m-d'))
                ->setValide(false);

            if ($achat->create($this->db)) {
                Flight::redirect('/achats?success=created');
            } else {
                Flight::redirect('/achats/besoins?error=creation_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/achats/besoins?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Valider tous les achats en attente
     */
    public function validerTous()
    {
        try {
            if (Achat::validerTous($this->db)) {
                Flight::redirect('/achats?success=validated');
            } else {
                Flight::redirect('/achats?error=validation_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/achats?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Annuler tous les achats en attente (simulation)
     */
    public function annulerSimulation()
    {
        try {
            if (Achat::annulerSimulation($this->db)) {
                Flight::redirect('/achats?success=cancelled');
            } else {
                Flight::redirect('/achats?error=cancel_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/achats?error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Supprimer un achat non validé
     */
    public function delete($id)
    {
        try {
            $achat = Achat::findById($this->db, (int)$id);
            if (!$achat) {
                Flight::redirect('/achats?error=not_found');
                return;
            }

            if ($achat->isValide()) {
                Flight::redirect('/achats?error=' . urlencode('Impossible de supprimer un achat validé'));
                return;
            }

            if ($achat->delete($this->db)) {
                Flight::redirect('/achats?success=deleted');
            } else {
                Flight::redirect('/achats?error=delete_failed');
            }
        } catch (\Exception $e) {
            Flight::redirect('/achats?error=' . urlencode($e->getMessage()));
        }
    }
}
