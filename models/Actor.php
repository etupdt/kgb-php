<?php

class Actor {

  private static $isUpdated = false;
  private $isDeleted = false;

  private $id_person;
  private $id;
  private $firstname;
  private $lastname;
  private $birthdate;
  private $identificationCode;
  private $id_country;

  private $specialities;

  public function __construct(
    string  $id = null, 
    string  $firstname = null, 
    string $lastname = null, 
    string $birthdate = null, 
    string $identificationCode = null, 
    string $id_country = null,
    array $specialities = []
  ) {
      $this->id = $id;
      $this->firstname = $firstname;
      $this->lastname = $lastname;
      $this->birthdate = $birthdate;
      $this->identificationCode = $identificationCode;
      $this->id_country = $id_country;

      $this->specialities = $specialities;
      $isUpdated = true;
  }

  public function getId()  : string {
    return $this->id;
  }

  public function getFirstname()  : string{
    return $this->firstname;
  }

  public function getLastname()  : string {
    return $this->lastname;
  }

  public function getBirthdate()  : string {
    return $this->birthdate;
  }

  public function getIdentificationCode()  : string {
    return $this->identificationCode;
  }

  public function getId_country()  : string {
    return $this->id_country;
  }

  public function getSpecialities() { 
    return $this->specialities;
  }  

  public function setFirstname(string  $firstname) {
    $isUpdated = true;
    $this->firstname = $firstname;
  }

  public function setLastname(string  $lastname) {
    $isUpdated = true;
    $this->lastname = $lastname;
  }

  public function setBirthdate(string  $birthdate) {
    $isUpdated = true;
    $this->birthdate = $birthdate;
  }

  public function setIdentificationCode(string  $identificationCode) {
    $isUpdated = true;
    $this->identificationCode = $identificationCode;
  }

  public function setId_country(string  $id_country) {
    $isUpdated = true;
    $this->id_country = $id_country;
  }

  public function setSpecialities(array $specialities) {
    $isUpdated = true;
    $this->specialities = $specialities;
  }

  public function addSpeciality(Speciality $speciality) { 
    $isUpdated = true;
    $this->specialities[] = $speciality;
  }  

  public function removeSpecialities() { 
    $isUpdated = true;
    $this->specialities = [];

  }  

  public function persist() {

    if (Actor::$isUpdated) {
      if ($this->isDeleted) {
        $this->deleteDatabase();
      } elseif ($this->getId() === 0) {
        $this->insertDatabase();
      } else {
        $this->updateDatabase();
      }
    }

    $isUpdated = false;
    $isDeleted = false;

  }

  public static function find(string $id) { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);
    
    $find = "SELECT a.id_person, a.id as id, p.firstname, p.lastname, a.birthdate, a.identificationCode, a.id_country 
    FROM actor a INNER JOIN person p ON a.id_person = p.id 
    WHERE a.id = ?;";

    $pdoStatement = $pdo->prepare($find);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if ($pdoStatement->execute()) {
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      $actor = $pdoStatement->fetch();
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }

    $actor->setSpecialities(Actor::getSpecialitiesDatabase($actor->getId()));

