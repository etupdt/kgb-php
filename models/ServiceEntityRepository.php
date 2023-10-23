<?php

class ServiceEntityRepository {

    public static function find(string $id) { 

        $pdo = new PDO(Database::$host, Database::$username, Database::$password);
        
        $find = "SELECT id, title, description, codeName, begin, end, id_country, id_statut, id_typeMission, id_speciality 
        FROM mission WHERE id = ?;";

        $pdoStatement = $pdo->prepare($find);

        $pdoStatement->bindValue(1, $id, PDO::PARAM_INT);

        if ($pdoStatement->execute()) {
            $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Mission');
            $object = $pdoStatement->fetch();
        } else {
            print_r($pdoStatement->errorInfo());  // sensible à modifier
        }

        $object->setHideouts(Mission::getHideoutsDatabase($object->getId()));
        $object->setActorsRoles(Mission::getActorsRolesDatabase($object->getId()));
    
        return $object;

    }    

    public static function findAll() { 

    $pdo = new PDO(Database::$host, Database::$username, Database::$password);

    $findAll = "SELECT id, title, description, codeName, begin, end, 
    id_country, id_statut, id_typeMission, id_speciality
    FROM mission";
    
    $pdoStatement = $pdo->prepare($findAll);

    $missions = [];

    if ($pdoStatement->execute()) {  
        $pdoStatement->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, __CLASS__);
        while($object = $pdoStatement->fetch()) {
        
        $object->setHideouts(Mission::getHideoutsDatabase($object->getId()));
        $object->setActorsRoles(Mission::getActorsRolesDatabase($object->getId()));

        $objects[] = $object;

        }
    } else {
        print_r($pdoStatement->errorInfo());  // sensible à modifier
    }  

    return $objects;
    
    }  

}
