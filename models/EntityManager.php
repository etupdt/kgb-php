<?php

class EntityManager {

  private $objects = [];

  public function persist(Object $object) {

    $this->objects[spl_object_id($object)] = [
        'type' => gettype($object),
        'object' => $object,
        'deleted' => false
      ];

  }

  public function remove ($object) {
      $this->objects[spl_object_id($object)]['deleted'] = true;
  }

  public function flush () {
    foreach ($this->objects as $object) {
      if ($this->objects[spl_object_id($object)]['deleted']) {
        $object->deleteDatabase();
      } elseif ($this->objects[spl_object_id($object)]['object']->getId() === "0") {
        $object->insertDatabase();
      } else {
        $object->updateDatabase();
      }
    }
  }

}
