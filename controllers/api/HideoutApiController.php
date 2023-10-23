<?php

/*
1/ Sur une mission, la ou les cibles ne peuvent pas avoir la même nationalité que le ou les agents.
2/ Sur une mission, les contacts sont obligatoirement de la nationalité du pays de la mission.
3/ Sur une mission, la planque est obligatoirement dans le même pays que la mission.
4/ Sur une mission, il faut assigner au moins 1 agent disposant de la spécialité requise.
*/

// Api pour controle lors de la création-modification d'un acteur
class HideoutApiController {

    public function index() { 

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