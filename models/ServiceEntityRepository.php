<?php

class ServiceEntityRepository {
    //call_user_func

    protected $rootClass;
    protected $columns = [];
    protected $associations = [];
    private $where;
    protected $depth = 0;
    protected $maxDepth;

    private $tables = [];

    private $log = true;

    public function __construct($rootClass) {

        $this->rootClass = $rootClass;

        $analyse = $this->analyseClasse ($rootClass, $this->depth);

        $this->columns = $analyse['columns'];
        $this->where = $analyse['where'];
        $this->tables = $analyse['tables'];
        $this->associations = $analyse['associations'];
        
    }

    private function analyseClasse ($class, $depth) {

        error_log('===== analyseClasse =======>   '.$depth.'    '.$class.'    <======================');

        $columns = [];
        $tables = [];
        $where = [];
        $associations = [];

        $originPropertyClass = $class;

        foreach ((new ReflectionClass($class))->getProperties() as $property) {

            $attributes = $property->getAttributes();

            $originPropertyClass = $property->getDeclaringClass()->getName();

            foreach ($attributes as $attribut) {

                $arguments = $attribut->getArguments();

                switch ($attribut->getName()) {

                    case 'Column' : {
                        $tables[strtolower(strtolower($this->rootClass))] = [
                            'class' => $class,
                            'object' => new $class('0')
                        ];
                        $columns[strtolower($class).'_'.$property->getName()] = [
                            'type' => 'column',
                            'property' => $property,
                            'class' => $class,
                            'selectField' => strtolower($originPropertyClass).'.'.$property->getName().' AS '.strtolower($class).'_'.$property->getName()
                        ];
                        break;
                    }
                    case 'OneToMany' : {
                        error_log('===== onetomany <======================');
                        $foreignClass = str_replace('?', '', $property->getType());
                        $foreignTable = strtolower($foreignClass);
        
                        if ($depth < $this->maxDepth) {
                            error_log('===== analyseClasse recurse onetomany =======>   '.$depth.'    '.$foreignClass.'    <======================');
                            $analyse = $this->analyseClasse ($foreignClass, $depth + 1);
                            $tables[$foreignTable] = [
                                'class' => $foreignClass,
                                'object' => new $foreignClass('0')
                            ];
                            $columns = array_merge($columns, $analyse['columns']);
                            $where[] = strtolower($class).".id_".$foreignTable." = ".$foreignTable.".id";
                        }    

                        break;

                    }
                    case 'ManyToMany' : {
                        error_log('===== manytomany <======================');
                        
                        $tupleTable = strtolower(implode('_', $arguments['classes']));

                        $associationColumns = [];
                        $associationTables = [];
                        $associationWhere[] = strtolower($class).".id = ".$tupleTable.".id_".strtolower($class);

                        foreach ($arguments['classes'] as $foreignClass) {

                            if ($depth < $this->maxDepth) {

                                if ($foreignClass === $class) {

                                    $associationTables[strtolower($class)] = [
                                        'class' => $class,
                                        'object' => new $class('0')
                                    ];
            
                                } else {
                                    
                                    $foreignTable = strtolower($foreignClass);
                
                                    error_log('===== analyseClasse recurse manytomany =======>   '.$depth.'    '.$foreignClass.'    <======================');
                                    $analyse = $this->analyseClasse ($foreignClass, $depth + 1);
                                    $associationTables[$foreignTable] = [
                                        'class' => $foreignClass,
                                        'object' => new $foreignClass('0')
                                    ];
                                    $associationColumns = array_merge($associationColumns, $analyse['columns']);
                                    $associationWhere[] = strtolower($foreignTable).".id = ".$tupleTable.".id_".$foreignTable;
                                }

                            }

                        }

                        $associationTables[ucfirst($tupleTable)] = [
                            'class' => $tupleTable,
                            'object' => null
                        ];

                        if ($depth < $this->maxDepth) {
                            $associations[$property->getName()] = [
                            'property' => $property,
                            'mainClass' => $class,
                            'tables' => $associationTables,
                            'columns' => $associationColumns,
                            'where' => $associationWhere,
                            'associations' => []
                        ];
                    }

                        break;

                    }

                }
                
            }
            
        }

        if (isset($originPropertyClass) && $class !== $originPropertyClass) {
            error_log('===== originPropertyClass =======>   '.$originPropertyClass);
            $tables[strtolower($originPropertyClass)] = [
                'class' => $originPropertyClass,
                'object' => null
            ];
            $where[] = strtolower($originPropertyClass).".id = ".strtolower($class).".id_".strtolower($originPropertyClass); 
        }

        return [
            'columns' => $columns,
            'where' => $where,
            'tables' => $tables,
            'associations' => $associations
        ];

    }    

