<?php

require_once 'models/TypeMission.php';

class TypeMissionController {

    public function index() { 

        $nameEntity = "typemission";

        $fields = $this->getFields(new TypeMission("0", ""));

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/typemission') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                TypeMission::deleteDatabase($explode[count($explode) - 1]);
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $fields = $this->getFields(TypeMission::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new TypeMission ($_POST['id'], $_POST['typeMission']);
                    $row->updateDatabase();

                } else {

                    $row = new TypeMission (0, $_POST['typeMission']);
                    $row->insertDatabase();
                }    

            }

            $rows = $this->getRows();
            require_once 'views/admin/entityList.php';

    }

        require_once 'views/footer.php';

    }

    private function getFields (TypeMission $row): array
    {

        $fields[] = [
            'label' => 'Id',
            'name' => 'id',
            'type' => 'text',
            'value' => $row->getId()
        ];
        $fields[] = [
            'label' => 'Nom',
            'name' => 'typeMission',
            'type' => 'text',
            'value' => $row->getTypeMission()
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $rows = [];
        
        foreach (TypeMission::findAll() as $row) {
            $rows[] = [
                'id' => $row->getId(),
                'typeMission' => $row->getTypeMission()
            ];
        } 
        
        return $rows;

    }

}