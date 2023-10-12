<?php

class Hideout {

  private $id;
  private $code;
  private $address;
  private $type;
  private $id_country;

  public function __construct(string  $id = null, string  $code = null, string $address = null, string $type = null, string $id_country = null) {
      $this->id = $id;
      $this->code = $code;
      $this->address = $address;
      $this->type = $type;
      $this->id_country = $id_country;
  }

  public function getId()  : string {
      return $this->id;
  }

  public function getCode()  : string{
      return $this->code;
  }

  public function getAddress()  : string {
    return $this->address;
  }

  public function getType()  : string {
    return $this->type;
  }

  public function getId_country()  : string {
    return $this->id_country;
  }

  public function setCode(string  $code) {
        $this->code = $code;
  }

  public function setAddress(string  $address) {
    $this->address = $address;
  }

  public function setType(string  $type) {
    $this->type = $type;
  }

  public function setId_country(string  $id_country) {
    $this->id_country = $id_country;
  }

  public static function expose() : array
  {
      return get_class_vars(__CLASS__);
  }

  public static function find(string $id) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $find = 'SELECT * FROM hideout WHERE id = ?';
    
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

    $findAll = 'SELECT * FROM hideout';
    
    $pdoStatement = $pdo->prepare($findAll);

    $countries = [];

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      while($hideout = $pdoStatement->fetch()) {
        $countries[] = $hideout;
      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    return $countries;
    
  }  

  public function insertDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $insert = 'INSERT INTO hideout (code, address, type, id_country) VALUE (?, ?, ?, ?)';
    
    $pdoStatement = $pdo->prepare($insert);

    $pdoStatement->bindValue(1, $this->code, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->address, PDO::PARAM_STR);
    $pdoStatement->bindValue(3, $this->type, PDO::PARAM_STR);
    $pdoStatement->bindValue(4, $this->id_country, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }  

  public function updateDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $update = 'UPDATE hideout SET code = ?, address = ?, type = ?, id_country = ? WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($update);

    $pdoStatement->bindValue(1, $this->code, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->address, PDO::PARAM_STR);
    $pdoStatement->bindValue(3, $this->type, PDO::PARAM_STR);
    $pdoStatement->bindValue(4, $this->id_country, PDO::PARAM_INT);
    $pdoStatement->bindValue(5, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  public static function deleteDatabase(int $id) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $delete = 'DELETE FROM hideout WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

}
