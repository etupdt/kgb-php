<?php

class MissionApiController {

    public function index() { 

      $agentssData = [];
      $contactsData = [];
      $hideoutsData = [];

      foreach (Actor::findAll() as $actor) {
        $actorsData[$actor->getId()] = [
          'countries' => [$actor->getId_country()],
          'specialities' => $actor->getSpecialities()
        ];
      }
      
      foreach (Hideout::findAll() as $hideout) {
        $hideoutsData[$hideout->getId()] = [
          'countries' => [$hideout->getId_country()]
        ];
      }

      foreach (Country::findAll() as $country) {
        $countriesData[$country->getId()] = [
          'libelle' => $country->getName()
        ];
      }

      foreach (Speciality::findAll() as $speciality) {
        $specialitiesData[$speciality->getId()] = [
          'libelle' => $speciality->getName()
        ];
      }

      $json = json_encode([
        'actorsData' => $actorsData,
        'hideoutsData' => $hideoutsData,
        'countriesData' => $countriesData,
        'specialitiesData' => $specialitiesData,
      ]);

      echo $json;

    }
    
}