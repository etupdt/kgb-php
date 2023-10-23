<?php

require_once 'models/entities/Mission.php';

class MissionPageController {

  public function index() { 

    $hideouts = [];
    $roles = Role::findAll();
  
    $nameMenu = "Mission";
    $path = BASE_URL."/mission";

    $mission = $this->getRow(Mission::find($_GET['id']), $roles);
    require_once 'views/header.php';
    require_once 'views/missions/missionPage.php';
    require_once 'views/footer.php';

  }

  private function getRow (Mission $mission, $roles): array 
  {

    $hideouts = [];

    foreach(Hideout::findAll() as $hideout) {
      $hideoutWithCountry['id'] = $hideout->getId();
      $hideoutWithCountry['code'] = $hideout->getCode();
      $hideoutWithCountry['address'] = $hideout->getAddress();
      $hideoutWithCountry['type'] = $hideout->getType();
      $hideoutWithCountry['country'] = Country::find($hideout->getId_country());
      if (in_array($hideoutWithCountry['id'], $mission->getHideouts())) {
        $hideouts[] = $hideoutWithCountry;
      }
    }

    $row = [
        'id' => $mission->getId(),
        'title' => $mission->getTitle(),
        'description' => $mission->getDescription(),
        'codeName' => $mission->getCodeName(),
        'begin' => $mission->getBegin(),
        'end' => $mission->getEnd(),
        'country' => Country::find($mission->getId_country())->getName(),
        'statut' => Statut::find($mission->getId_statut())->getStatut(),
        'typeMission' => TypeMission::find($mission->getId_typeMission())->getTypeMission(),
        'speciality' => Speciality::find($mission->getId_speciality())->getName(),
        'hideouts' => $hideouts,
    ];

    $actorsRoles = $mission->getActorsRoles();

    foreach ($roles as $role) {

        $actors = [];

        foreach($actorsRoles as $actorRole) {
          if ($actorRole['id_role'] == $role->getId()) {
            $actor = Actor::find($actorRole['id_actor']);
            $actorWithCountry['firstname'] = $actor->getFirstname();
            $actorWithCountry['lastname'] = $actor->getLastname();
            $actorWithCountry['birthdate'] = $actor->getBirthdate();
            $actorWithCountry['identificationCode'] = $actor->getIdentificationCode();
            $actorWithCountry['country'] = Country::find($actor->getId_country())->getName();
            $actors[] = $actorWithCountry;
          }
        }

        $row[str_replace(' ', '_', $role->getRole())] = $actors;

    }
    
    return $row;

  }

}