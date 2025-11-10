<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../bootstrap.php';

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\OrganizationController;
use App\Controllers\MemberController;
use App\Controllers\FinanceController;
use App\Controllers\ElectionController;
use App\Controllers\PublicController;

$router = new Router();
$authController = new AuthController();
$dashboardController = new DashboardController();
$organizationController = new OrganizationController();
$memberController = new MemberController();
$financeController = new FinanceController();
$electionController = new ElectionController();
$publicController = new PublicController();

$router->get('/login', fn() => $authController->showLogin());
$router->post('/login', fn() => $authController->login());
$router->get('/register', fn() => $authController->showRegister());
$router->post('/register', fn() => $authController->register());
$router->post('/verify', fn() => $authController->verify());
$router->post('/logout', fn() => $authController->logout());

$router->get('/', fn() => $publicController->home());
$router->get('/public', fn() => $publicController->home());
$router->get('/dashboard', fn() => $dashboardController->index());
$router->get('/organizations', fn() => $organizationController->index('dra'));
$router->get('/organizations/{type}', fn($type) => $organizationController->index($type));
$router->post('/organizations/{type}', fn($type) => $organizationController->store($type));
$router->get('/memberships', fn() => $memberController->index());
$router->get('/memberships/apply', fn() => $memberController->apply());
$router->post('/memberships', fn() => $memberController->store());
$router->get('/finance', fn() => $financeController->index());
$router->get('/elections', fn() => $electionController->index());

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptDir = str_replace('\\', '/', dirname($scriptName));
$scriptDir = $scriptDir === '/' ? '' : rtrim($scriptDir, '/');

if (str_contains($requestUri, '?')) {
    $requestPath = strstr($requestUri, '?', true);
} else {
    $requestPath = $requestUri;
}

if ($scriptDir !== '' && str_starts_with($requestPath, $scriptDir)) {
    $requestPath = substr($requestPath, strlen($scriptDir)) ?: '/';
}

$router->dispatch($requestPath ?: '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');
