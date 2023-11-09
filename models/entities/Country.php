<?php

class Country extends Entity {

  #[Column]
  protected $id;
  #[Column]
  protected $name;
  #[Column]
  protected $nationality;

  public function __construct() {

    $this->id = 0;

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

}
