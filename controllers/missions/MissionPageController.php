<?php

require_once $_SERVER['DOCUMENT_ROOT'].'models/entities/Mission.php';

class MissionPageController {

  public function index() { 

    $script = false;

    $roleRepository = new RoleRepository(1);
    $roles = $roleRepository->findAll();
  
    $nameMenu = "Mission";
    $path = BASE_URL."/mission";

    $missionRepository = new MissionRepository(2);

    $mission = $this->getRow($missionRepository->find($_GET['id']), $roles);
    require_once $_SERVER['DOCUMENT_ROOT'].'views/header.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'views/missions/missionPage.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'views/footer.php';

  }

  private function getRow (Mission $mission, $roles): array 
  {

    $row = [
        'id' => $mission->getId(),
        'title' => $mission->getTitle(),
        'description' => $mission->getDescription(),
        'codeName' => $mission->getCodeName(),
        'begin' => $mission->getBegin(),
        'end' => $mission->getEnd(),
        'country' => $mission->getCountry()->getName(),
        'statut' => $mission->getStatut()->getStatut(),
        'typeMission' => $mission->getTypeMission()->getTypeMission(),
        'speciality' => $mission->getSpeciality()->getName(),
        'hideouts' => $mission->getHideouts(),
    ];

    $actorsRoles = $mission->getActors_roles();

    foreach ($roles as $role) {

        $actors = [];

        foreach($actorsRoles as $actorRole) {
          if ($actorRole['role']->getId() == $role->getId()) {
            $actorWithCountry['firstname'] = $actorRole['actor']->getFirstname();
            $actorWithCountry['lastname'] = $actorRole['actor']->getLastname();
            $actorWithCountry['birthdate'] = $actorRole['actor']->getBirthdate();
            $actorWithCountry['identificationCode'] = $actorRole['actor']->getIdentificationCode();
            $actorWithCountry['country'] = $actorRole['actor']->getCountry()->getName();
            $actors[] = $actorRole['actor'];
          }
        }

        $row[str_replace(' ', '_', $role->getRole())] = $actors;

    }
    
    return $row;

  }

}