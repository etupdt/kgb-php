<?php

require_once 'models/entities/Mission.php';

class MissionsController {

  public function index() { 

    $nameMenu = "Missions";
    $path = BASE_URL."/missions";

    $missionRepository = new MissionRepository(2);

    $missions = $missionRepository->findAll();
    require_once 'views/header.php';
    require_once 'views/missions/missionsPage.php';
    require_once 'views/footer.php';

  }

}