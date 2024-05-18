<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/models/entities/Mission.php';

class MissionsController {

  public function index() { 

    $nameMenu = "Missions";
    $path = BASE_URL."/missions";

    $missionRepository = new MissionRepository(2);

    $missions = $missionRepository->findAll();
    require_once $_SERVER['DOCUMENT_ROOT'].'/views/header.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/views/missions/missionsPage.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/views/footer.php';

  }

}