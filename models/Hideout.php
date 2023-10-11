<?php

class Hideout {

  private $id;
  private $code;
  private $address;
  private $type;

  public function __construct(string  $id, string  $code, string $address, string $type) {
      $this->id = $id;
      $this->code = $code;
      $this->address = $address;
      $this->type = $type;
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

  public function setCode(string  $code) {
        $this->code = $code;
  }

  public function setAddress(string  $address) {
    $this->address = $address;
  }

  public function setType(string  $type) {
    $this->type = $type;
  }

  public static function expose() : array
  {
      return get_class_vars(__CLASS__);
  }

  public static function find(string $id) { 

    include_once 'Database.php';
  
    $conn = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    $stmt = $conn->prepare("SELECT * FROM hideout WHERE id = ?;");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $hideout = new Hideout ($row['id'], $row['code'], $row['address'], $row['type']);
    }

    return $hideout;
    
  }  

  public static function findAll() { 

    $conn = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    $stmt = $conn->prepare("SELECT * FROM hideout;");
    $stmt->execute();
    $result = $stmt->get_result();

    $hideouts = [];

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $hideouts[] = new Hideout ($row['id'], $row['code'], $row['address'], $row['type']);
      }
    }

    return $hideouts;
    
  }  

  public function insertDatabase() { 

    $conn = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    $stmt=$conn->prepare("INSERT INTO hideout ( code , address, type) VALUES (?, ?, ?) ");
    $stmt->bind_param("sss", $this->code, $this->address, $this->type);
    $stmt->execute();
    $stmt->close();

  }  

  public function updateDatabase() { 

    $conn = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    $stmt = $conn->prepare('UPDATE hideout SET code = ?, address = ?, type = ? WHERE id = ? ');
    $stmt->bind_param("sssi", $this->code, $this->address, $this->type, $this->id);
    $stmt->execute();
    $stmt->close();

  }

  public static function deleteDatabase(int $id) { 

    $conn = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    $stmt = $conn->prepare('DELETE FROM hideout WHERE id = ?; ');
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

  }

}
