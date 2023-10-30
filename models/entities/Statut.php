<?php

class Statut {

  #[Column]
  protected $id;
  #[Column]
  protected $statut;

  public function __construct() {

    $this->id = 0;

  }

  public function getId()  : string {
      return $this->id;
  }

  public function getStatut()  : string{
      return $this->statut;
  }

  public function setStatut(string  $statut) {
        $this->statut = $statut;
  }

}
