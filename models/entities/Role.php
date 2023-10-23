<?php

class Role {

  private $isUpdated = false;
  private $isDeleted = false;

  private $id;
  private $role;

  public function __construct(string $id = null, string $role = null) {
    $this->id = $id;
    $this->role = $role;
  }

  public function __set($name, $value)
  {
      // empty
  }

  public function getId()  : string {
      return $this->id;
  }

  public function getRole()  : string{
      return $this->role;
  }

  public function setRole(string  $role) {
        $this->role = $role;
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

    $find = 'SELECT * FROM role WHERE id = ?';
    
    $pdoStatement = $pdo->prepare($find);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      $role = $pdoStatement->fetch();
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    return $role;

  }  

  public static function findAll() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $findAll = 'SELECT id, role FROM role';
    
    $pdoStatement = $pdo->prepare($findAll);

    $roles = [];

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      while($role = $pdoStatement->fetch()) {

        $roles[] = $role;

      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    return $roles;
    
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

    $insert = 'INSERT INTO role (role) VALUE (?)';
    
    $pdoStatement = $pdo->prepare($insert);

    $pdoStatement->bindValue(1, $this->role, PDO::PARAM_STR);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }  

  public function updateDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $update = 'UPDATE role SET role = ? WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($update);

    $pdoStatement->bindValue(1, $this->role, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  public function deleteDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $delete = 'DELETE FROM role WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

}
