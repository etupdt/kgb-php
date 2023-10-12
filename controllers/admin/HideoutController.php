<?php

require_once 'models/Hideout.php';

class HideoutController {

    public function index() { 

        $nameEntity = "planque";

        $fields = $this->getFields(new Hideout("0", "", "", "", ""));

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/planque') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                Hideout::deleteDatabase($explode[count($explode) - 1]);
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $fields = $this->getFields(Hideout::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new Hideout ($_POST['id'], $_POST['code'], $_POST['address'], $_POST['type'], $_POST['id_country']);
                    $row->updateDatabase();

                } else {

                    $row = new Hideout (0, $_POST['code'], $_POST['address'], $_POST['type'], $_POST['id_country']);
                    $row->insertDatabase();
                }    

            }

            $rows = $this->getRows();
            require_once 'views/admin/entityList.php';

    }

        require_once 'views/footer.php';

    }

    private function getFields (Hideout $row): array
    {

        $fields[] = [
            'label' => 'Id',
            'name' => 'id',
            'type' => 'text',
            'value' => $row->getId()
        ];
        $fields[] = [
            'label' => 'Code',
            'name' => 'code',
            'type' => 'text',
            'value' => $row->getCode()
        ];
        $fields[] = [
            'label' => 'Adresse',
            'name' => 'address',
            'type' => 'text',
            'value' => $row->getAddress()
        ];
        $fields[] = [
            'label' => 'Type',
            'name' => 'type',
            'type' => 'text',
            'value' => $row->getType()
        ];

        $rows = [];

        foreach (Country::findAll() as $country) {
            $rows[] = [
                'id' => $country->getId(),
                'name' => $country->getName()
            ];
        }

        $fields[] = [
            'label' => 'Pays',
            'name' => 'id_country',
            'type' => 'select',
            'value' => $rows,
            'selected' => [$row->getId_country()]
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $rows = [];

        foreach (Hideout::findAll() as $row) {

            $country = Country::find($row->getId_country());

            $rows[] = [
                'id' => $row->getId(),
                'code' => $row->getCode(),
                'address' => $row->getAddress(),
                'type' => $row->getType(),
                'id_country' => $country->getName()
            ];
        } 
        
        return $rows;

    }
}