<?php

class Hideout extends Entity {

  #[Column]
  protected $id;
  #[Column]
  protected $code;
  #[Column]
  protected $address;
  #[Column]
  protected $type;

  #[OneToMany(foreignKey: 'id_country')]
  protected ?Country $country;

  public function __construct() {

    $this->id = 0;

    $this->country = null;

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

  public function getCountry()  : ?Country {
    return $this->country;
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

  public function setId_country(Country $country) {
    $this->country = $country;
  }

}
