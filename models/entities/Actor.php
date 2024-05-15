<?php

require_once $_SERVER['DOCUMENT_ROOT'].'models/entities/Person.php';

#[Herit(class: 'Person')]
class Actor extends Person {

  // #[Column]
  // protected $id;
  #[Column]
  protected $birthdate;
  #[Column]
  protected $identificationCode;

  #[OneToMany(foreignKey: 'id_country')]
  protected ?Country $country;

  #[ManyToMany(classes: ['Actor', 'Speciality'])]
  protected $specialities;

  public function __construct() {

    $this->id = 0;

    $this->country = null;

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

  public function getBirthdate()  : string {
    return $this->birthdate;
  }

  public function getIdentificationCode()  : string {
    return $this->identificationCode;
  }

  public function getCountry()  : ?Country {
    return $this->country;
  }

  public function getSpecialities() { 
    return $this->specialities;
  }  

  public function setFirstname(string  $firstname) {
    $this->firstname = $firstname;
  }

  public function setLastname(string  $lastname) {
    $this->lastname = $lastname;
  }

  public function setBirthdate(string  $birthdate) {
    $this->birthdate = $birthdate;
  }

  public function setIdentificationCode(string  $identificationCode) {
    $this->identificationCode = $identificationCode;
  }

  public function setCountry(Country  $country) {
    $this->country = $country;
  }

  public function setSpecialities(array $specialities) {
    $this->specialities = $specialities;
  }

  public function addSpeciality(Speciality $speciality) { 
    $this->specialities[] = $speciality;
  }  

  public function removeSpecialities() { 
    $this->specialities = [];
  }  

  public function __clone() {
    
  }

}
