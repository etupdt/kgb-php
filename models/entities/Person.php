<?php

class Person {

  protected $id;
  #[Column]
  protected $firstname;
  #[Column]
  protected $lastname;
  #[Column]

  public function __construct() {

    $this->id = 0;

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
