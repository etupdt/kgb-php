<?php

require_once 'models/Speciality.php';

class SpecialityController {

    public function index() { 

        $nameMenu = "Spécialités";
        $nameEntity = BASE_URL.ADMIN_URL."/specialite";

        $fields = $this->getFields();

        require_once 'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = Speciality::find($_GET['id']);
                        $row->delete();
                        $row->persist();
                        $rows = $this->getRows();
                        require_once 'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow(Speciality::find($_GET['id']));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $row = $this->getRow(new Speciality("0", "", []));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            if ($_POST['id'] !== "0") {

                $row = new Speciality ($_POST['id'], $_POST['name']);
                $row->update();
                $row->persist();

            } else {

                $row = new Speciality (0, $_POST['name'], []);
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
            'name' => 'name',
            'type' => 'text'
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $specialities = [];

        foreach (Speciality::findAll() as $speciality) {

            $specialities[] = $this->getRow($speciality);

        } 

        return $specialities;

    }

    private function getRow (Speciality $speciality): array 
    {

        return  [
            'id' => $speciality->getId(),
            'name' => $speciality->getName(),
        ];

    }

}