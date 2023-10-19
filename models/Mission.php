<?php

class Mission {

  private $isUpdated = false;
  private $isDeleted = false;

  private $id;
  private $title;
  private $description;
  private $codeName;
  private $begin;
  private $end;

  private $id_country;
  private $id_statut;
  private $id_typeMission;
  private $id_speciality;

  private $hideouts;
  private $actors_roles;

  public function __construct(
    string  $id = null, 
    string  $title = null, 
    string $description = null, 
    string $codeName = null, 
    string $begin = null, 
    string $end = null, 

    string $id_country = null,
    string $id_statut = null,
    string $id_typeMission = null,
    string $id_speciality = null,

    array $hideouts = null,
    array $actors_roles = null

    ) {
      $this->id = $id;
      $this->title = $title;
      $this->description = $description;
      $this->codeName = $codeName;
      $this->begin = $begin;
      $this->end = $end;

      $this->id_country = $id_country;
      $this->id_statut = $id_statut;
      $this->id_typeMission = $id_typeMission;
      $this->id_speciality = $id_speciality;

      $this->hideouts = $hideouts;
      $this->actors_roles = $actors_roles;

    }

  public function getId()  : string {
    return $this->id;
  }

  public function getTitle()  : string{
    return $this->title;
  }

  public function getDescription()  : string {
    return $this->description;
  }

  public function getCodeName()  : string {
    return $this->codeName;
  }

  public function getBegin()  : string {
    return $this->begin;
  }

  public function getEnd()  : string {
    return $this->end;
  }

  public function getId_country()  : string {
    return $this->id_country;
  }

  public function getId_statut()  : string {
    return $this->id_statut;
  }

  public function getId_typeMission()  : string {
    return $this->id_typeMission;
  }

  public function getId_speciality()  : string {
    return $this->id_speciality;
  }

  public function getHideouts() { 
    return $this->hideouts;
  }  

  public function getActorsRoles() { 
    return $this->actors_roles;
  }  

  public function setTitle(string  $title) {
    $this->title = $title;
  }

  public function setDescription(string  $description) {
    $this->description = $description;
  }

  public function setCodeName(string  $codeName) {
    $this->codeName = $codeName;
  }

  public function setBegin(string  $begin) {
    $this->begin = $begin;
  }

  public function setEnd(string  $end) {
    $this->isUpdated = true;
    $this->end = $end;
  }

  public function setId_country(string  $id_country) {
    $this->id_country = $id_country;
  }

  public function setId_statut(string  $id_statut) {
    $this->id_statut = $id_statut;
  }

  public function setId_typeMission(string  $id_typeMission) {
    $this->id_typeMission = $id_typeMission;
  }

  public function setId_speciality(string  $id_speciality) {
    $this->id_speciality = $id_speciality;
  }

  public function setHideouts(array $hideouts) {
    $this->hideouts = $hideouts;
  }

  public function addHideout(Hideout $hideout) { 
    $this->hideouts[] = $hideout;
  }  

  public function removeHideouts() { 
    $this->hideouts = [];
  }  

  public function setActorsRoles(array $actors_roles) {
    $this->actors_roles = $actors_roles;
  }

  public function addActorsRoles($actors_roles) { 
    $this->actors_roles[] = $actors_roles;
  }  

  public function removeActorsRoles() { 
    $this->actors_roles = [];
  }  

  public function persist() {

    if ($this->isUpdated) {
      if ($this->isDeleted) {
        $this->deleteDatabase();
      } elseif ($this->id === "0") {
        $this->insertDatabase();
      } else {
        $this->updateDatabase();
      }
    }

    $this->isUpdated = false;
    $this->isDeleted = false;

  }

