<?php

class Mission {

  protected $id;
  #[Column]
  protected $title;
  #[Column]
  protected $description;
  #[Column]
  protected $codeName;
  #[Column]
  protected $begin;
  #[Column]
  protected $end;

  #[OneToMany(foreignKey: 'id_country')]
  protected ?Country $country;
  #[OneToMany(foreignKey: 'id_statut')]
  protected ?Statut $statut;
  #[OneToMany(foreignKey: 'id_typeMission')]
  protected ?TypeMission $typeMission;
  #[OneToMany(foreignKey: 'id_speciality')]
  protected ?Speciality $speciality;

  #[ManyToMany(class: 'Mission')]
  #[ManyToMany(class: 'Hideout')]
  protected $hideouts;
  #[ManyToMany(class: 'Mission')]
  #[ManyToMany(class: 'Actor')]
  #[ManyToMany(class: 'Role')]
  protected $actors_roles;

  public function __construct() {

    $this->id = 0;

    $this->country = null;
    $this->statut = null;
    $this->typeMission = null;
    $this->speciality = null;

  }

  public function getId()  : string {
    return $this->id;
  }

  public function getTitle()  : string{
    return $this->title;
  }

  public function getDescription()  : string {
    return $this->description;
  }

  public function getCodeName()  : string {
    return $this->codeName;
  }

  public function getBegin()  : string {
    return $this->begin;
  }

  public function getEnd()  : string {
    return $this->end;
  }

  public function getCountry()  : ?Country {
    return $this->country;
  }

  public function getStatut()  : ?Statut {
    return $this->statut;
  }

  public function getTypeMission()  : ?TypeMission {
    return $this->typeMission;
  }

  public function getSpeciality()  : ?Speciality {
    return $this->speciality;
  }

  public function getHideouts() { 
    return $this->hideouts;
  }  

  public function getActorsRoles() { 
    return $this->actors_roles;
  }  

  protected function setId(string  $id) {
    $this->id = $id;
  }

  public function setTitle(string  $title) {
    $this->title = $title;
  }

  public function setDescription(string  $description) {
    $this->description = $description;
  }

  public function setCodeName(string  $codeName) {
    $this->codeName = $codeName;
  }

  public function setBegin(string  $begin) {
    $this->begin = $begin;
  }

  public function setEnd(string  $end) {
    $this->end = $end;
  }

  public function setCountry(Country  $country) {
    $this->country = $country;
  }

  public function setStatut(Statut  $statut) {
    $this->statut = $statut;
  }

  public function setTypeMission(TypeMission  $typeMission) {
    $this->typeMission = $typeMission;
  }

  public function setSpeciality(Speciality  $speciality) {
    $this->speciality = $speciality;
  }

  public function setHideouts(array $hideouts) {
    $this->hideouts = $hideouts;
  }

  public function addHideout(Hideout $hideout) { 
    $this->hideouts[] = $hideout;
  }  

  public function removeHideouts() { 
    $this->hideouts = [];
  }  

  public function setActorsRoles(array $actors_roles) {
    $this->actors_roles = $actors_roles;
  }

  public function addActorsRoles($actors_roles) { 
    $this->actors_roles[] = $actors_roles;
  }  

  public function removeActorsRoles() { 
    $this->actors_roles = [];
  }  

}
