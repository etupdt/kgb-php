<?php

require_once $_SERVER['DOCUMENT_ROOT'].'models/ServiceEntityRepository.php';

class SpecialityRepository extends ServiceEntityRepository {

  public function __construct($maxDepth) {
    
    $this->maxDepth = $maxDepth;

    parent::__construct(Speciality::class);

  }

  public function find($id) {

    return parent::find($id);

  }

  public function findAll() { 

    return parent::findAll();

  }  

  public function insert(Speciality $speciality) { 

    $id_speciality = $this->insertDatabase('speciality', [
      'name' => $speciality->getName(),
    ]);

    $this->deleteDatabase('actor_speciality', [
      'id_speciality' => $id_speciality
    ]);

    foreach ($speciality->getActors() as $actor) {
      $this->insertDatabase('actor_speciality', [
        'id_actor' => $actor->getId(),
        'id_speciality' => $id_speciality
      ]);
    }

  }  

  public function update(Speciality $speciality) { 

    $this->updateDatabase('speciality', $speciality->getId(), [
      'name' => $speciality->getName(),
    ]);

    $this->deleteDatabase('actor_speciality', [
      'id_speciality' => $speciality->getId()
    ]);

    foreach ($speciality->getActors() as $actor) {
      $this->insertDatabase('actor_speciality', [
        'id_actor' => $actor['id_actor'],
        'id_speciality' => $speciality->getId()
      ]);
    }

  }  

  public function delete(Speciality $speciality) { 

    $this->deleteDatabase('actor_speciality', [
      'id_speciality' => $speciality->getId()
    ]);
    
    $this->deleteDatabase('speciality', [
      'id' => $speciality->getId()
    ]);
    
  }  

}