  public static function find(string $id) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);
    
    $find = "SELECT id, title, description, codeName, begin, end, 
    id_country, id_statut, id_typeMission, id_speciality 
    FROM mission WHERE id = ?;";

    $pdoStatement = $pdo->prepare($find);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if ($pdoStatement->execute()) {
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      $mission = $pdoStatement->fetch();
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }

    $mission->setHideouts(Mission::getHideoutsDatabase($mission->getId()));
    $mission->setActorsRoles(Mission::getActorsRolesDatabase($mission->getId()));

    return $mission;

  }    

  public static function findAll() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $findAll = "SELECT id, title, description, codeName, begin, end, 
    id_country, id_statut, id_typeMission, id_speciality
    FROM mission";
    
    $pdoStatement = $pdo->prepare($findAll);

    $missions = [];

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
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

  public function update () {
    $this->isUpdated = true;
  }

  public function insert () {
    $this->isUpdated = true;
  }

  public function delete () {
    $this->isUpdated = true;
    $this->isDeleted = true;
  }

  public function insertDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $insert = 'INSERT INTO mission (title, description, codeName, begin, end, 
    id_country, id_statut, id_typeMission, id_speciality) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);';
    
    $pdoStatement = $pdo->prepare($insert);

    $pdoStatement->bindValue(1, $this->title, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->description, PDO::PARAM_STR);
    $pdoStatement->bindValue(3, $this->codeName, PDO::PARAM_STR);
    $pdoStatement->bindValue(4, $this->begin, PDO::PARAM_STR);
    $pdoStatement->bindValue(5, $this->end, PDO::PARAM_STR);
    $pdoStatement->bindValue(6, $this->id_country, PDO::PARAM_INT);
    $pdoStatement->bindValue(7, $this->id_statut, PDO::PARAM_INT);
    $pdoStatement->bindValue(8, $this->id_typeMission, PDO::PARAM_INT);
    $pdoStatement->bindValue(9, $this->id_speciality, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  
    $this->id = $pdo->lastInsertId();

    $this->insertHideoutsDatabase($pdo);
    $this->insertActorsRolesDatabase($pdo);

  }  

  public function updateDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $update = 'UPDATE mission SET title = ?, description = ?, codeName = ?, begin = ?, end = ?, 
    id_country = ?, id_statut = ?, id_typeMission = ?, id_speciality = ? 
    WHERE id = ?';
    
    $pdoStatement = $pdo->prepare($update);

    $pdoStatement->bindValue(1, $this->title, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->description, PDO::PARAM_STR);
    $pdoStatement->bindValue(3, $this->codeName, PDO::PARAM_STR);
    $pdoStatement->bindValue(4, $this->begin, PDO::PARAM_STR);
    $pdoStatement->bindValue(5, $this->end, PDO::PARAM_STR);
    $pdoStatement->bindValue(6, $this->id_country, PDO::PARAM_INT);
    $pdoStatement->bindValue(7, $this->id_statut, PDO::PARAM_INT);
    $pdoStatement->bindValue(8, $this->id_typeMission, PDO::PARAM_INT);
    $pdoStatement->bindValue(9, $this->id_speciality, PDO::PARAM_INT);
    $pdoStatement->bindValue(10, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    $this->deleteHideoutsDatabase($pdo);
    $this->insertHideoutsDatabase($pdo);
    
    $this->deleteActorsRolesDatabase($pdo);
    $this->insertActorsRolesDatabase($pdo);
    
  }

  public function deleteDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $this->deleteHideoutsDatabase($pdo);
    $this->deleteActorsRolesDatabase($pdo);

    $delete = 'DELETE FROM mission WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $this->id, PDO::PARAM_INT);

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

  private function deleteHideoutsDatabase($pdo) {

    $delete = 'DELETE FROM mission_hideout WHERE id_mission = ? ';

    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  private function insertHideoutsDatabase($pdo) {

    foreach ($this->hideouts as $hideout) {

      $insert = 'INSERT mission_hideout (id_mission, id_hideout) VALUE (?, ?)';

      $pdoStatement = $pdo->prepare($insert);

      $pdoStatement->bindValue(1, $this->id, PDO::PARAM_INT);
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

  private function deleteActorsRolesDatabase($pdo) {

    $delete = 'DELETE FROM mission_actor_role WHERE id_mission = ? ';

    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  private function insertActorsRolesDatabase($pdo) {

    foreach ($this->actors_roles as $actor_role) {

      $insert = 'INSERT mission_actor_role (id_mission, id_actor, id_role) VALUE (?, ?, ?)';

      $pdoStatement = $pdo->prepare($insert);

      $pdoStatement->bindValue(1, $this->id, PDO::PARAM_INT);
      $pdoStatement->bindValue(2, $actor_role['id_actor'], PDO::PARAM_INT);
      $pdoStatement->bindValue(3, $actor_role['id_role'], PDO::PARAM_INT);

      if (!$pdoStatement->execute()) {  
        print_r($pdoStatement->errorInfo());  // sensible à modifier
      }  

    }

  }

}
