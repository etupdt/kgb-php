<?php

class EntityManager {

  private $objects = [];

  public function persist(Object $object) {

    $this->objects[spl_object_id($object)] = [
        'class' => get_class($object),
        'object' => $object,
        'deleted' => false
      ];

  }

  public function remove ($object) {

    $this->objects[spl_object_id($object)] = [
      'class' => get_class($object),
      'object' => $object,
      'deleted' => true
    ];  

  }

  public function flush () {

    foreach ($this->objects as $object) {

      $repositoryName = $object['class'].'Repository';

      $repository = new $repositoryName(1);
      
      if ($object['deleted']) {

        $repository->delete($object['object']);

      } elseif ($object['object']->getId() === "0") {

        $repository->insert($object['object']);
      
      } else {
      
        $repository->update($object['object']);
      
      }

    }

  }

}
