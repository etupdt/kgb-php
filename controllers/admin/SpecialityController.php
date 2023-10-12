<?php

require_once 'models/Speciality.php';

class SpecialityController {

    public function index() { 

        $nameEntity = "specialite";

        $fields = $this->getFields(new Speciality("0", ""));

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/specialite') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                Speciality::deleteDatabase($explode[count($explode) - 1]);
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $fields = $this->getFields(Speciality::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new Speciality ($_POST['id'], $_POST['name']);
                    $row->updateDatabase();

                } else {

                    $row = new Speciality (0, $_POST['name']);
                    $row->insertDatabase();
                }    

            }

            $rows = $this->getRows();
            require_once 'views/admin/entityList.php';

    }

        require_once 'views/footer.php';

    }

    private function getFields (Speciality $row): array
    {

        $fields[] = [
            'label' => 'Id',
            'name' => 'id',
            'type' => 'text',
            'value' => $row->getId()
        ];
        $fields[] = [
            'label' => 'Nom',
            'name' => 'name',
            'type' => 'text',
            'value' => $row->getName()
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $rows = [];
        
        foreach (Speciality::findAll() as $row) {
            $rows[] = [
                'id' => $row->getId(),
                'name' => $row->getName()
            ];
        } 
        
        return $rows;

    }

}