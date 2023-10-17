<?php

require_once 'models/Mission.php';

class MissionsController {

  public function index() { 

    $nameMenu = "Missions";
    $path = BASE_URL."/missions";

    $missions = Mission::findAll();
    require_once 'views/header.php';
    require_once 'views/missions/missionsPage.php';
    require_once 'views/footer.php';

  }

}