<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="docsearch:language" content="fr">
  <title>KGB</title>
  <link href="views/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</head>
<body>

<?php 

define("BASE_URL", '');

require_once 'models/Router.php';
// require_once 'controllers/HomeController.php';
// require_once 'controllers/ProfileController.php';
// require_once 'controllers/LogoutController.php';
require_once 'controllers/admin/DashboardController.php';
require_once 'controllers/admin/HideoutController.php';

$router = new Router();

$router->addRoute('GET',BASE_URL.'/', 'DashboardController', 'index');
$router->addRoute('GET',BASE_URL.'/planques', 'HideoutController', 'index');
//$router->addRoute('GET', BASE_URL.'/', 'HomeController', 'index');
$router->addRoute('POST',BASE_URL.'/profile', 'ProfileController', 'index');
$router->addRoute('GET',BASE_URL.'/logout', 'LogoutController', 'index');

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

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
