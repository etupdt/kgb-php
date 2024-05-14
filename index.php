<?php 

require_once 'models/Router.php';

require_once 'controllers/home/HomeController.php';
require_once 'controllers/missions/MissionsController.php';
require_once 'controllers/missions/MissionPageController.php';
require_once 'controllers/admin/CountryController.php';
require_once 'controllers/admin/HideoutController.php';
require_once 'controllers/admin/ActorController.php';
require_once 'controllers/admin/SpecialityController.php';
require_once 'controllers/admin/TypeMissionController.php';
require_once 'controllers/admin/StatutController.php';
require_once 'controllers/admin/RoleController.php';
require_once 'controllers/admin/MissionController.php';
require_once 'controllers/api/MissionApiController.php';
require_once 'controllers/api/ActorApiController.php';
require_once 'controllers/api/HideoutApiController.php';

require_once 'models/Database.php';

define("BASE_URL", '');
define("ADMIN_URL", '/admin');
define("API_URL", '/api');

$router = new Router(); 

$router->addRoute('GET',BASE_URL.'/', 'HomeController', 'index');

$router->addRoute('GET',BASE_URL.'/missions', 'MissionsController', 'index');
$router->addRoute('GET',BASE_URL.'/mission', 'MissionPageController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/country', 'CountryController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/country', 'CountryController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/hideout', 'HideoutController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/hideout', 'HideoutController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/actor', 'ActorController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/actor', 'ActorController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/speciality', 'SpecialityController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/speciality', 'SpecialityController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/typemission', 'TypeMissionController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/typemission', 'TypeMissionController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/statut', 'StatutController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/statut', 'StatutController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/role', 'RoleController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/role', 'RoleController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/mission', 'MissionController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/mission', 'MissionController', 'index');

$router->addRoute('GET',BASE_URL.API_URL.'/controlmission', 'MissionApiController', 'index');
$router->addRoute('GET',BASE_URL.API_URL.'/controlactor', 'ActorApiController', 'index');
$router->addRoute('GET',BASE_URL.API_URL.'/controlhideout', 'HideoutApiController', 'index');

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('?', $_SERVER['REQUEST_URI'])[0];

$handler = $router->gethandler($method, $uri);

if ($handler == null ) { 

    header ('HTTP/1.1 404 not found');
    exit();
}

$controller = new $handler['controller']();
$action = $handler['action'];
$controller->$action();
