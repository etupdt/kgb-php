<?php

require_once 'models/ServiceEntityRepository.php';

class TypeMissionRepository extends ServiceEntityRepository {

  public function __construct($maxDepth) {

    $this->maxDepth = $maxDepth;

    parent::__construct(TypeMission::class);

  }

  public function find($id) {

    return parent::find($id);

  }

  public function findAll() { 

    return parent::findAll();

  }  

  public function insert(TypeMission $typeMission) { 

    $values[] = $typeMission->getTypeMission();

    $id_typeMission = $this->insertDatabase('typemission', [
      'typeMission' => $typeMission->getTypeMission(),
    ]);

  }  

  public function update(TypeMission $typeMission) { 

    $this->updateDatabase('typemission', $typeMission->getId(), [
      'typeMission' => $typeMission->getTypeMission(),
    ]);

  }  

  public function delete(TypeMission $typeMission) { 

    $this->deleteDatabase('typemission', [
      'id' => $typeMission->getId()
    ]);
    
  }  

}
