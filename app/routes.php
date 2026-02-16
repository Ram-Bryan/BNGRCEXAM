<?php

use controllers\BesoinController;
use controllers\DonController;
use controllers\StatsController;
use controllers\AchatController;
use controllers\SimulationController;
use controllers\RecapController;
use controllers\HomeController;

// ==================== ACCUEIL ====================
Flight::route('GET /', [new HomeController(), 'index']);
Flight::route('GET /index', [new HomeController(), 'index']);

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

// ==================== ACHATS ====================
Flight::route('GET /achats', [new AchatController(), 'listAchats']);
Flight::route('GET /achats/besoins', [new AchatController(), 'showBesoinsRestants']);
Flight::route('POST /achats/create', [new AchatController(), 'create']);
Flight::route('POST /achats/valider', [new AchatController(), 'validerTous']);
Flight::route('POST /achats/annuler', [new AchatController(), 'annulerSimulation']);
Flight::route('POST /achats/@id/delete', [new AchatController(), 'delete']);

// ==================== SIMULATION ====================
Flight::route('GET /simulation', [new SimulationController(), 'showSimulation']);
Flight::route('POST /simulation/simuler', [new SimulationController(), 'simuler']);
Flight::route('POST /simulation/valider', [new SimulationController(), 'valider']);
Flight::route('POST /simulation/annuler', [new SimulationController(), 'annuler']);
Flight::route('GET /simulation/etat', [new SimulationController(), 'getEtat']);

// ==================== RÉCAPITULATION ====================
Flight::route('GET /recap', [new RecapController(), 'showRecap']);
Flight::route('GET /recap/ajax', [new RecapController(), 'getRecapAjax']);

// ==================== STATISTIQUES ====================
Flight::route('GET /stats', [new StatsController(), 'listVilles']);
Flight::route('GET /stats/ville/@id', [new StatsController(), 'showVilleDetail']);
