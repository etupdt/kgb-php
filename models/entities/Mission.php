<?php

require 'models/repositories/MissionRepository.php';

class Mission extends MissionRepository {

  private $id;
  private $title;
  private $description;
  private $codeName;
  private $begin;
  private $end;

  private $id_country;
  private $id_statut;
  private $id_typeMission;
  private $id_speciality;

  private $hideouts;
  private $actors_roles;

  public function __construct(
    string  $id = null, 
    string  $title = null, 
    string $description = null, 
    string $codeName = null, 
    string $begin = null, 
    string $end = null, 

    string $id_country = null,
    string $id_statut = null,
    string $id_typeMission = null,
    string $id_speciality = null,

    array $hideouts = null,
    array $actors_roles = null

    ) {
      $this->id = $id;
      $this->title = $title;
      $this->description = $description;
      $this->codeName = $codeName;
      $this->begin = $begin;
      $this->end = $end;

      $this->id_country = $id_country;
      $this->id_statut = $id_statut;
      $this->id_typeMission = $id_typeMission;
      $this->id_speciality = $id_speciality;

      $this->hideouts = $hideouts;
      $this->actors_roles = $actors_roles;

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

  public function getId_country()  : string {
    return $this->id_country;
  }

  public function getId_statut()  : string {
    return $this->id_statut;
  }

  public function getId_typeMission()  : string {
    return $this->id_typeMission;
  }

  public function getId_speciality()  : string {
    return $this->id_speciality;
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

  public function setId_country(string  $id_country) {
    $this->id_country = $id_country;
  }

  public function setId_statut(string  $id_statut) {
    $this->id_statut = $id_statut;
  }

  public function setId_typeMission(string  $id_typeMission) {
    $this->id_typeMission = $id_typeMission;
  }

  public function setId_speciality(string  $id_speciality) {
    $this->id_speciality = $id_speciality;
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
