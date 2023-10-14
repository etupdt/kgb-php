<?php

require_once 'models/Speciality.php';

class SpecialityController {

    public function index() { 

        $nameMenu = "Spécialités";
        $nameEntity = "specialite";

        $fields = $this->getFields();

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/specialite') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                $row = Speciality::find($explode[count($explode) - 1]);
                $row->delete();
                $row->persist();

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $row = $this->getRow(Speciality::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                $row = $this->getRow(new Speciality("0", "", []));
                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new Speciality ($_POST['id'], $_POST['name']);
                    $row->update();
                    $row->persist();

                } else {

                    $row = new Speciality (0, $_POST['name'], []);
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