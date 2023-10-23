<?php

class ServiceEntityRepository {
    //call_user_func

    public static function repositoryFind($classe, string $id) { 

        $fields = ServiceEntityRepository::getFields ($classe);

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);

        $find = "SELECT id, ".implode(', ',$fields)." FROM ".strtolower($classe)." WHERE id = ?;";

        $pdoStatement = $pdo->prepare($find);

        $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

        if ($pdoStatement->execute()) {
            $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $classe);
            $object = $pdoStatement->fetch();
        } else {
            print_r($pdoStatement->errorInfo());  // sensible à modifier
        }

        return $object;

    }    

    public static function repositoryFindAll($classe) { 

        $fields = ServiceEntityRepository::getFields ($classe);

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
        
        $findAll = "SELECT id, ".$fields." FROM ".strtolower($classe);
        
        $pdoStatement = $pdo->prepare($findAll);

        $objects = [];

        if ($pdoStatement->execute()) {  
            $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $classe);
            while($object = $pdoStatement->fetch()) {
            
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

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
    
        $fields = array_keys(get_class_vars($classe));
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
        // echo '<pre>';
        // print_r($object);
    
    
        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
    
        $delete = "DELETE FROM ".strtolower($classe)." WHERE id = ?; ";
        
        $pdoStatement = $pdo->prepare($delete);
    
        $pdoStatement->bindValue(1, $object->getId(), PDO::PARAM_INT);
    
        if (!$pdoStatement->execute()) {  
          print_r($pdoStatement->errorInfo());  // sensible à modifier
        }  
    
    }

    private static function getFields ($classe) {

        $fields = [];

         foreach (get_class_vars($classe) as $field) {

            $class = get_class($field);
            if (isset($class)) {
                $fields[] = 'id_'.$field;
            } else {
                $fields[] = $field;
            }

        }

        return $fields;
    
    }

}
