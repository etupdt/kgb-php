<?php

// Api pour enrichier les pages d'aide
class HideoutApiController {

    public function index() { 

      $origin = $_GET['origin'];
      $id = $_GET['id'];

      switch ($origin) {
        case 'Actor' : {
          Mission::findBy(['id_actor']);
        }
      }

      $missionsData = [];

      // on recherche les missisons
      foreach (Mission::findAll() as $mission) {
   
        $missionsData[] = [
          'id_mission' => $mission->getId(),
          'title' => $mission->getTitle(),
          'countries' => [$mission->getId_country()],
          'hideouts' => $mission->getHideouts()
        ];

      }
      
      // on retourne au front-end la liste des missions associées à la planque
      $json = json_encode([
        'missionsData' => $missionsData
      ]);

      echo $json;

    }
    
}