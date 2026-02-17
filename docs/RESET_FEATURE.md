# 🔄 Fonctionnalité de Réinitialisation - Guide d'Installation

## 📋 Résumé
Cette fonctionnalité permet de réinitialiser toutes les données du système (distributions, achats, historique) et de restaurer les besoins et dons initiaux.

## 🗂️ Structure des Tables

### Tables Principales (production)
- `bngrc_besoin` - Besoins des villes
- `bngrc_dons` - Dons reçus
- `bngrc_distribution` - Distributions (simulées ou validées)
- `bngrc_historique_besoin` - Historique des modifications
- `bngrc_achat` - Achats effectués

### Tables de Sauvegarde (données initiales)
- `bngrc_besoin_initial` - **COPIE** exacte de bngrc_besoin (structure identique)
- `bngrc_dons_initial` - **COPIE** exacte de bngrc_dons (structure identique)

## 🚀 Installation

### Étape 1 : Exécuter le script SQL
```bash
mysql -u root -p bngrc < data/donneeinitial.sql
```

Ou via phpMyAdmin : Importer `data/donneeinitial.sql`

### Étape 2 : Vérifier les données
```sql
SELECT * FROM bngrc_besoin_initial;  -- Doit afficher 12 besoins
SELECT * FROM bngrc_dons_initial;    -- Doit afficher 12 dons
```

## 📊 Données Initiales Incluses

### Besoins (12 total)
- **Antananarivo** : 50 sacs Riz, 200 bouteilles Eau
- **Toamasina** : 100 Médicaments, 80 Couvertures
- **Antsirabe** : 30 sacs Riz, 15 Tentes, 5 000 000 Ar
- **Fianarantsoa** : 150 bouteilles Eau, 50 Médicaments
- **Mahajanga** : 40 sacs Riz, 60 Couvertures

### Dons (12 total)
- **Riz** : 80 + 50 sacs
- **Argent** : 10 000 000 + 3 000 000 Ar
- **Eau** : 300 + 150 bouteilles
- **Médicaments** : 120 + 80 boîtes
- **Couvertures** : 100 + 70
- **Tentes** : 20 + 10

## 🎯 Utilisation

### Via Interface Web
1. Aller sur `/simulation`
2. Cliquer sur le bouton rouge **"🔄 RÉINITIALISER"**
3. Confirmer deux fois (sécurité)
4. Le système affiche les statistiques avant/après
5. Page rechargée automatiquement

### Via API
```javascript
fetch('/reset', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' }
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('Réinitialisation réussie:', data.stats);
    }
});
```

### Réponse JSON
```json
{
    "success": true,
    "message": "Données réinitialisées avec succès...",
    "stats": {
        "before": {
            "besoins": 15,
            "dons": 8,
            "distributions": 23,
            "achats": 5
        },
        "after": {
            "besoins": 12,
            "dons": 12,
            "distributions": 0,
            "achats": 0
        }
    }
}
```

## 🔧 Fichiers Techniques

### Backend
- `app/utils/Reset.php` - Logique DAO de réinitialisation
- `app/controllers/ResetController.php` - Controller API
- `app/routes.php` - Routes POST /reset et GET /reset/stats

### Frontend
- `app/views/simulation/index.php` - Bouton UI
- `public/assets/js/simulation.js` - Fonction resetData()

### SQL
- `data/donneeinitial.sql` - Script de création des tables initiales

## ⚠️ Avertissements

1. **Action IRRÉVERSIBLE** : Toutes les distributions, achats et historiques sont supprimés
2. **Double confirmation** : L'utilisateur doit confirmer 2 fois
3. **Transaction atomique** : Si une erreur survient, tout est annulé (ROLLBACK)
4. **Données préservées** : Les villes, régions et types d'articles ne sont PAS touchés

## 🔍 Dépannage

### Erreur "Tables initiales n'existent pas"
```bash
# Réexécuter le script SQL
mysql -u root -p bngrc < data/donneeinitial.sql
```

### Erreur "Contrainte de clé étrangère"
```sql
-- Vérifier que les villes et types d'articles existent
SELECT * FROM bngrc_ville WHERE id IN (1,2,3,4,5);
SELECT * FROM bngrc_type_articles WHERE id IN (1,2,3,4,5,6);
```

### Vérifier l'état des données
```bash
curl http://localhost:1234/reset/stats
```

## 📝 Notes Techniques

### Structure Identique
Les tables `*_initial` ont **exactement** la même structure que les tables principales :
- Même types de colonnes (INT pour quantite, ENUM pour statut)
- Mêmes contraintes de clés étrangères
- Mêmes valeurs par défaut

### Transaction SQL
```sql
START TRANSACTION;
DELETE FROM bngrc_distribution;
DELETE FROM bngrc_historique_besoin;
DELETE FROM bngrc_achat;
DELETE FROM bngrc_dons;
DELETE FROM bngrc_besoin;
ALTER TABLE bngrc_besoin AUTO_INCREMENT = 1;
ALTER TABLE bngrc_dons AUTO_INCREMENT = 1;
INSERT INTO bngrc_besoin SELECT * FROM bngrc_besoin_initial;
INSERT INTO bngrc_dons SELECT * FROM bngrc_dons_initial;
COMMIT;
```

## ✅ Checklist d'Installation

- [ ] Script SQL exécuté sans erreurs
- [ ] 12 besoins dans `bngrc_besoin_initial`
- [ ] 12 dons dans `bngrc_dons_initial`
- [ ] Bouton RÉINITIALISER visible sur `/simulation`
- [ ] Double confirmation fonctionne
- [ ] Statistiques affichées après reset
- [ ] Page se recharge automatiquement

---
**Version** : 1.0  
**Date** : 17 février 2026  
**Auteur** : Équipe BNGRC
