<?php

class ServiceEntityRepository {
    //call_user_func

    protected $class = Mission::class;
    protected $fields = [];
    protected $associations = [];

    public function __construct($class) {

        $this->class = $class;

        foreach ((new ReflectionClass($this->class))->getProperties() as $property) {
            foreach ($property->getAttributes() as $attribut) {
                if (isset($attribut->getArguments()['foreignKey'])) {
                    $this->fields[$attribut->getArguments()['foreignKey']] = [
                        'type' => 'foreignKey',
                        'property' => $property,
                        'class' => $property->getType()->getName()
                    ];
                } elseif (isset($attribut->getArguments()['class'])) {
                    $classes = [];
                    foreach ($attribut->getArguments() as $argument) {
                        $classes[] = $argument['class'];
                    }
                    $this->associations[$property->getName()] = [
                        'type' => 'association',
                        'property' => $property,
                        'class' => $classes
                    ]; 
                } else {
                    $this->fields[$property->getName()] = [
                        'type' => 'field',
                        'property' => $property
                    ];
                }
            }
        }
                  echo '<pre>';
                  print_r($this->associations);
    }    

    public function constructObject($array, $object) {

        foreach (array_keys($array) as $field) {
    
            $record = $this->fields[$field];
            
            switch ($record['type']) {
                case 'field' : {
                    $record['property']->setValue($object, $array[$field]);
                    break;
                }
                case 'foreignKey' : {
                    $record['property']->setValue($object, $record['class']::find($array[$field]));
                    break;
                }
            }
    
        }

        return $object;
    
    }    
  
    public function find(string $id) { 

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);

        $find = "SELECT ".implode(', ', array_keys($this->fields))." FROM ".strtolower($this->class)." WHERE id = ?;";

        $pdoStatement = $pdo->prepare($find);

        $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

        if ($pdoStatement->execute()) {

            $object = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        } else {

            print_r($pdoStatement->errorInfo());  // sensible à modifier
        
        }
        
        return $object;

    }    

    public function findAll() { 

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
        
        $findAll = "SELECT ".implode(', ', array_keys($this->fields))." FROM ".strtolower($this->class);
        $pdoStatement = $pdo->prepare($findAll);

        $objects = [];

        if ($pdoStatement->execute()) {  
            while($fetch = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
            
                $objects[] = $fetch;

            } 
        } else {
            print_r($pdoStatement->errorInfo());  // sensible à modifier
        }  

        return $objects;
    
    }  

    public function repositoryInsert($classe, $object) { 

        $fields = array_keys(get_class_vars($classe));

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
    
        $insert = "INSERT INTO ".strtolower($classe)." (".implode(', ', $fields).") 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
        
        $pdoStatement = $pdo->prepare($insert);
    
        $pdo_const = [
            'string' => PDO::PARAM_STR,
            'int' => PDO::PARAM_INT
        ];
    
        foreach ($fields as $index => $field) {
            $value = call_user_func([$object, 'get'.ucfirst($field)]);
            $pdoStatement->bindValue($index + 1, $value, $pdo_const[gettype($value)]);
        }
    
        if (!$pdoStatement->execute()) {  
          print_r($pdoStatement->errorInfo());  // sensible à modifier
        }  
        $object->setId($pdo->lastInsertId());
    
    }  

    public function repositoryUpdate($classe, $object) { 

        $fields = array_keys(get_class_vars($classe));

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
    
        $updateFields = implode(' = ?, ', $fields).' = ?';

        $update = "UPDATE ".strtolower($classe)." SET ".$updateFields." WHERE id = ?";
        
        $pdoStatement = $pdo->prepare($update);
    
        $pdo_const = [
            'string' => PDO::PARAM_STR,
            'int' => PDO::PARAM_INT
        ];
    
        foreach ($fields as $index => $field) {
            $value = call_user_func([$object, 'get'.ucfirst($field)]);
            $pdoStatement->bindValue($index + 1, $value, $pdo_const[gettype($value)]);
        }
    
        $pdoStatement->bindValue(count($fields) + 1, $object->getId(), PDO::PARAM_INT);

        if (!$pdoStatement->execute()) {  
          print_r($pdoStatement->errorInfo());  // sensible à modifier
        }  
    
    }
    
    public function repositoryDelete($classe, $object) { 
    
        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
    
        $delete = "DELETE FROM ".strtolower($classe)." WHERE id = ?; ";
        
        $pdoStatement = $pdo->prepare($delete);
    
        $pdoStatement->bindValue(1, $object->getId(), PDO::PARAM_INT);
    
        if (!$pdoStatement->execute()) {  
          print_r($pdoStatement->errorInfo());  // sensible à modifier
        }  
    
    }
/*
    private static function getOneToMany ($oneToMany) {

        $fields = array_keys(get_class_vars($oneToMany['className']));

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
        
        $findHideout = "SELECT FROM "." WHERE id_mission = ?";
        
        $pdoStatement = $pdo->prepare($findHideout);
        
        $pdoStatement->bindValue(1, $id_mission, PDO::PARAM_INT);
        
        $hideouts = [];
        
        if ($pdoStatement->execute()) {  
            while($hideout = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
            $hideouts[] = $hideout['id_hideout'];
            }
        } else {
            print_r($pdoStatement->errorInfo());  // sensible à modifier
        }  
        
        return $hideouts;
        
    }  
*/    
}