    public function constructObject($array, $class, $tables, $columns) {

        foreach (array_keys($array) as $fieldName) {
    
            $table = explode('_', $fieldName)[0];

            $columns[$fieldName]['property']->setValue($tables[$table]['object'], $array[$fieldName]);

        }

        $object = $tables[strtolower($class)]['object'];

        return $object;
    
    }    
  
    public function find(string $id) { 

        // echo '<pre>';
        // print_r($this->where);

        $find = "SELECT ".implode(', ', array_column($this->columns, 'selectField')).
        " FROM ".strtolower(implode(', ', array_keys($this->tables))).
        " WHERE ".array_keys($this->tables)[0].".id = ?".
        (count($this->where) === 0 ? "" : " AND ".implode(' AND ', $this->where));

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);

        $pdoStatement = $pdo->prepare($find);

        error_log('===== find =======>   '.$this->depth.'    '.$id.'    '.$find);

        $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

        if ($pdoStatement->execute()) {

            $fetch = $pdoStatement->fetch(PDO::FETCH_ASSOC);

            foreach ($this->associations as $association) {

                $tupleObjects = $this->findAllAssociation($this->rootClass, $association['tables'], $association['columns'], $association['where']);

                $association['property']->setValue($fetch, $tupleObjects);
            
            }

            $object = $fetch;

        } else {
            print_r($pdoStatement->errorInfo());  // sensible à modifier
        }
        
        return $object;

    }    

    public function findAllAssociation(string $id, $association) { 

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);

        $where = $association['where'];

        $find = "SELECT ".implode(', ', array_column($association['columns'], 'selectField')).
        " FROM ".strtolower(implode(', ', array_keys($association['tables']))).
        " WHERE ".array_keys($association['tables'])[0].".id = ?".
        (count($where) === 0 ? "" : " AND ".implode(' AND ', $where));

        if ($this->log) {
            error_log('===== findAllAssociations =======>   '.$this->depth.'    '.$id.'   '.$this->rootClass.'    '.$find);
        }

        $pdoStatement = $pdo->prepare($find);

        $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

        $objects = [];
        
        if ($pdoStatement->execute()) {
            while($fetch = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {

                $objects[] = $this->constructObject($fetch, $association['mainClass'], $association['tables'], $association['columns']);

            } 
        } else {
            print_r($pdoStatement->errorInfo());  // sensible à modifier
        }
        
        return $objects;

    }    

    public function findAll() { 

        $findAll = "SELECT ".implode(', ', array_column($this->columns, 'selectField')).
        " FROM ".strtolower(implode(', ', array_keys($this->tables))).
        (count($this->where) === 0 ? "" : " WHERE ".implode(' AND ', $this->where));

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
        
        $pdoStatement = $pdo->prepare($findAll);

        if ($this->log) {
            error_log('===== findAll =======>   '.$this->depth.'    '.$findAll);
        }

        $objects = [];

        if ($pdoStatement->execute()) {  
            while($fetch = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
            
                $object = $this->constructObject($fetch, $this->rootClass, $this->tables, $this->columns);

                foreach ($this->associations as $association) {

        echo '<pre>';
        print_r($association);
                    $tupleObjects = $this->findAllAssociation($object->getId(), $association);

                    $association['property']->setValue($object, $tupleObjects);
                
                }

                $objects[] = $object;

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
