
<?php

class Entity {

    function __clone(){

        foreach($this as $name => $value){

            if(gettype($value)=='object'){

                $this->$name = clone $this->$name;

            }

        }

    }

}
