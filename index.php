<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="docsearch:language" content="fr">
  <title>KGB</title>
  <link href="/views/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
  <script src="/views/js/script.js" defer crossorigin="anonymous"></script>
</head>
<body clas="d-flex flex-column">

<?php 

define("BASE_URL", '');
define("ADMIN_URL", '/admin');

require_once 'models/Router.php';

require_once 'controllers/admin/HomeController.php';
require_once 'controllers/admin/CountryController.php';
require_once 'controllers/admin/HideoutController.php';
require_once 'controllers/admin/ActorController.php';
require_once 'controllers/admin/SpecialityController.php';
require_once 'controllers/admin/TypeMissionController.php';
require_once 'controllers/admin/StatutController.php';
require_once 'controllers/admin/RoleController.php';
require_once 'controllers/admin/MissionController.php';

require_once 'models/Database.php';

$router = new Router();

$router->addRoute('GET',BASE_URL.'/', 'HomeController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/pays', 'CountryController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/pays', 'CountryController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/planque', 'HideoutController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/planque', 'HideoutController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/acteur', 'ActorController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/acteur', 'ActorController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/specialite', 'SpecialityController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/specialite', 'SpecialityController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/typemission', 'TypeMissionController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/typemission', 'TypeMissionController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/statut', 'StatutController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/statut', 'StatutController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/role', 'RoleController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/role', 'RoleController', 'index');

$router->addRoute('GET',BASE_URL.ADMIN_URL.'/mission', 'MissionController', 'index');
$router->addRoute('POST',BASE_URL.ADMIN_URL.'/mission', 'MissionController', 'index');

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

?>
</body>
