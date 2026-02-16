<?php
use controllers\BesoinController;
use controllers\DonController;
use controllers\StatsController;

// ==================== ACCUEIL ====================
Flight::route('GET /', function () {
    Flight::render('index');
});
Flight::route('GET /index', function () {
    Flight::render('index');
});

// ==================== BESOINS ====================
Flight::route('GET /besoins', [new BesoinController(), 'listBesoins']);
Flight::route('GET /besoins/ajout', [new BesoinController(), 'showForm']);
Flight::route('POST /besoins/create', [new BesoinController(), 'create']);
Flight::route('GET /besoins/@id/edit', [new BesoinController(), 'showEditForm']);
Flight::route('POST /besoins/@id/update', [new BesoinController(), 'update']);
Flight::route('POST /besoins/@id/delete', [new BesoinController(), 'delete']);
Flight::route('GET /besoins/@id/historique', [new BesoinController(), 'showHistorique']);

// ==================== DONS ====================
Flight::route('GET /dons', [new DonController(), 'listDons']);
Flight::route('GET /dons/ajout', [new DonController(), 'showForm']);
Flight::route('POST /dons/create', [new DonController(), 'create']);
Flight::route('POST /dons/@id/delete', [new DonController(), 'delete']);

// ==================== STATISTIQUES ====================
Flight::route('GET /stats', [new StatsController(), 'listVilles']);
Flight::route('GET /stats/ville/@id', [new StatsController(), 'showVilleDetail']);