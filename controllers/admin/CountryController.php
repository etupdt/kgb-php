<?php

require_once 'models/Country.php';

class CountryController {

    public function index() { 

        $nameEntity = "pays";

        $fields = $this->getFields(new Country("0", "", ""));

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/pays') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                Country::deleteDatabase($explode[count($explode) - 1]);
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $fields = $this->getFields(Country::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new Country ($_POST['id'], $_POST['name'], $_POST['nationality']);
                    $row->updateDatabase();

                } else {

                    $row = new Country (0, $_POST['name'], $_POST['nationality']);
                    $row->insertDatabase();
                }    

            }

            $rows = $this->getRows();
            require_once 'views/admin/entityList.php';

    }

        require_once 'views/footer.php';

    }

    private function getFields (Country $row): array
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
        $fields[] = [
            'label' => 'Nationalité',
            'name' => 'nationality',
            'type' => 'text',
            'value' => $row->getNationality()
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $rows = [];
        
        foreach (Country::findAll() as $row) {
            $rows[] = [
                'id' => $row->getId(),
                'name' => $row->getName(),
                'nationality' => $row->getNationality()
            ];
        } 
        
        return $rows;

    }
}