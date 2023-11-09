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

        $this->analyseClasse ($rootClass, $this->datas, $this->depth);
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

                        error_log('===== onetomany <======================');

                        $foreignClass = str_replace('?', '', $property->getType());
                        $foreignTable = strtolower($foreignClass);
        
                        $datas['results'][$table.'_'.$property->getName()] = [
                            'table' => $table,
                            'type' => 'OneToMany',
                            'property' => $property,
                            'source' => $foreignTable
                        ];

                        if ($depth < $this->maxDepth) {

                            error_log('===== analyseClasse recurse onetomany =======>   '.$depth.'    '.$foreignClass.'    <======================');

                            $this->analyseClasse ($foreignClass, $datas, $depth + 1);
                            
                            $datas['tables'][$foreignTable] = [
                                'class' => $foreignClass,
                                'object' => new $foreignClass('0')
                            ];

                            $datas['where'][] = $table.".id_".$foreignTable." = ".$foreignTable.".id";
                        
                        }    

                        break;

                    }
                    case 'ManyToMany' : {
                        
                        error_log('===== manytomany <======================');
                        
                        $datas['results'][$table.'_'.$property->getName()] = [
                            'table' => $table,
                            'type' => 'ManyToMany',
                            'property' => $property,
                            'sources' => []
                        ];

                        $tupleTable = strtolower(implode('_', $arguments['classes']));
                        $associationWhere[] = strtolower($class).".id = ".$tupleTable.".id_".strtolower($class);
                        
                        $associationDatas['columns'] = [];
                        $associationDatas['results'] = [];
                        $associationDatas['tables'] = [];
                        $associationDatas['where'] = [];
                        $associationDatas['associations'] = [];
                        $associationDatas['pivot'] = $tupleTable.'.id_'.$table;
                        $associationDatas['master'] = $tupleTable;
                        
                        foreach ($arguments['classes'] as $foreignClass) {

                            $foreignTable = strtolower($foreignClass);

                            if ($foreignClass !== $class) {
                                $datas['results'][$table.'_'.$property->getName()]['sources'][] = $foreignTable;
                            }
    
                            if ($depth < $this->maxDepth) {

                                if ($foreignClass !== $class) {         

                                    $associationDatas['results'][$tupleTable.'_'.$foreignTable] = [
                                        'table' => $tupleTable,
                                        'type' => 'OneToMany',
                                        'source' => $foreignTable
                                    ];
            
                                    $associationDatas['tables'][$foreignTable] = [
                                        'class' => $foreignClass,
                                        'object' => new $foreignClass('0')
                                    ];
                                
                                    error_log('===== analyseClasse recurse manytomany =======>   '.$depth.'    '.$foreignClass.'    <======================');
                                    $this->analyseClasse ($foreignClass, $associationDatas, $depth + 1);

                                    $associationDatas['where'][] = strtolower($foreignTable).".id = ".$tupleTable.".id_".$foreignTable;

                                }

                            }

                        }

                        $associationDatas['tables'][$tupleTable] = [
                            'class' => ucfirst($tupleTable),
                            'object' => []
                        ];

                        if ($depth < $this->maxDepth) {

                            $datas['associations'][$property->getName()] = [
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

        error_log('=================================================================================> ');
        error_log('=================================================================================> ');
        foreach ($datas['tables'] as $t) {
            error_log('===== classes <======================> '.$t['class']);
        }
        // if (isset($array['mission_title'])) {
        //     echo '<pre>';
        //     print_r($datas);
        // }
        // // foreach (array_keys($array) as $fieldName) {
    
        //     $table = explode('_', $fieldName)[0];

        //     $datas['results'][$fieldName]['property']->setValue($datas['tables'][$table]['object'], $array[$fieldName]);

        // }

        foreach ($datas['results'] as $fieldName=>$field) {
    
            $table = $field['table'];
            // error_log('===== field <======================'.$table.'   '.'  '.$fieldName);

            switch ($field['type']) {
                case 'Column' : {
                    $field['property']->setValue($datas['tables'][$table]['object'], $array[$fieldName]);
                    break;
                }
                case 'OneToMany' : {
                    if (!isset($field['property'])) {
                        error_log('===== onetomany sans property <======================'.$table.'   '.'  '.$fieldName);
                        $datas['tables'][$table]['object'][strtolower($field['source'])] = $datas['tables'][strtolower($field['source'])]['object'];
                    } else if (isset($datas['tables'][$field['source']])) {
                        error_log('===== onetomany avec property <======================'.$table.'   '.'  '.$fieldName);
                        $field['property']->setValue($datas['tables'][$table]['object'], $datas['tables'][strtolower($field['source'])]['object']);
                    } 
                    break;
                }
                case 'ManyToMany' : {
                    $sources = [];
                    foreach ($field['sources'] as $source) {
                        if (isset($datas['tables'][$source])) {
                            $sources[] = $datas['tables'][$source]['object'];
                        }
                    }
                    $field['property']->setValue($datas['tables'][$table]['object'], $sources);
                    break;
                }
            }

        }

        // if ($table === 'mission_actor_role') {
            // echo '<pre>';
            // print_r(gettype($datas['tables'][$datas['master']]['object']));
        // }
        // error_log('===== master <======================'.$datas['master']);
        if (gettype($datas['tables'][$datas['master']]['object']) === 'object') {
            // error_log('===== objet <======================'.gettype($datas['tables'][$datas['master']]['object']).'    '.$fieldName.'   '.gettype($datas['tables'][$datas['master']]['object']));
            return clone $datas['tables'][$datas['master']]['object'];
        } else {
            // error_log('===== tableau <======================'.gettype($datas['tables'][$datas['master']]['object']).'    '.$fieldName.'   '.gettype($datas['tables'][$datas['master']]['object']));
            $retour = [];
            foreach($datas['tables'][$datas['master']]['object'] as $key=>$object) {
                $retour[$key] = clone $object;
            }
            return $retour;
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

            foreach ($this->datas['associations'] as $key=>$association) {

                $associationObjects = $this->findAllAssociation($fetch[$table.'_id'], $association);
                
                $set = 'set'.ucFirst($key);
                
                $object->$set($associationObjects);

            }

        } else {
            print_r($pdoStatement->errorInfo());  // sensible à modifier
        }
        
        return $object;

    }    

    public function findAllAssociation(string $id, $association) { 

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);

        $find = "SELECT ".implode(', ', array_column($association['datas']['columns'], 'selectField')).
        " FROM ".strtolower(implode(', ', array_keys($association['datas']['tables']))).
        " WHERE ".$association['datas']['pivot']." = ?".
        (count($association['datas']['where']) === 0 ? "" : " AND ".implode(' AND ', $association['datas']['where']));

        if ($this->log) {
            error_log('===== findAllAssociations =======>   '.$id.'   '.$find);
        }

        $pdoStatement = $pdo->prepare($find);

        $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

        $objects = [];
        
        if ($pdoStatement->execute()) {
            while($fetch = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {

                $object = $this->constructObject($fetch, $association['datas']);

                $objects[] = $object;

            } 
        } else {
            print_r($pdoStatement->errorInfo());  // sensible à modifier
        }
        
        return $objects;

    }    

    public function findAll() { 

        $table = strtolower($this->rootClass);
        
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

                foreach ($this->datas['associations'] as $key=>$association) {
    
                    $associationObjects = $this->findAllAssociation($fetch[$table.'_id'], $association);
                    
                    $set = 'set'.ucFirst($key);
                    
                    $object->$set($associationObjects);

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

    private function deepClone ($object) {

        return clone $object;

    }

}
