<?php

require_once 'models/ServiceEntityRepository.php';

class ActorRepository extends ServiceEntityRepository {

  public function __construct($maxDepth) {

    $this->maxDepth = $maxDepth;
    
    parent::__construct(Actor::class);

  }

  public function find($id) {

    return parent::find($id);

  }

  public function findAll() { 

    return parent::findAll();

  }  

  public function insert(Actor $actor) { 

    $id_person = $this->insertDatabase('person', [
      'firstName' => $actor->getFirstname(),
      'lastName' => $actor->getLastname(),
    ]);

    $id_actor = $this->insertDatabase('actor', [
      'id_person' => $id_person,
      'id' => $id_person,
      'birthdate' => $actor->getBirthdate(),
      'identificationCode' => $actor->getIdentificationCode(),
      'id_country' => $actor->getCountry()->getId()
    ]);

    $this->deleteDatabase('actor_speciality', [
      'id_actor' => $id_person
    ]);

    foreach ($actor->getSpecialities() as $speciality) {
      $this->insertDatabase('actor_speciality', [
        'id_actor' => $id_person,
        'id_speciality' => $speciality->getId()
      ]);
    }

  }  

  public function update(Actor $actor) { 

    $this->updateDatabase('person', $actor->getId(), [
      'firstName' => $actor->getFirstname(),
      'lastName' => $actor->getLastname(),
    ]);

    $this->updateDatabase('actor', $actor->getId(), [
      'id_person' => $actor->getId(),
      'id' => $actor->getId(),
      'birthdate' => $actor->getBirthdate(),
      'identificationCode' => $actor->getIdentificationCode(),
      'id_country' => $actor->getCountry()->getId(),
    ]);

    $this->deleteDatabase('actor_speciality', [
      'id_actor' => $actor->getId()
    ]);

    foreach ($actor->getSpecialities() as $speciality) {
      $this->insertDatabase('actor_speciality', [
        'id_actor' => $actor->getId(),
        'id_speciality' => $speciality['id_speciality']
      ]);
    }

  }  

  public function delete(Actor $actor) { 

    $this->deleteDatabase('actor_speciality', [
      'id_actor' => $actor->getId()
    ]);
    
    $this->deleteDatabase('actor', [
      'id' => $actor->getId()
    ]);
    
    $this->deleteDatabase('person', [
      'id' => $actor->getId()
    ]);

  }  

}
