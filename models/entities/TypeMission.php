<?php

class TypeMission extends Entity {

  #[Column]
  protected $id;
  #[Column]
  protected $typeMission;

  public function __construct() {

    $this->id = 0;

  }

  public function getId()  : string {
    return $this->id;
  }

  public function getTypeMission()  : string{
    return $this->typeMission;
  }

  public function setTypeMission(string $typeMission) {
    $this->typeMission = $typeMission;
  }

}
