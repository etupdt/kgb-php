<?php

class MissionApiController {

  public function index() { 

    $depth = 0;

    $hideoutRepository = new HideoutRepository($depth);
    $countryRepository = new CountryRepository($depth);
    $actorRepository = new ActorRepository($depth);
    $specialityRepository = new SpecialityRepository($depth);

    $agentssData = [];
    $contactsData = [];
    $hideoutsData = [];

    foreach ($actorRepository->findAll() as $actor) {
      $actorsData[$actor->getId()] = [
        'countries' => [$actor->getCountry()->getId()],
        'specialities' => $actor->getSpecialities()
      ];
    }
    
    foreach ($hideoutRepository->findAll() as $hideout) {
      $hideoutsData[$hideout->getId()] = [
        'countries' => [$hideout->getCountry()->getName()]
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