<?php

require_once 'models/ServiceEntityRepository.php';

class CountryRepository extends ServiceEntityRepository {

  public function __construct($maxDepth) {

    $this->maxDepth = $maxDepth;

    parent::__construct(Country::class);

  }

  public function find($id) {

    return parent::find($id);

  }

  public function findAll() { 

    return parent::findAll();

  }  

  public function insert(Country $country) { 

    $values[] = $country->getName();
    $values[] = $country->getNationality();

    $id_country = $this->insertDatabase('country', [
      'name' => $country->getName(),
      'nationality' => $country->getNationality(),
    ]);

  }  

  public function update(Country $country) { 

    $this->updateDatabase('country', $country->getId(), [
      'name' => $country->getName(),
      'nationality' => $country->getNationality(),
    ]);

  }  

  public function delete(Country $country) { 

    $this->deleteDatabase('country', [
      'id' => $country->getId()
    ]);
    
  }  

}
