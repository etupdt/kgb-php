<?php

require_once 'models/ServiceEntityRepository.php';

class HideoutRepository extends ServiceEntityRepository {

  public function __construct($maxDepth) {

    $this->maxDepth = $maxDepth;

    parent::__construct(Hideout::class);

  }

  public function find($id) {

    return parent::find($id);

  }

  public function findAll() { 

    return parent::findAll();

  }  

  public function insert(Hideout $hideout) { 

    $id_hideout = $this->insertDatabase('hideout', [
      'code' => $hideout->getCode(),
      'address' => $hideout->getAddress(),
      'type' => $hideout->getType(),
      'id_country' => $hideout->getCountry()->getId()
    ]);

    $this->deleteDatabase('mission_hideout', [
      'id_hideout' => $id_hideout
    ]);

    foreach ($hideout->getMissions() as $mission) {
      $this->insertDatabase('mission_hideout', [
        'id_mission' => $mission['id_mission'],
        'id_hideout' => $id_hideout
      ]);
    }

  }  

  public function update(Hideout $hideout) { 

    $this->updateDatabase('hideout', $hideout->getId(), [
      'code' => $hideout->getCode(),
      'address' => $hideout->getAddress(),
      'type' => $hideout->gettype(),
      'id_country' => $hideout->getCountry()->getId(),
    ]);

    $this->deleteDatabase('mission_hideout', [
      'id_hideout' => $hideout->getId()
    ]);

    foreach ($hideout->getMissions() as $mission) {
      $this->insertDatabase('mission_hideout', [
        'id_mission' => $mission['id_mission'],
        'id_hideout' => $hideout->getId()
      ]);
    }

  }  

  public function delete(Hideout $hideout) { 

    $this->deleteDatabase('mission_hideout', [
      'id_hideout' => $hideout->getId()
    ]);
    
    $this->deleteDatabase('hideout', [
      'id' => $hideout->getId()
    ]);
    
  }  

}
