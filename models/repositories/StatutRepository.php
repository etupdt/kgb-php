<?php

require_once 'models/ServiceEntityRepository.php';

class StatutRepository extends ServiceEntityRepository {

  public function __construct($depth) {

    parent::__construct(Statut::class);

    $this->depth = $depth;

  }

  public function find($id) {

    return parent::find($id);

  }

  public function findAll() { 

    return parent::findAll();

  }  

  public function insertDatabase() { 

    $this->repositoryInsert('Statut', $this);

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $this->insertHideoutsDatabase($pdo, $this);
    $this->insertActorsRolesDatabase($pdo, $this);

  }  

  public function updateDatabase() { 

    $this->repositoryUpdate('Statut', $this);

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

    $this->repositoryDelete('Statut', $this);

    $this->repositoryDelete('Statut', $this);

  }

  public static function getHideoutsDatabase($id_statut) { 
  
    $pdo = new PDO(Database::$host, Database::$username, Database::$password);
  
    $findHideout = "SELECT id_hideout FROM statut_hideout WHERE id_statut = ?";
  
    $pdoStatement = $pdo->prepare($findHideout);
  
    $pdoStatement->bindValue(1, $id_statut, PDO::PARAM_INT);
  
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

  private function deleteHideoutsDatabase($pdo, $statut) {

    $delete = 'DELETE FROM statut_hideout WHERE id_statut = ? ';

    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $statut->getId(), PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  private function insertHideoutsDatabase($pdo, $statut) {

    foreach ($statut->getHideouts() as $hideout) {

      $insert = 'INSERT statut_hideout (id_statut, id_hideout) VALUE (?, ?)';

      $pdoStatement = $pdo->prepare($insert);

      $pdoStatement->bindValue(1, $statut->getId(), PDO::PARAM_INT);
      $pdoStatement->bindValue(2, $hideout, PDO::PARAM_INT);

      if (!$pdoStatement->execute()) {  
        print_r($pdoStatement->errorInfo());  // sensible à modifier
      }  

    }

  }

  public static function getActorsRolesDatabase($id_statut) { 
  
    $pdo = new PDO(Database::$host, Database::$username, Database::$password);
  
    $findActor = "SELECT id_actor, id_role FROM statut_actor_role WHERE id_statut = ?";
  
    $pdoStatement = $pdo->prepare($findActor);
  
    $pdoStatement->bindValue(1, $id_statut, PDO::PARAM_INT);
  
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

  private function deleteActorsRolesDatabase($pdo, $statut) {

    $delete = 'DELETE FROM statut_actor_role WHERE id_statut = ? ';

    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $statut->getId(), PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  private function insertActorsRolesDatabase($pdo, $statut) {

    foreach ($statut->getActorsRoles() as $actor_role) {

      $insert = 'INSERT statut_actor_role (id_statut, id_actor, id_role) VALUE (?, ?, ?)';

      $pdoStatement = $pdo->prepare($insert);

      $pdoStatement->bindValue(1, $statut->getId(), PDO::PARAM_INT);
      $pdoStatement->bindValue(2, $actor_role['id_actor'], PDO::PARAM_INT);
      $pdoStatement->bindValue(3, $actor_role['id_role'], PDO::PARAM_INT);

      if (!$pdoStatement->execute()) {  
        print_r($pdoStatement->errorInfo());  // sensible à modifier
      }  

    }

  }

}
