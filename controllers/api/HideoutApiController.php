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

      $missionRepository = new MissionRepository(2);

      $missionsData = [];

      // on recherche les missisons
      foreach ($missionRepository->findAll() as $mission) {
        
        $hideouts = [];
        foreach ($mission->getHideouts() as $hideout) {
          error_log('======= hideout =========> '.$hideout['hideout']->getAddress());
          $hideouts[$hideout['hideout']->getId()] = [
            'address' => $hideout['hideout']->getAddress(),
            'country' => [
              'id' => $hideout['hideout']->getCountry()->getId(),
              'name' => $hideout['hideout']->getCountry()->getName()
            ],  
          ];
        }

        $missionsData[$mission->getTitle()] = [
          'id_mission' => $mission->getId(),
          'title' => $mission->getTitle(),
          'country' => [
            'id' => $mission->getCountry()->getId(),
            'name' => $mission->getCountry()->getName()
          ],
          'hideouts' => $hideouts
        ];

      }
      
      // on retourne au front-end la liste des missions associées à la planque
      $json = json_encode([
        'missionsData' => $missionsData
      ]);

      echo $json;

    }
    
}