<?php

class Speciality {

  private $id;
  private $name;

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

  public function getName()  : string{
      return $this->name;
  }

  public function setName(string  $name) {
        $this->name = $name;
  }

  public static function find(string $id) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $find = 'SELECT * FROM speciality WHERE id = ?';
    
    $pdoStatement = $pdo->prepare($find);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      return $pdoStatement->fetch();
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }  

  public static function findBy(array $conditions) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $find = 'SELECT * FROM speciality WHERE id = ?';

    foreach ($conditions as $condition) {
      $findBy = $findBy.' '.$condition->boolOp.' '.$condition->field.' '.$condition>op.' ?';
    }

    $pdoStatement = $pdo->prepare($findBy);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    foreach ($conditions as $index=>$condition) {
      $pdoStatement->bindValue(2 + $index, $condition.value, $condition.type);
    }

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      return $pdoStatement->fetch();
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }  

  public static function findAll() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $findAll = 'SELECT id, name FROM speciality';
    
    $pdoStatement = $pdo->prepare($findAll);

    $specialities = [];

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      while($speciality = $pdoStatement->fetch()) {
        $specialities[] = $speciality;
      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    return $specialities;
    
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

  public static function deleteDatabase(int $id) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $delete = 'DELETE FROM speciality WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

}
