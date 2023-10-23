<?php

class Speciality {

  private $isUpdated = false;
  private $isDeleted = false;

  private $id;
  private $name;

  private $actors;

  public function __construct(string $id = null, string $name = null) {
    $this->id = $id;
    $this->name = $name;
  }

  public function __set($name, $value)
  {
      // empty
  }

  public function getId()  : string {
      return $this->id;
  }

  public function getActors() { 
    return $this->actors;
  }  

  public function getName()  : string{
      return $this->name;
  }

  public function setName(string  $name) {
    $this->name = $name;
  }

  public function setActors(array  $actors) {
    $this->actors = $actors;
  }

  public function removeSpecialities() { 
    $this->specialities = [];
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

    $find = 'SELECT * FROM speciality WHERE id = ?';
    
    $pdoStatement = $pdo->prepare($find);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      $speciality = $pdoStatement->fetch();
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    $speciality->setActors(Speciality::getActorsDatabase($speciality->getId()));

    return $speciality;

  }  

  public static function findAll() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $findAll = 'SELECT id, name FROM speciality';
    
    $pdoStatement = $pdo->prepare($findAll);

    $specialities = [];

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      while($speciality = $pdoStatement->fetch()) {
        
        $speciality->setActors(Speciality::getActorsDatabase($speciality->getId()));

        $specialities[] = $speciality;

      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    return $specialities;
    
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

    $insert = 'INSERT INTO speciality (name) VALUE (?)';
    
    $pdoStatement = $pdo->prepare($insert);

    $pdoStatement->bindValue(1, $this->name, PDO::PARAM_STR);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }  

  public function updateDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $update = 'UPDATE speciality SET name = ? WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($update);

    $pdoStatement->bindValue(1, $this->name, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  public function deleteDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $this->deleteActorsDatabase($pdo);

    $delete = 'DELETE FROM speciality WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  public static function getActorsDatabase($id_speciality) { 
  
    $pdo = new PDO(Database::$host, Database::$username, Database::$password);
  
    $findActor = "SELECT id_actor FROM actor_speciality WHERE id_speciality = ?";
  
    $pdoStatement = $pdo->prepare($findActor);
  
    $pdoStatement->bindValue(1, $id_speciality, PDO::PARAM_INT);
  
    $actors = [];
  
    if ($pdoStatement->execute()) {  
      while($actor = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
        $actors[] = $actor['id_actor'];
      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  
  
    return $actors;
  
  }  

  private function deleteActorsDatabase($pdo) {

    $delete = 'DELETE FROM actor_speciality WHERE id_speciality = ? ';

    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

}
