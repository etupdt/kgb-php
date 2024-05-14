<?php

class ServiceEntityRepository {

    protected $datas;

    protected $rootClass;

    protected $depth = 0;
    protected $maxDepth;


    private $log = true;

    public function __construct($rootClass) {

        $this->rootClass = $rootClass;

        $this->datas['columns'] = [];
        $this->datas['results'] = [];
        $this->datas['tables'] = [];
        $this->datas['where'] = [];
        $this->datas['associations'] = [];

        $this->analyseClasse($rootClass, $this->datas, $this->depth);
        $this->datas['master'] = strtolower($rootClass);

    }

    private function analyseClasse ($class, &$datas, $depth) {

        error_log('===== analyseClasse =======>   '.$depth.'    '.$class.'    <======================');

        $table = strtolower($class);

        $originPropertyClass = $class;

        foreach ((new ReflectionClass($class))->getProperties() as $property) {

            $originPropertyClass = $property->getDeclaringClass()->getName();
            $originPropertyTable = strtolower($originPropertyClass);

            foreach ($property->getAttributes() as $attribut) {

                $arguments = $attribut->getArguments();

                switch ($attribut->getName()) {

                    case 'Column' : {

                        $datas['results'][$table.'_'.$property->getName()] = [
                            'table' => $table,
                            'type' => 'Column',
                            'property' => $property
                        ];
                        $datas['tables'][$table] = [
                            'class' => $class,
                            'object' => new $class('0')
                        ];
                        if ($class !== $originPropertyClass) {
                            $datas['tables'][$originPropertyTable] = [
                                'class' => $originPropertyClass,
                                'object' => null
                            ];
                            $datas['where'][] = $originPropertyTable.".id = ".strtolower($table).".id_".$originPropertyTable; 
                        }
                        $datas['columns'][$table.'_'.$property->getName()] = [
                            'class' => $class,
                            'selectField' => $originPropertyTable.'.'.$property->getName().' AS '.strtolower($class).'_'.$property->getName()
                        ];

                        break;

                    }

                    case 'OneToMany' : {

                        $foreignClass = str_replace('?', '', $property->getType());
                        $foreignTable = strtolower($foreignClass);
        
                        if ($depth < $this->maxDepth) {

                            $datas['results'][$table.'_'.$property->getName()] = [
                                'table' => $table,
                                'type' => 'OneToMany',
                                'property' => $property,
                                'source' => $foreignTable
                            ];

                            $this->analyseClasse($foreignClass, $datas, $depth + 1);
                            
                            $datas['tables'][$foreignTable] = [
                                'class' => $foreignClass,
                                'object' => new $foreignClass('0')
                            ];

                            $datas['where'][] = $table.".id_".$foreignTable." = ".$foreignTable.".id";
                        
                        }    

                        break;

                    }
                    case 'ManyToMany' : {
                                              
                        $tupleTable = strtolower(implode('_', $arguments['classes']));
                        $associationWhere[] = strtolower($class).".id = ".$tupleTable.".id_".strtolower($class);
                        
                        $associationDatas['columns'] = [];
                        $associationDatas['results'] = [];
                        $associationDatas['tables'] = [];
                        $associationDatas['where'] = [];
                        $associationDatas['pivot'] = $table;
                        $associationDatas['master'] = $tupleTable;
                        
                        if ($depth < $this->maxDepth) {

                            foreach ($arguments['classes'] as $foreignClass) {

                                $foreignTable = strtolower($foreignClass);


                                if ($foreignClass !== $class) {         

                                    $associationDatas['results'][$tupleTable.'_'.$foreignTable] = [
                                        'table' => $tupleTable,
                                        'type' => 'ManyToManyEntry',
                                        'source' => $foreignTable
                                    ];
            
                                    $associationDatas['tables'][$foreignTable] = [
                                        'class' => $foreignClass,
                                        'object' => new $foreignClass('0')
                                    ];
                                
                                    $this->analyseClasse($foreignClass, $associationDatas, $depth + 1);

                                    $associationDatas['where'][] = strtolower($foreignTable).".id = ".$tupleTable.".id_".$foreignTable;

                                }

                                
                            }
                            
                            $associationDatas['tables'][$tupleTable] = [
                                'class' => ucfirst($tupleTable),
                                'object' => []
                            ];
                            
                            $datas['results'][$table.'_'.$property->getName()] = [
                                'table' => $table,
                                'type' => 'ManyToMany',
                                'property' => $property,
                                'datas' => $associationDatas
                            ];    
                            
                        }
                        break;

                    }

                }
                
            }
            
        }

    }    

    public function constructObject($array, $datas) {

        foreach ($datas['results'] as $fieldName=>$field) {
    
            $table = $field['table'];

            switch ($field['type']) {
                case 'Column' : {
                    $field['property']->setValue($datas['tables'][$table]['object'], $array[$fieldName]);
                    break;
                }
                case 'OneToMany' : {
                    $field['property']->setValue($datas['tables'][$table]['object'], $datas['tables'][strtolower($field['source'])]['object']);
                    break;
                }
                case 'ManyToManyEntry' : {
                    $datas['tables'][$table]['object'][$field['source']] = $datas['tables'][strtolower($field['source'])]['object'];
                    break;
                }
                case 'ManyToMany' : {

                    $id = $array[$field['datas']['pivot'].'_id'];

                    $associationObjects = $this->findAllAssociation($id, $field['datas']);
                            
                    $field['property']->setValue($datas['tables'][$table]['object'], $associationObjects);
                    break;

                }
            }

        }

        if (gettype($datas['tables'][$datas['master']]['object']) === 'object') {
            return $datas['tables'][$datas['master']]['object']->clone();    
        } else {
            $entries = [];
            foreach($datas['tables'][$datas['master']]['object'] as $key=>$entry) {
                $entries[$key] = $entry->clone();   
            }
            return $entries;
        } 

    }    
  
