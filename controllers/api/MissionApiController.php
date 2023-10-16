<?php

class MissionApiController {

    public function index() { 

      foreach (Actor::findAll() as $actor) {
        $actorsData[$actor->getId()] = [
          'country' => $actor->getId_country(),
          'specialities' => $actor->getSpecialities()
        ];
      }
      
      foreach (Hideout::findAll() as $hideout) {
        $hideoutsData[$hideout->getId()] = [
          'country' => $hideout->getId_country()
        ];
      }

      $json = json_encode([
        'actorsData' => $actorsData,
        'hideoutsData' => $hideoutsData
      ]);

      echo $json;

    }
    
}