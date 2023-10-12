<?php

class Statut {

  private $id;
  private $statut;

  public function __construct(string $id = null, string $statut = null) {
    $this->id = $id;
    $this->statut = $statut;
  }

  public function __set($name, $value)
  {
      // empty
  }

  public function getId()  : string {
      return $this->id;
  }

  public function getStatut()  : string{
      return $this->statut;
  }

  public function setStatut(string  $statut) {
        $this->statut = $statut;
  }

  public static function find(string $id) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $find = 'SELECT * FROM statut WHERE id = ?';
    
    $pdoStatement = $pdo->prepare($find);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      return $pdoStatement->fetch();
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }  

  public static function findAll() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $findAll = 'SELECT id, statut FROM statut';
    
    $pdoStatement = $pdo->prepare($findAll);

    $specialities = [];

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      while($statut = $pdoStatement->fetch()) {
        $specialities[] = $statut;
      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    return $specialities;
    
  }  

  public function insertDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $insert = 'INSERT INTO statut (statut) VALUE (?)';
    
    $pdoStatement = $pdo->prepare($insert);

    $pdoStatement->bindValue(1, $this->statut, PDO::PARAM_STR);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }  

  public function updateDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $update = 'UPDATE statut SET statut = ? WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($update);

    $pdoStatement->bindValue(1, $this->statut, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  public static function deleteDatabase(int $id) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $delete = 'DELETE FROM statut WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

}
