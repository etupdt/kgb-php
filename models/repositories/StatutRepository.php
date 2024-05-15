<?php

require_once $_SERVER['DOCUMENT_ROOT'].'models/ServiceEntityRepository.php';

class StatutRepository extends ServiceEntityRepository {

  public function __construct($maxDepth) {

    $this->maxDepth = $maxDepth;

    parent::__construct(Statut::class);

  }

  public function find($id) {

    return parent::find($id);

  }

  public function findAll() { 

    return parent::findAll();

  }  

  public function insert(Statut $statut) { 

    $id_statut = $this->insertDatabase('statut', [
      'statut' => $statut->getStatut(),
    ]);

  }  

  public function update(Statut $statut) { 

    $this->updateDatabase('statut', $statut->getId(), [
      'statut' => $statut->getStatut(),
    ]);

  }  

  public function delete(Statut $statut) { 

    $this->deleteDatabase('statut', [
      'id' => $statut->getId()
    ]);
    
  }  

}
