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

    $this->objects[spl_object_id($object)] = [
      'type' => gettype($object),
      'object' => $object,
      'deleted' => true
    ];  

  }

  public function flush () {

    foreach ($this->objects as $object) {
      
      if ($object['deleted']) {

        $object['object']->deleteDatabase();

      } elseif ($object['object']->getId() === "0") {

        $object['object']->insertDatabase();
      
      } else {
      
        $object['object']->updateDatabase();
      
      }

    }

  }

}
