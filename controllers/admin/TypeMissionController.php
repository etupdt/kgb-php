<?php

require_once 'models/entities/TypeMission.php';

class TypeMissionController {

    public function index() { 

        $nameMenu = "Types Mission";
        $nameEntity = BASE_URL.ADMIN_URL."/typemission";

        $fields = $this->getFields();

        require_once 'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = TypeMission::find($_GET['id']);
                        $row->delete();
                        $row->persist();
                        $rows = $this->getRows();
                        require_once 'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow(TypeMission::find($_GET['id']));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $row = $this->getRow(new TypeMission("0", "", ""));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            if ($_POST['id'] !== "0") {

                $row = new TypeMission ($_POST['id'], $_POST['typeMission']);
                $row->update();
                $row->persist();

            } else {

                $row = new TypeMission (0, $_POST['typeMission']);
                $row->insert();
                $row->persist();

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