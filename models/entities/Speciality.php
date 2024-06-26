<?php

class Speciality extends Entity {

  #[Column]
  protected $id;
  #[Column]
  protected $name;

  #[ManyToMany(classes: ['Actor', 'Speciality'])]
  protected $actors;

  public function __construct() {

    $this->id = 0;

  }

  public function getId()  : string {
      return $this->id;
  }

  public function getActors() { 
    return $this->actors;
  }  

  public function getName()  : string{
      return $this->name;
  }

  public function setName(string  $name) {
    $this->name = $name;
  }

  public function setActors(array  $actors) {
    $this->actors = $actors;
  }

}
