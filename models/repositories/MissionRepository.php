<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/models/ServiceEntityRepository.php';

class MissionRepository extends ServiceEntityRepository {

  public function __construct($maxDepth) {
    
    $this->maxDepth = $maxDepth;

    parent::__construct(Mission::class);

  }
 
  public function find($id) {

    return parent::find($id);

  }

  public function findAll() { 

    return parent::findAll();

  }  

  public function insert(Mission $mission) { 

    $id_mission = $this->insertDatabase('mission', [
      'title' => $mission->getTitle(),
      'description' => $mission->getDescription(),
      'codeName' => $mission->getCodeName(),
      'begin' => $mission->getBegin(),
      'end' => $mission->getEnd(),
      'id_country' => $mission->getCountry()->getId(),
      'id_statut' => $mission->getStatut()->getId(),
      'id_typeMission' => $mission->getTypeMission()->getId(),
      'id_speciality' => $mission->getSpeciality()->getId(),
    ]);

    $this->deleteDatabase('mission_hideout', [
      'id_mission' => $id_mission
    ]);

    foreach ($mission->getHideouts() as $hideout) {
      $this->insertDatabase('mission_hideout', [
        'id_mission' => $id_mission,
        'id_hideout' => $hideout['id_hideout']
      ]);
    }

    $this->deleteDatabase('mission_actor_role', [
      'id_mission' => $id_mission
    ]);

    foreach ($mission->getActors_roles() as $actorRole) {
      $this->insertDatabase('mission_actor_role', [
        'id_mission' => $id_mission,
        'id_actor' => $actorRole['id_actor'],
        'id_role' => $actorRole['id_role'],
      ]);
    }

  }  

  public function update(Mission $mission) { 

    $this->updateDatabase('mission', $mission->getId(), [
      'title' => $mission->getTitle(),
      'description' => $mission->getDescription(),
      'codeName' => $mission->getCodeName(),
      'begin' => $mission->getBegin(),
      'end' => $mission->getEnd(),
      'id_country' => $mission->getCountry()->getId(),
      'id_statut' => $mission->getStatut()->getId(),
      'id_typeMission' => $mission->getTypeMission()->getId(),
      'id_speciality' => $mission->getSpeciality()->getId(),
    ]);

    $this->deleteDatabase('mission_hideout', [
      'id_mission' => $mission->getId()
    ]);

    foreach ($mission->getHideouts() as $hideout) {
      $this->insertDatabase('mission_hideout', [
        'id_mission' => $mission->getId(),
        'id_hideout' => $hideout['id_hideout']
      ]);
    }

    $this->deleteDatabase('mission_actor_role', [
      'id_mission' => $mission->getId()
    ]);

    foreach ($mission->getActors_roles() as $actorRole) {
      $this->insertDatabase('mission_actor_role', [
        'id_mission' => $mission->getId(),
        'id_actor' => $actorRole['id_actor'],
        'id_role' => $actorRole['id_role']
      ]);
    }

  }  

  public function delete(Mission $mission) { 

    $this->deleteDatabase('mission_hideout', [
      'id_mission' => $mission->getId()
    ]);
    
    $this->deleteDatabase('mission_actor_role', [
      'id_mission' => $mission->getId()
    ]);
    
    $this->deleteDatabase('mission', [
      'id' => $mission->getId()
    ]);
    
  }  

}
