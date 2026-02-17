<?php

namespace utils;

use PDO;
use PDOException;

/**
 * Classe utilitaire pour la réinitialisation des données
 */
class Reset
{
    /**
     * Réinitialiser les données du système aux valeurs initiales
     * 
     * @param PDO $db Connexion à la base de données
     * @return array ['success' => bool, 'message' => string]
     */
    public static function resetToInitialData(PDO $db): array
    {
        try {
            // Démarrer une transaction
            $db->beginTransaction();

            // 1️⃣ Supprimer toutes les données actuelles (dans le bon ordre)
            $db->exec("DELETE FROM bngrc_distribution");
            $db->exec("DELETE FROM bngrc_historique_besoin");
            $db->exec("DELETE FROM bngrc_achat");
            $db->exec("DELETE FROM bngrc_dons");
            $db->exec("DELETE FROM bngrc_besoin");

            // 2️⃣ Réinitialiser les AUTO_INCREMENT
            $db->exec("ALTER TABLE bngrc_besoin AUTO_INCREMENT = 1");
            $db->exec("ALTER TABLE bngrc_dons AUTO_INCREMENT = 1");
            $db->exec("ALTER TABLE bngrc_distribution AUTO_INCREMENT = 1");
            $db->exec("ALTER TABLE bngrc_historique_besoin AUTO_INCREMENT = 1");
            $db->exec("ALTER TABLE bngrc_achat AUTO_INCREMENT = 1");

            // 3️⃣ Recharger les données initiales depuis les tables de sauvegarde
            $db->exec("INSERT INTO bngrc_besoin (id, ville_id, type_article_id, quantite, date_demande) 
                       SELECT id, ville_id, type_article_id, quantite, date_demande 
                       FROM bngrc_besoin_initial");

            $db->exec("INSERT INTO bngrc_dons (id, type_article_id, quantite, date_don, donateur, statut) 
                       SELECT id, type_article_id, quantite, date_don, donateur, statut 
                       FROM bngrc_dons_initial");

            // 4️⃣ Enregistrer l'historique des besoins initiaux
            $db->exec("INSERT INTO bngrc_historique_besoin (besoin_id, quantite, date_enregistrement) 
                       SELECT id, quantite, date_demande 
                       FROM bngrc_besoin");

            // Valider la transaction
            $db->commit();

            return [
                'success' => true,
                'message' => 'Données réinitialisées avec succès. Le système est revenu à son état initial.'
            ];

        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            return [
                'success' => false,
                'message' => 'Erreur lors de la réinitialisation : ' . $e->getMessage()
            ];
        } catch (\Exception $e) {
            // Annuler la transaction pour toute autre erreur
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            return [
                'success' => false,
                'message' => 'Erreur inattendue : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier si les tables initiales existent et contiennent des données
     * 
     * @param PDO $db Connexion à la base de données
     * @return array ['exists' => bool, 'besoins_count' => int, 'dons_count' => int]
     */
    public static function checkInitialDataExists(PDO $db): array
    {
        try {
            // Vérifier l'existence des tables
            $stmt = $db->query("SHOW TABLES LIKE 'bngrc_besoin_initial'");
            $besoinTableExists = $stmt->rowCount() > 0;

            $stmt = $db->query("SHOW TABLES LIKE 'bngrc_dons_initial'");
            $donsTableExists = $stmt->rowCount() > 0;

            if (!$besoinTableExists || !$donsTableExists) {
                return [
                    'exists' => false,
                    'besoins_count' => 0,
                    'dons_count' => 0,
                    'message' => 'Les tables initiales n\'existent pas. Veuillez exécuter donneeinitial.sql'
                ];
            }

            // Compter les données
            $stmt = $db->query("SELECT COUNT(*) FROM bngrc_besoin_initial");
            $besoinsCount = (int)$stmt->fetchColumn();

            $stmt = $db->query("SELECT COUNT(*) FROM bngrc_dons_initial");
            $donsCount = (int)$stmt->fetchColumn();

            return [
                'exists' => true,
                'besoins_count' => $besoinsCount,
                'dons_count' => $donsCount,
                'message' => "Données initiales disponibles : $besoinsCount besoins, $donsCount dons"
            ];

        } catch (PDOException $e) {
            return [
                'exists' => false,
                'besoins_count' => 0,
                'dons_count' => 0,
                'message' => 'Erreur lors de la vérification : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir un résumé des données actuelles vs initiales
     * 
     * @param PDO $db Connexion à la base de données
     * @return array Statistiques comparatives
     */
    public static function getResetStats(PDO $db): array
    {
        try {
            // Données actuelles
            $stmt = $db->query("SELECT COUNT(*) FROM bngrc_besoin");
            $currentBesoins = (int)$stmt->fetchColumn();

            $stmt = $db->query("SELECT COUNT(*) FROM bngrc_dons");
            $currentDons = (int)$stmt->fetchColumn();

            $stmt = $db->query("SELECT COUNT(*) FROM bngrc_distribution");
            $distributions = (int)$stmt->fetchColumn();

            $stmt = $db->query("SELECT COUNT(*) FROM bngrc_achat");
            $achats = (int)$stmt->fetchColumn();

            // Données initiales
            $initialData = self::checkInitialDataExists($db);

            return [
                'current' => [
                    'besoins' => $currentBesoins,
                    'dons' => $currentDons,
                    'distributions' => $distributions,
                    'achats' => $achats
                ],
                'initial' => [
                    'besoins' => $initialData['besoins_count'],
                    'dons' => $initialData['dons_count']
                ],
                'can_reset' => $initialData['exists']
            ];

        } catch (PDOException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
}
