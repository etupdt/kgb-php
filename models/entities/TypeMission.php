<?php

class TypeMission {

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

  public function setTypeMisson(string  $typeMission) {
    $this->typeMission = $typeMission;
  }

}
