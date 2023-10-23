<?php

require 'models/ServiceEntityRepository.php';

class MissionRepository extends ServiceEntityRepository {

  public static function find($id) {

    $mission = ServiceEntityRepository::repositoryFind('Mission', $id);

    $mission->setHideouts(Mission::getHideoutsDatabase($mission->getId()));
    $mission->setActorsRoles(Mission::getActorsRolesDatabase($mission->getId()));

    return $mission;

  }

  public static function findAll() { 

    $missions = ServiceEntityRepository::repositoryFindAll('Mission', Mission::getClassFields());

    foreach ($missions as $mission) {
      $mission->setHideouts(Mission::getHideoutsDatabase($mission->getId()));
      $mission->setActorsRoles(Mission::getActorsRolesDatabase($mission->getId()));
    }

    return $missions;
    
  }  

  public function insertDatabase() { 

    $this->repositoryInsert('Mission', $this);

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $this->insertHideoutsDatabase($pdo, $this);
    $this->insertActorsRolesDatabase($pdo, $this);

  }  

  public function updateDatabase() { 

    $this->repositoryUpdate('Mission', $this);

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

    $this->repositoryDelete('Mission', $this);

    $this->repositoryDelete('Mission', $this);

  }

  public static function getHideoutsDatabase($id_mission) { 
  
    $pdo = new PDO(Database::$host, Database::$username, Database::$password);
  
    $findHideout = "SELECT id_hideout FROM mission_hideout WHERE id_mission = ?";
  
    $pdoStatement = $pdo->prepare($findHideout);
  
    $pdoStatement->bindValue(1, $id_mission, PDO::PARAM_INT);
  
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

  private function deleteHideoutsDatabase($pdo, $mission) {

    $delete = 'DELETE FROM mission_hideout WHERE id_mission = ? ';

    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $mission->getId(), PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  private function insertHideoutsDatabase($pdo, $mission) {

    foreach ($mission->getHideouts() as $hideout) {

      $insert = 'INSERT mission_hideout (id_mission, id_hideout) VALUE (?, ?)';

      $pdoStatement = $pdo->prepare($insert);

      $pdoStatement->bindValue(1, $mission->getId(), PDO::PARAM_INT);
      $pdoStatement->bindValue(2, $hideout, PDO::PARAM_INT);

      if (!$pdoStatement->execute()) {  
        print_r($pdoStatement->errorInfo());  // sensible à modifier
      }  

    }

  }

  public static function getActorsRolesDatabase($id_mission) { 
  
    $pdo = new PDO(Database::$host, Database::$username, Database::$password);
  
    $findActor = "SELECT id_actor, id_role FROM mission_actor_role WHERE id_mission = ?";
  
    $pdoStatement = $pdo->prepare($findActor);
  
    $pdoStatement->bindValue(1, $id_mission, PDO::PARAM_INT);
  
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

  private function deleteActorsRolesDatabase($pdo, $mission) {

    $delete = 'DELETE FROM mission_actor_role WHERE id_mission = ? ';

    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $mission->getId(), PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  private function insertActorsRolesDatabase($pdo, $mission) {

    foreach ($mission->getActorsRoles() as $actor_role) {

      $insert = 'INSERT mission_actor_role (id_mission, id_actor, id_role) VALUE (?, ?, ?)';

      $pdoStatement = $pdo->prepare($insert);

      $pdoStatement->bindValue(1, $mission->getId(), PDO::PARAM_INT);
      $pdoStatement->bindValue(2, $actor_role['id_actor'], PDO::PARAM_INT);
      $pdoStatement->bindValue(3, $actor_role['id_role'], PDO::PARAM_INT);

      if (!$pdoStatement->execute()) {  
        print_r($pdoStatement->errorInfo());  // sensible à modifier
      }  

    }

  }

}
