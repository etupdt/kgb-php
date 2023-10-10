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

  public function insertDatabase() { 

    include_once 'config.php';
  
    $conn = mysqli_connect($host, $username, $password, $dbname);
    $stmt=$conn->prepare("INSERT INTO hideout ( code , address, type) VALUES (?, ?, ?) ");
    $stmt->bind_param("ss", $this->code, $this->address, $this->type);
    $stmt->execute();
    $stmt->close();

  }

  public function updateDatabase() { 

    include_once 'config.php';
  
    $conn = mysqli_connect($host, $username, $password, $dbname);
    $stmt = $conn->prepare('UPDATE hideout SET code = ?, address = ?, type = ? WHERE id = ? ');
    $stmt->bind_param("sssi", $this->code, $this->address, $this->type, $this->id);
    $stmt->execute();
    $stmt->close();

  }

}
