<?php

require_once 'models/ServiceEntityRepository.php';

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

  public function insertDatabase() { 

    $this->repositoryInsert('Speciality', $this);

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $this->insertHideoutsDatabase($pdo, $this);
    $this->insertActorsRolesDatabase($pdo, $this);

  }  

  public function updateDatabase() { 

    $this->repositoryUpdate('Speciality', $this);

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $this->deleteHideoutsDatabase($pdo, $this);
    $this->insertHideoutsDatabase($pdo, $this);
    
    $this->deleteActorsRolesDatabase($pdo, $this);
    $this->insertActorsRolesDatabase($pdo, $this);
    
  }

  public function deleteDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $this->deleteHideoutsDatabase($pdo, $this);
    $this->deleteActorsRolesDatabase($pdo, $this);

    $this->repositoryDelete('Speciality', $this);

    $this->repositoryDelete('Speciality', $this);

  }

  public static function getHideoutsDatabase($id_speciality) { 
  
    $pdo = new PDO(Database::$host, Database::$username, Database::$password);
  
    $findHideout = "SELECT id_hideout FROM speciality_hideout WHERE id_speciality = ?";
  
    $pdoStatement = $pdo->prepare($findHideout);
  
    $pdoStatement->bindValue(1, $id_speciality, PDO::PARAM_INT);
  
    $hideouts = [];
  
    if ($pdoStatement->execute()) {  
      while($hideout = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
        $hideouts[] = $hideout['id_hideout'];
      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  
  
    return $hideouts;
  
  }  

  private function deleteHideoutsDatabase($pdo, $speciality) {

    $delete = 'DELETE FROM speciality_hideout WHERE id_speciality = ? ';

    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $speciality->getId(), PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  private function insertHideoutsDatabase($pdo, $speciality) {

    foreach ($speciality->getHideouts() as $hideout) {

      $insert = 'INSERT speciality_hideout (id_speciality, id_hideout) VALUE (?, ?)';

      $pdoStatement = $pdo->prepare($insert);

      $pdoStatement->bindValue(1, $speciality->getId(), PDO::PARAM_INT);
      $pdoStatement->bindValue(2, $hideout, PDO::PARAM_INT);

      if (!$pdoStatement->execute()) {  
        print_r($pdoStatement->errorInfo());  // sensible à modifier
      }  

    }

  }

  public static function getActorsRolesDatabase($id_speciality) { 
  
    $pdo = new PDO(Database::$host, Database::$username, Database::$password);
  
    $findActor = "SELECT id_actor, id_role FROM speciality_actor_role WHERE id_speciality = ?";
  
    $pdoStatement = $pdo->prepare($findActor);
  
    $pdoStatement->bindValue(1, $id_speciality, PDO::PARAM_INT);
  
    $actors_roles = [];
  
    if ($pdoStatement->execute()) {  
      while($actor_role = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
        $actors_roles[] = [
          'id_actor' => $actor_role['id_actor'],
          'id_role' => $actor_role['id_role']
        ];
      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  
  
    return $actors_roles;
  
  }  

  private function deleteActorsRolesDatabase($pdo, $speciality) {

    $delete = 'DELETE FROM speciality_actor_role WHERE id_speciality = ? ';

    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $speciality->getId(), PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  private function insertActorsRolesDatabase($pdo, $speciality) {

    foreach ($speciality->getActorsRoles() as $actor_role) {

      $insert = 'INSERT speciality_actor_role (id_speciality, id_actor, id_role) VALUE (?, ?, ?)';

      $pdoStatement = $pdo->prepare($insert);

      $pdoStatement->bindValue(1, $speciality->getId(), PDO::PARAM_INT);
      $pdoStatement->bindValue(2, $actor_role['id_actor'], PDO::PARAM_INT);
      $pdoStatement->bindValue(3, $actor_role['id_role'], PDO::PARAM_INT);

      if (!$pdoStatement->execute()) {  
        print_r($pdoStatement->errorInfo());  // sensible à modifier
      }  

    }

  }

}
