<?php
use controllers\HomeController;
use controllers\InscriptionController;
use controllers\LoginController;
use controllers\BackofficeController;
use controllers\ObjetController;
use controllers\EchangeController;
use controllers\CategorieController;

// Home
Flight::route('GET /', [HomeController::class, 'showIndex']);
Flight::route('GET /index', [HomeController::class, 'showIndex']);

// Authentication
Flight::route('GET /inscription', [InscriptionController::class, 'showInscription']);
Flight::route('POST /inscription/validate', [InscriptionController::class, 'validateRegister']);
Flight::route('POST /inscription/register', [InscriptionController::class, 'register']);

Flight::route('GET /login', [LoginController::class, 'goToLogin']);
Flight::route('GET /login-admin', [LoginController::class, 'goToAdminLogin']);
Flight::route('POST /login/verifyUser', [LoginController::class, 'verifyUser']);
Flight::route('GET /logout', [LoginController::class, 'logout']);

// Objets
Flight::route('GET /objets', [ObjetController::class, 'listObjets']);
Flight::route('GET /objets/search', [ObjetController::class, 'searchObjets']);
Flight::route('GET /objets/ajout', [ObjetController::class, 'showAjoutObjet']);
Flight::route('POST /objets/create', [ObjetController::class, 'createObjet']);
Flight::route('GET /objets/@id/edit', [ObjetController::class, 'showEditObjet']);
Flight::route('POST /objets/@id/update', [ObjetController::class, 'updateObjet']);
Flight::route('POST /objets/@id/delete', [ObjetController::class, 'deleteObjet']);
Flight::route('GET /objets/@id', [ObjetController::class, 'showObjet']);
Flight::route('GET /mes-objets', [ObjetController::class, 'mesObjets']);

// Echanges
Flight::route('GET /echanges', [EchangeController::class, 'mesEchanges']);
Flight::route('POST /echanges/proposer', [EchangeController::class, 'proposerEchange']);
Flight::route('POST /echanges/@id/accepter', [EchangeController::class, 'accepterEchange']);
Flight::route('POST /echanges/@id/refuser', [EchangeController::class, 'refuserEchange']);
Flight::route('POST /echanges/@id/annuler', [EchangeController::class, 'annulerEchange']);
Flight::route('GET /echanges/@id/detail', [EchangeController::class, 'detailEchange']);

// Backoffice
Flight::route('GET /backoffice', [BackofficeController::class, 'showDashboard']);
Flight::route('GET /backoffice/stats', [BackofficeController::class, 'showStats']);

// Backoffice - Categories
Flight::route('GET /backoffice/categories', [CategorieController::class, 'showCategories']);
Flight::route('GET /api/categories', [CategorieController::class, 'getAll']);
Flight::route('POST /backoffice/categories/create', [CategorieController::class, 'createCategorie']);
Flight::route('POST /backoffice/categories/@id/update', [CategorieController::class, 'updateCategorie']);
Flight::route('POST /backoffice/categories/@id/delete', [CategorieController::class, 'deleteCategorie']);