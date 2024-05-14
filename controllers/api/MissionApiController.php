<?php

class MissionApiController {

  public function index() { 

    $depth = 0;

    $hideoutRepository = new HideoutRepository(1);
    $countryRepository = new CountryRepository($depth);
    $actorRepository = new ActorRepository(2);
    $specialityRepository = new SpecialityRepository($depth);

    $agentssData = [];
    $contactsData = [];
    $hideoutsData = [];

    foreach ($actorRepository->findAll() as $actor) {
      error_log('===== api =======>   '.$actor->getId().'    <======================');

      $specialities = [];

      foreach ($actor->getSpecialities() as $speciality) {
        $specialities[$speciality['speciality']->getId()] = $speciality['speciality']->getName();
      }

      $actorsData[$actor->getId()] = [
        'country' => [
          'id' => $actor->getCountry()->getId(),
          'name' => $actor->getCountry()->getName()
        ],
        'specialities' => $specialities
      ];
    }
    
    foreach ($hideoutRepository->findAll() as $hideout) {
      $hideoutsData[$hideout->getId()] = [
        'country' => [
          'id' => $hideout->getCountry()->getId(),
          'name' => $hideout->getCountry()->getName()
        ],
];
    }

    foreach ($countryRepository->findAll() as $country) {
      $countriesData[$country->getId()] = [
        'libelle' => $country->getName()
      ];
    }

    foreach ($specialityRepository->findAll() as $speciality) {
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