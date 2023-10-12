<?php

class Country {

  static $fields = [
    'id' => [
      'label' => 'Id',
      'name' => 'id',
      'type' => 'text',
      'pdo' => PDO::PARAM_STR
    ],
    'name' => [
      'label' => 'Nom',
      'name' => 'name',
      'type' => 'text',
      'pdo' => PDO::PARAM_STR
    ],
    'nationality' => [
      'label' => 'Nationalité',
      'name' => 'nationality',
      'type' => 'text',
      'pdo' => PDO::PARAM_STR
    ]
  ];

  private $id;
  private $name;
  private $nationality;

  public function __construct(string $id = null, string $name = null, string $nationality = null) {
    $this->id = $id;
    $this->name = $name;
    $this->nationality = $nationality;
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

  public function getNationality()  : string {
    return $this->nationality;
  }

  public function setName(string  $name) {
        $this->name = $name;
  }

  public function setNationality(string  $nationality) {
    $this->nationality = $nationality;
  }

  public static function find(string $id) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $find = 'SELECT * FROM country WHERE id = ?';
    
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

    $findAll = 'SELECT id, name, nationality FROM country';
    
    $pdoStatement = $pdo->prepare($findAll);

    $countries = [];

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      while($country = $pdoStatement->fetch()) {
        $countries[] = $country;
      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    return $countries;
    
  }  

  public function insertDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $insert = 'INSERT INTO country (name, nationality) VALUE (?, ?)';
    
    $pdoStatement = $pdo->prepare($insert);

    $pdoStatement->bindValue(1, $this->name, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->nationality, PDO::PARAM_STR);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }  

  public function updateDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $update = 'UPDATE country SET name = ?, nationality = ? WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($update);

    $pdoStatement->bindValue(1, $this->name, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->nationality, PDO::PARAM_STR);
    $pdoStatement->bindValue(3, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  public static function deleteDatabase(int $id) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $delete = 'DELETE FROM country WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

}
