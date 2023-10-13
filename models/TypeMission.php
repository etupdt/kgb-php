<?php

class TypeMission {

  private $isUpdated = false;
  private $isDeleted = false;

  private $id;
  private $typeMission;

  public function __construct(string $id = null, string $typeMission = null) {
    $this->id = $id;
    $this->typeMission = $typeMission;
  }

  public function __set($name, $value)
  {
      // empty
  }

  public function getId()  : string {
      return $this->id;
  }

  public function getTypeMission()  : string{
      return $this->typeMission;
  }

  public function setTypeMisson(string  $typeMission) {
        $this->typeMission = $typeMission;
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

    $find = 'SELECT * FROM typemission WHERE id = ?';
    
    $pdoStatement = $pdo->prepare($find);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      $typemission = $pdoStatement->fetch();
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    return $typemission;

  }  

  public static function findAll() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $findAll = 'SELECT id, typeMission FROM typemission';
    
    $pdoStatement = $pdo->prepare($findAll);

    $typemissions = [];

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      while($typeMission = $pdoStatement->fetch()) {

        $typemissions[] = $typeMission;

      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    return $typemissions;
    
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

    $insert = 'INSERT INTO typemission (typeMission) VALUE (?)';
    
    $pdoStatement = $pdo->prepare($insert);

    $pdoStatement->bindValue(1, $this->typeMission, PDO::PARAM_STR);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }  

  public function updateDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $update = 'UPDATE typemission SET typeMission = ? WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($update);

    $pdoStatement->bindValue(1, $this->typeMission, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  public function deleteDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $delete = 'DELETE FROM typemission WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

}
