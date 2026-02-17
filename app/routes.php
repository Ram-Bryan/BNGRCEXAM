<?php

use controllers\BesoinController;
use controllers\DonController;
use controllers\StatsController;
use controllers\AchatController;
use controllers\SimulationController;
use controllers\RecapController;
use controllers\HomeController;
use controllers\ConfigurationController;

// ==================== ACCUEIL ====================
Flight::route('GET /', [new HomeController(), 'index']);
Flight::route('GET /index', [new HomeController(), 'index']);

// ==================== BESOINS ====================
Flight::route('GET /besoins', [BesoinController::class, 'listBesoins']);
Flight::route('GET /besoins/ajout', [BesoinController::class, 'showForm']);
Flight::route('POST /besoins/create', [BesoinController::class, 'create']);
Flight::route('GET /besoins/@id/edit', [BesoinController::class, 'showEditForm']);
Flight::route('POST /besoins/@id/update', [BesoinController::class, 'update']);
Flight::route('POST /besoins/@id/delete', [BesoinController::class, 'delete']);
Flight::route('GET /besoins/@id/historique', [BesoinController::class, 'showHistorique']);

// ==================== DONS ====================
Flight::route('GET /dons', [DonController::class, 'listDons']);
Flight::route('GET /dons/ajout', [DonController::class, 'showForm']);
Flight::route('POST /dons/create', [DonController::class, 'create']);
Flight::route('POST /dons/@id/delete', [DonController::class, 'delete']);

// ==================== ACHATS ====================
Flight::route('GET /achats', [new AchatController(), 'listAchats']);
Flight::route('GET /achats/besoins', [new AchatController(), 'showBesoinsRestants']);
Flight::route('POST /achats/create', [new AchatController(), 'create']);
Flight::route('POST /achats/valider', [new AchatController(), 'validerTous']);
Flight::route('POST /achats/annuler', [new AchatController(), 'annulerSimulation']);
Flight::route('POST /achats/@id/delete', [new AchatController(), 'delete']);

// ==================== SIMULATION ====================
Flight::route('GET /simulation', [SimulationController::class, 'showSimulation']);
Flight::route('POST /simulation/simuler', [SimulationController::class, 'simuler']);
Flight::route('POST /simulation/simuler-petit', [SimulationController::class, 'simulerPlusPetit']);
Flight::route('POST /simulation/valider', [SimulationController::class, 'valider']);
Flight::route('POST /simulation/annuler', [SimulationController::class, 'annuler']);
Flight::route('GET /simulation/etat', [SimulationController::class, 'getEtat']);

// ==================== RÉCAPITULATION ====================
Flight::route('GET /recap', [RecapController::class, 'showRecap']);
Flight::route('GET /recap/ajax', [RecapController::class, 'getRecapAjax']);

// ==================== STATISTIQUES ====================
Flight::route('GET /stats', [StatsController::class, 'listVilles']);
Flight::route('GET /stats/ville/@id', [StatsController::class, 'showVilleDetail']);

// ==================== CONFIGURATION ====================
Flight::route('GET /configurations', [ConfigurationController::class, 'list']);
Flight::route('POST /configurations/create', [ConfigurationController::class, 'create']);
Flight::route('POST /configurations/update', [ConfigurationController::class, 'update']);
Flight::route('POST /configurations/delete/@id', [ConfigurationController::class, 'delete']);