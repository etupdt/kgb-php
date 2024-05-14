
<?php

class Entity {

    function clone() {

        $retour = clone $this;

        foreach ((new ReflectionClass($this))->getProperties() as $property) {

            if ($property->gettype() !== null) {
                $name = $property->getName();
                if ($this->$name !== null) {
                    $property->setValue($retour, $this->$name->clone());
                }
            }

        }

        return $retour;

    }

}
