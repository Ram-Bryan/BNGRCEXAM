-- ============================================================
-- INDEX POUR OPTIMISER LES PERFORMANCES
-- ============================================================
-- Ces index améliorent significativement les performances des requêtes

-- Index pour la table bngrc_besoin
CREATE INDEX IF NOT EXISTS idx_besoin_ville ON bngrc_besoin(ville_id);
CREATE INDEX IF NOT EXISTS idx_besoin_type_article ON bngrc_besoin(type_article_id);
CREATE INDEX IF NOT EXISTS idx_besoin_date ON bngrc_besoin(date_demande);

-- Index pour la table bngrc_dons
CREATE INDEX IF NOT EXISTS idx_don_type_article ON bngrc_dons(type_article_id);
CREATE INDEX IF NOT EXISTS idx_don_date ON bngrc_dons(date_don);
CREATE INDEX IF NOT EXISTS idx_don_statut ON bngrc_dons(statut);

-- Index pour la table bngrc_distribution (améliore CONSIDÉRABLEMENT les performances)
CREATE INDEX IF NOT EXISTS idx_distribution_don ON bngrc_distribution(don_id);
CREATE INDEX IF NOT EXISTS idx_distribution_besoin ON bngrc_distribution(besoin_id);
CREATE INDEX IF NOT EXISTS idx_distribution_simulation ON bngrc_distribution(est_simulation);
CREATE INDEX IF NOT EXISTS idx_distribution_date ON bngrc_distribution(date_distribution);
-- Index composé pour les requêtes courantes
CREATE INDEX IF NOT EXISTS idx_distribution_don_simulation ON bngrc_distribution(don_id, est_simulation);
CREATE INDEX IF NOT EXISTS idx_distribution_besoin_simulation ON bngrc_distribution(besoin_id, est_simulation);

-- Index pour la table bngrc_achat
CREATE INDEX IF NOT EXISTS idx_achat_besoin ON bngrc_achat(besoin_id);
CREATE INDEX IF NOT EXISTS idx_achat_date ON bngrc_achat(date_achat);
CREATE INDEX IF NOT EXISTS idx_achat_statut ON bngrc_achat(statut);

-- Index pour la table bngrc_historique_besoin
CREATE INDEX IF NOT EXISTS idx_historique_besoin ON bngrc_historique_besoin(besoin_id);
CREATE INDEX IF NOT EXISTS idx_historique_date ON bngrc_historique_besoin(date_enregistrement);

-- Index pour la table bngrc_ville
CREATE INDEX IF NOT EXISTS idx_ville_region ON bngrc_ville(idregion);