    return $actor;

  }    

  public static function findAll() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $findAll = "SELECT a.id_person, a.id as id, p.firstname, p.lastname, a.birthdate, a.identificationCode, a.id_country 
                FROM actor a INNER JOIN person p ON a.id_person = p.id";
    
    $pdoStatement = $pdo->prepare($findAll);

    $actors = [];

    if ($pdoStatement->execute()) {  
      $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
      while($actor = $pdoStatement->fetch()) {
        
        $actor->setSpecialities(Actor::getSpecialitiesDatabase($actor->getId()));

        $actors[] = $actor;

      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    return $actors;
    
  }  

  public function insertDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $insert = 'INSERT INTO person ( firstname , lastname) VALUES ( ?, ?);';
    
    $pdoStatement = $pdo->prepare($insert);

    $pdoStatement->bindValue(1, $this->firstname, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->lastname, PDO::PARAM_STR);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  
    $this->id_person = $pdo->lastInsertId();

    $insert = 'INSERT INTO actor (id_person, id, birthdate, identificationCode, id_country) VALUES (?, ?, ?, ?, ?);';
    
    $pdoStatement = $pdo->prepare($insert);

    $pdoStatement->bindValue(1, $this->id_person, PDO::PARAM_INT);
    $pdoStatement->bindValue(2, $this->id_person, PDO::PARAM_INT);
    $pdoStatement->bindValue(3, $this->birthdate, PDO::PARAM_STR);
    $pdoStatement->bindValue(4, $this->identificationCode, PDO::PARAM_INT);
    $pdoStatement->bindValue(5, $this->id_country, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    foreach ($this->specialities as $speciality) {

      $insert = 'INSERT actor_speciality (id_actor, id_speciality) VALUE (?, ?)';

      $pdoStatement = $pdo->prepare($insert);

      $pdoStatement->bindValue(1, $this->id, PDO::PARAM_INT);
      $pdoStatement->bindValue(2, $speciality, PDO::PARAM_INT);

      if (!$pdoStatement->execute()) {  
        print_r($pdoStatement->errorInfo());  // sensible à modifier
      }  

    }
    
  }  

  public function updateDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $update = 'UPDATE person SET firstname = ?, lastname = ? WHERE id = ?;';
    
    $pdoStatement = $pdo->prepare($update);

    $pdoStatement->bindValue(1, $this->firstname, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->lastname, PDO::PARAM_STR);
    $pdoStatement->bindValue(3, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  
    $id_person = $pdo->lastInsertId();

    $update = 'UPDATE actor SET birthdate = ?, identificationCode = ?, id_country = ? WHERE id = ?';
    
    $pdoStatement = $pdo->prepare($update);

    $pdoStatement->bindValue(1, $this->birthdate, PDO::PARAM_STR);
    $pdoStatement->bindValue(2, $this->identificationCode, PDO::PARAM_INT);
    $pdoStatement->bindValue(3, $this->id_country, PDO::PARAM_INT);
    $pdoStatement->bindValue(4, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    $delete = 'DELETE FROM actor_speciality 
      WHERE id_actor = ? ';

    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $this->id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    foreach ($this->specialities as $speciality) {

      $insert = 'INSERT actor_speciality (id_actor, id_speciality) VALUE (?, ?)';

      $pdoStatement = $pdo->prepare($insert);

      $pdoStatement->bindValue(1, $this->id, PDO::PARAM_INT);
      $pdoStatement->bindValue(2, $speciality, PDO::PARAM_INT);

      if (!$pdoStatement->execute()) {  
        print_r($pdoStatement->errorInfo());  // sensible à modifier
      }  

    }
    
  }

  public static function deleteDatabase() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $delete = 'DELETE FROM actor_speciality WHERE id_actor = ? ';

    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $actor->getId(), PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    $delete = 'DELETE FROM actor WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    $delete = 'DELETE FROM person WHERE id = ?; ';
    
    $pdoStatement = $pdo->prepare($delete);

    $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

    if (!$pdoStatement->execute()) {  
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

  }

  public static function getSpecialitiesDatabase($id_actor) { 
  
    $pdo = new PDO(Database::$host, Database::$username, Database::$password);
  
    $findSpeciality = "SELECT id_speciality FROM actor_speciality WHERE id_actor = ?";
  
    $pdoStatement = $pdo->prepare($findSpeciality);
  
    $pdoStatement->bindValue(1, $id_actor, PDO::PARAM_INT);
  
    $specialities = [];
  
    if ($pdoStatement->execute()) {  
      while($speciality = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
        $specialities[] = $speciality['id_speciality'];
      }
    } else {
      print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  
  
    return $specialities;
  
  }  

}
