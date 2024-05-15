<?php

require_once $_SERVER['DOCUMENT_ROOT'].'models/Entity.php';

class Person extends Entity {

  #[Column]
  protected $id;
  #[Column]
  protected $firstname;
  #[Column]
  protected $lastname;

  public function __construct(int $id = null) {

    $this->id = $id;

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

  public function setFirstname(string  $firstname) {
    $this->firstname = $firstname;
  }

  public function setLastname(string  $lastname) {
    $this->lastname = $lastname;
  }

}
