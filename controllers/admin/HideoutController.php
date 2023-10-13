<?php

require_once 'models/Hideout.php';

class HideoutController {

    public function index() { 

        $nameEntity = "planque";

        $fields = $this->getFields();

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/planque') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                $row = Hideout::find($explode[count($explode) - 1]);
                $row->delete();
                $row->persist();

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $row = $this->getRow(Hideout::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                $row = $this->getRow(new Hideout("0", "", "", "", 1));
                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new Hideout ($_POST['id'], $_POST['code'], $_POST['address'], $_POST['type'], $_POST['id_country']);
                    $row->update();
                    $row->persist();

                } else {

                    $row = new Hideout (0, $_POST['code'], $_POST['address'], $_POST['type'], $_POST['id_country']);
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
            'label' => 'Code',
            'name' => 'code',
            'type' => 'text'
        ];
        $fields[] = [
            'label' => 'Adresse',
            'name' => 'address',
            'type' => 'text'
        ];
        $fields[] = [
            'label' => 'Type',
            'name' => 'type',
            'type' => 'text'
        ];

        $rows = [];

        $countries = [];

        foreach (Country::findAll() as $country) {
            $countries[$country->getId()] = $country->getName();
        }

        $fields[] = [
            'label' => 'Pays',
            'name' => 'id_country',
            'type' => 'select',
            'value' => $countries
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $actors = [];

        foreach (Hideout::findAll() as $hideout) {

            $hideouts[] = $this->getRow($hideout);
        } 

        return $hideouts;

    }

    private function getRow (Hideout $hideout): array 
    {

        return  [
            'id' => $hideout->getId(),
            'code' => $hideout->getCode(),
            'address' => $hideout->getAddress(),
            'type' => $hideout->getType(),
            'id_country' => $hideout->getId_country(),
        ];

    }
    
}