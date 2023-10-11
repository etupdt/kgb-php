<?php

class Country {

  private $id;
  private $name;
  private $nationality;

  public function __construct(string  $id, string  $name, string $nationality) {
      $this->id = $id;
      $this->name = $name;
      $this->nationality = $nationality;
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

    $conn = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    $stmt = $conn->prepare("SELECT * FROM country WHERE id = ?;");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $country = new Country($row['id'], $row['name'], $row['nationality']);
    }

    return $country;
    
  }  

  public static function findAll() { 

    $conn = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    $stmt = $conn->prepare("SELECT * FROM country;");
    $stmt->execute();
    $result = $stmt->get_result();

    $countries = [];

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $countries[] = new Country ($row['id'], $row['name'], $row['nationality']);
      }
    }

    return $countries;
    
  }  

  public function insertDatabase() { 

    $conn = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    $stmt=$conn->prepare("INSERT INTO country ( name , nationality) VALUES (?, ?);");
    $stmt->bind_param("ss", $this->name, $this->nationality);
    $stmt->execute();
    $stmt->close();

  }  

  public function updateDatabase() { 

    $conn = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    $stmt = $conn->prepare('UPDATE country SET name = ?, nationality = ? WHERE id = ?; ');
    $stmt->bind_param("ssi", $this->name, $this->nationality, $this->id);
    $stmt->execute();
    $stmt->close();

  }

  public static function deleteDatabase(int $id) { 

    $conn = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    $stmt = $conn->prepare('DELETE FROM country WHERE id = ?; ');
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

  }

}
