<?php

require_once 'models/ServiceEntityRepository.php';

class RoleRepository extends ServiceEntityRepository {

  public function __construct($maxDepth) {

    $this->maxDepth = $maxDepth;

    parent::__construct(Role::class);

  }

  public function find($id) {

    return parent::find($id);

  }

  public function findAll() { 

    return parent::findAll();

  }  

  public function insert(Role $role) { 

    $values[] = $role->getRole();

    $id_role = $this->insertDatabase('role', [
      'role' => $role->getRole(),
    ]);

  }  

  public function update(Role $role) { 

    $this->updateDatabase('role', $role->getId(), [
      'role' => $role->getRole(),
    ]);

  }  

  public function delete(Role $role) { 

    $this->deleteDatabase('role', [
      'id' => $role->getId()
    ]);
    
  }  

}
