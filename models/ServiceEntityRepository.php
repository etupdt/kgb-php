<?php

class ServiceEntityRepository {
    //call_user_func

    protected $rootClass;
    protected $heritClass;
    protected $fields = [];
    protected $columns = [];
    protected $associations = [];
    private $find;
    protected $depth;
    protected $maxDepth = 1;

    private $log = true;

    public function __construct($rootClass) {

        $this->rootClass = $rootClass;

        $reflectionClass = new ReflectionClass($rootClass);
        
        foreach ($reflectionClass->getAttributes() as $classAttribut) {
            if ($classAttribut->getName() === 'Herit') {
                $this->heritClass = $classAttribut->getArguments()['class'];
            }
        }

        foreach ($reflectionClass->getProperties() as $property) {

            $attributes = $property->getAttributes();

            $TransformedProperty = [
                'property' => $property,
                'type' => 'field'
            ];

            foreach ($attributes as $attribut) {

                $arguments = $attribut->getArguments();

                if ($attribut->getName() === 'Column') {
                    $TransformedProperty['type'] = 'column';
                }

                foreach (array_keys($arguments) as $argument) {

                      switch ($argument) {
                        case 'foreignKey' : {
                            $TransformedProperty['type'] = 'foreignKey';
                            $TransformedProperty['class'] = $property->getType()->getName();
                            $TransformedProperty['name'] = $arguments['foreignKey'];
                            break;
                        }
                        case 'class' : {
                            $TransformedProperty['type'] = 'association';
                            $TransformedProperty['classes'][] = $arguments['class'];
                        }
                    }

                }
            
            }

            switch ($TransformedProperty['type']) {
    
                case 'field' : {
                    $this->fields[$property->getName()] = $TransformedProperty;
                    break;
                }
                case 'column' : {
                    $this->columns[$property->getName()] = $TransformedProperty;
                    break;
                }
                case 'foreignKey' : {
                    $this->columns[$TransformedProperty['name']] = $TransformedProperty;
                    break;
                }
                case 'association' : {
                    $this->associations[$property->getName()] = $TransformedProperty;
                    break;
                }
    
            }
        
        }

    }    

    public function constructObject($array, $object) {

        foreach (array_keys($array) as $field) {
    
            $record = array_merge($this->fields, $this->columns)[$field];
            
            switch ($record['type']) {
                case 'field' : {
                    $record['property']->setValue($object, $array[$field]);
                    break;
                }
                case 'column' : {
                    $record['property']->setValue($object, $array[$field]);
                    break;
                }
                case 'foreignKey' : {
                    if ($this->depth < $this->maxDepth) {
                        $classe = $record['class'].'Repository';
                        $repository = new $classe($this->depth + 1);
                        $record['property']->setValue($object, $repository->find($array[$field]));
                    } else {
                        $record['property']->setValue($object, null);
                    }
                    break;
                }
            }
    
        }

        foreach (array_keys($this->associations) as $association) {
    
            $record = $this->associations[$association];

            $tuplesIds = $this->findAllAssociations($record['classes'], $object->getId());
    
            $tuplesObjects = [];

            foreach ($tuplesIds as $tupleIds) {

                $tupleObjects = [];

                foreach (array_keys($tupleIds) as $key) {
    
                    $class = ucfirst(str_replace('id_', '', $key));
    
                    if ($this->depth < $this->maxDepth) {
                        $classe = $class.'Repository';
                        $repository = new $classe($this->depth + 1);
                        $tupleObjects[$class] = $repository->find($tupleIds[$key]);
                    } else {
                        $tupleObjects[$class] = null;
                    }             

                }    

                $tuplesObjects[] = $tupleObjects;
                                                 
            }    
            
            // echo '<pre>';
            // print_r($tuplesObjects);
            // echo '</pre';
            $record['property']->setValue($object, $tuplesObjects);
        
        }

        return $object;
    
    }    
  
    public function find(string $id) { 

        $this->find = "SELECT ".strtolower($this->rootClass).".id AS id, ".implode(', ', array_keys($this->columns))." FROM "
            .strtolower($this->rootClass)
            .(isset($this->heritClass) ? ', '.strtolower($this->heritClass)." WHERE ".strtolower($this->heritClass).".id = id_".strtolower($this->heritClass) : "");

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);

        $find = $this->find." ".(isset($this->heritClass) ? "AND" : "WHERE")." ".strtolower($this->rootClass).".id = ?;";

        $pdoStatement = $pdo->prepare($find);

        // if ($this->log) {
        //     echo '<pre>';
        //     print_r('============>   '.$this->depth.'    '.$find);
            error_log('============>   '.$this->depth.'    '.$find);
        // }

        $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

        if ($pdoStatement->execute()) {

            $object = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        } else {
            print_r($pdoStatement->errorInfo());  // sensible à modifier
        }
        
        return $object;

    }    

    public function findAllAssociations($classes, string $id) { 

        $table = implode('_', $classes);

        $select  = [];

        foreach ($classes as $class) {
            if ($class !== $this->rootClass) {
                $select[] = 'id_'.$class; 
            }
        }

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);

        $find = "SELECT ".strtolower(implode(', ', $select))." FROM ".strtolower($table)." WHERE id_".$this->rootClass." = ?;";

        $pdoStatement = $pdo->prepare($find);

        $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

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

    public function findAll() { 

        $this->find = "SELECT ".strtolower($this->rootClass).".id AS id, ".implode(', ', array_keys($this->columns))." FROM "
            .strtolower($this->rootClass)
            .(isset($this->heritClass) ? ', '.strtolower($this->heritClass)." WHERE ".strtolower($this->heritClass).".id = id_".strtolower($this->heritClass) : "");

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
        
        $findAll = $this->find;
        $pdoStatement = $pdo->prepare($findAll);

        if ($this->log) {
            // echo '<pre>';
            // echo '<br>';
            // print_r('============>   '.$this->depth.'    '.$findAll);
            error_log('============>   '.$this->depth.'    '.$findAll);
            // echo '</pre>';
        }

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