    public function find(string $id) { 

        $table = strtolower($this->rootClass);
        
        $find = "SELECT ".implode(', ', array_column($this->datas['columns'], 'selectField')).
        " FROM ".strtolower(implode(', ', array_keys($this->datas['tables']))).
        " WHERE ".$table.".id = ?".
        (count($this->datas['where']) === 0 ? "" : " AND ".implode(' AND ', $this->datas['where']));

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);

        $pdoStatement = $pdo->prepare($find);

        if ($this->log) {
            error_log('===== find =======>   '.$find);
        }

        $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

        if ($pdoStatement->execute()) {

            $fetch = $pdoStatement->fetch(PDO::FETCH_ASSOC);

            $object = $this->constructObject($fetch, $this->datas);

        } else {
            print_r($pdoStatement->errorInfo());  // sensible à modifier
        }
        
        return $object;

    }    

    public function findAllAssociation(string $id, $datas) { 

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);

        $find = "SELECT ".implode(', ', array_column($datas['columns'], 'selectField')).
        " FROM ".strtolower(implode(', ', array_keys($datas['tables']))).
        " WHERE ".$datas['master'].".id_".$datas['pivot']." = ?".  
        (count($datas['where']) === 0 ? "" : " AND ".implode(' AND ', $datas['where']));

        if ($this->log) {
            error_log('===== findAllAssociations =======>   '.$id.'   '.$find);
        }

        $pdoStatement = $pdo->prepare($find);

        $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

        $objects = [];
        
        if ($pdoStatement->execute()) {
            while($fetch = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {

                $object = $this->constructObject($fetch, $datas);

                $objects[] = $object;

            } 
        } else {
            print_r($pdoStatement->errorInfo());  // sensible à modifier
        }
        
        return $objects;

    }    

    public function findAll() { 

        $findAll = "SELECT ".implode(', ', array_column($this->datas['columns'], 'selectField')).
        " FROM ".strtolower(implode(', ', array_keys($this->datas['tables']))).
        (count($this->datas['where']) === 0 ? "" : " WHERE ".implode(' AND ', $this->datas['where']));

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
        
        $pdoStatement = $pdo->prepare($findAll);

        if ($this->log) {
            error_log('===== findAll =======>   '.$this->depth.'    '.$findAll);
        }

        $objects = [];

        if ($pdoStatement->execute()) {  
            while($fetch = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
            
                $object = $this->constructObject($fetch, $this->datas);

                $objects[] = $object;

            } 

        } else {
            print_r($pdoStatement->errorInfo());  // sensible à modifier
        }  

        return $objects;
    
    }  

    public static function insertDatabase($table, $fields) { 

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
    
        $insert = "INSERT INTO ".$table." (".implode(', ', array_keys($fields)).")".
        " VALUE (".implode(', ', array_fill(0, count($fields), '?')).");";
        
        error_log('===== insert =======>    '.$insert);

        $pdoStatement = $pdo->prepare($insert);
    
        $pdo_const = [
            'string' => PDO::PARAM_STR,
            'int' => PDO::PARAM_INT
        ];
    
        $index = 1;
        foreach ($fields as $value) {
            $pdoStatement->bindValue($index, $value, $pdo_const[gettype($value)]);
            $index++;
        }
    
        if (!$pdoStatement->execute()) {  
          print_r($pdoStatement->errorInfo());  // sensible à modifier
        }  
        
        return $pdo->lastInsertId();
    
    }  

    public static function updateDatabase($table, $id, $fields) { 

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
    
        $update = "UPDATE ".$table." SET ".implode(' = ?, ', array_keys($fields)).' = ?'." WHERE id = ?";
        
        error_log('===== update =======>    '.$update);

        $pdoStatement = $pdo->prepare($update);
    
        $pdo_const = [
            'string' => PDO::PARAM_STR,
            'int' => PDO::PARAM_INT
        ];
    
        $index = 1;
        foreach ($fields as $value) {
            $pdoStatement->bindValue($index, $value, $pdo_const[gettype($value)]);
            $index++;
        }
        $pdoStatement->bindValue($index, $id, PDO::PARAM_INT);
    
        if (!$pdoStatement->execute()) {  
          print_r($pdoStatement->errorInfo());  // sensible à modifier
        }  
        
    }  
    
    public function deleteDatabase($table, $and) { 
    
        $pdo = new PDO(Database::$host, Database::$username, Database::$password);

        foreach ($and as $left=>$right) {
            $where[] = $left." = ?";
        }
    
        $delete = "DELETE FROM ".$table." WHERE ".implode(' AND ', $where);
        
        $pdoStatement = $pdo->prepare($delete);
    
        $pdo_const = [
            'string' => PDO::PARAM_STR,
            'int' => PDO::PARAM_INT
        ];
    
        $index = 1;
        foreach ($and as $value) {
            $pdoStatement->bindValue($index, $value, $pdo_const[gettype($value)]);
            $index++;
        }
    
        if (!$pdoStatement->execute()) {  
          print_r($pdoStatement->errorInfo());  // sensible à modifier
        }  
    
    }

}
