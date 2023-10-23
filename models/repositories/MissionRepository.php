<?php

require 'models/ServiceEntityRepository.php';

class MissionRepository extends ServiceEntityRepository {

  public static function findAll() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $findAll = "SELECT id, title, description, codeName, begin, end, 
    id_country, id_statut, id_typeMission, id_speciality
    FROM mission";
    
    $pdoStatement = $pdo->prepare($findAll);

    $missions = [];

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Mission');
      while($mission = $pdoStatement->fetch()) {
        
        $mission->setHideouts(Mission::getHideoutsDatabase($mission->getId()));
        $mission->setActorsRoles(Mission::getActorsRolesDatabase($mission->getId()));

        $missions[] = $mission;

      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    return $missions;
    
  }  

  public function insertDatabase(Mission $mission) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $insert = 'INSERT INTO mission (title, description, codeName, begin, end, 
    id_country, id_statut, id_typeMission, id_speciality) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);';
    
    $pdoStatement = $pdo->prepare($insert);

    $pdoStatement->bindValue(1, $mission->getTitle(), PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $mission->getDescription(), PDO::PARAM_STR);
    $pdoStatement->bindValue(3, $mission->getCodeName(), PDO::PARAM_STR);
    $pdoStatement->bindValue(4, $mission->getBegin(), PDO::PARAM_STR);
    $pdoStatement->bindValue(5, $mission->getEnd(), PDO::PARAM_STR);
    $pdoStatement->bindValue(6, $mission->getId_country(), PDO::PARAM_INT);
    $pdoStatement->bindValue(7, $mission->getId_statut(), PDO::PARAM_INT);
    $pdoStatement->bindValue(8, $mission->getId_typeMission(), PDO::PARAM_INT);
    $pdoStatement->bindValue(9, $mission->getId_speciality(), PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  
    $mission->setId($pdo->lastInsertId());

    $this->insertHideoutsDatabase($mission, $pdo);
    $this->insertActorsRolesDatabase($mission, $pdo);

  }  

  public function updateDatabase(Mission $mission) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $update = 'UPDATE mission SET title = ?, description = ?, codeName = ?, begin = ?, end = ?, 
    id_country = ?, id_statut = ?, id_typeMission = ?, id_speciality = ? 
    WHERE id = ?';
    
    $pdoStatement = $pdo->prepare($update);

    $pdoStatement->bindValue(1, $mission->getTitle(), PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $mission->getDescription(), PDO::PARAM_STR);
    $pdoStatement->bindValue(3, $mission->getCodeName(), PDO::PARAM_STR);
    $pdoStatement->bindValue(4, $mission->getBegin(), PDO::PARAM_STR);
    $pdoStatement->bindValue(5, $mission->getEnd(), PDO::PARAM_STR);
    $pdoStatement->bindValue(6, $mission->getId_country(), PDO::PARAM_INT);
    $pdoStatement->bindValue(7, $mission->getId_statut(), PDO::PARAM_INT);
    $pdoStatement->bindValue(8, $mission->getId_typeMission(), PDO::PARAM_INT);
    $pdoStatement->bindValue(9, $mission->getId_speciality(), PDO::PARAM_INT);
    $pdoStatement->bindValue(10, $mission->getId(), PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    $this->deleteHideoutsDatabase($mission, $pdo);
    $this->insertHideoutsDatabase($mission, $pdo);
    
    $this->deleteActorsRolesDatabase($mission, $pdo);
    $this->insertActorsRolesDatabase($mission, $pdo);
    
  }

  public function deleteDatabase(Mission $mission) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $this->deleteHideoutsDatabase($mission, $pdo);
    $this->deleteActorsRolesDatabase($mission, $pdo);

    $delete = 'DELETE FROM mission WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $mission->getId(), PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

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

  private function deleteHideoutsDatabase(Mission $mission, $pdo) {

    $delete = 'DELETE FROM mission_hideout WHERE id_mission = ? ';

    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $mission->getId(), PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  private function insertHideoutsDatabase(Mission $mission, $pdo) {

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
