<?php

require_once 'models/TypeMission.php';

class TypeMissionController {

    public function index() { 

        $nameMenu = "Types Mission";
        $nameEntity = "typemission";

        $fields = $this->getFields();

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/typemission') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                $row = TypeMission::find($explode[count($explode) - 1]);
                $row->delete();
                $row->persist();

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $row = $this->getRow(TypeMission::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                $row = $this->getRow(new TypeMission("0", "", ""));
                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new TypeMission ($_POST['id'], $_POST['typeMission']);
                    $row->update();
                    $row->persist();

                } else {

                    $row = new TypeMission (0, $_POST['typeMission']);
                    $row->insert();
                    $row->persist();

                }    

            }

            $rows = $this->getRows();
            require_once 'views/admin/entityList.php';

        }

        require_once 'views/footer.php';

    }

    private function getFields (): array
    {

        $fields[] = [
            'label' => 'Id',
            'name' => 'id',
            'type' => 'text'
        ];
        $fields[] = [
            'label' => 'Nom',
            'name' => 'typeMission',
            'type' => 'text'
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $typeMissions = [];

        foreach (TypeMission::findAll() as $typeMission) {

            $typeMissions[] = $this->getRow($typeMission);
        } 

        return $typeMissions;

    }

    private function getRow (TypeMission $typeMission): array 
    {

        return  [
            'id' => $typeMission->getId(),
            'typeMission' => $typeMission->getTypeMission()
];

    }
    
}