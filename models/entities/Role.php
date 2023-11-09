<?php

class Role extends Entity {

  #[Column]
  private $id;
  #[Column]
  private $role;

  public function __construct() {

    $this->id = 0;

  }

  public function getId()  : string {
      return $this->id;
  }

  public function getRole()  : string{
      return $this->role;
  }

  public function setRole(string  $role) {
        $this->role = $role;
  }

}
