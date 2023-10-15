<?php

require_once 'models/Hideout.php';

class HideoutController {

    public function index() { 

        $nameMenu = "Planques";
        $nameEntity = BASE_URL.ADMIN_URL."/planque";

        $fields = $this->getFields();

        require_once 'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = Hideout::find($_GET['id']);
                        $row->delete();
                        $row->persist();
                        $rows = $this->getRows();
                        require_once 'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow(Hideout::find($_GET['id']));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $row = $this->getRow(new Hideout("0", "", "", "", 1));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            if ($_POST['id'] !== "0") {

                $row = new Hideout ($_POST['id'], $_POST['code'], $_POST['address'], $_POST['type'], $_POST['id_country']);
                $row->update();
                $row->persist();

            } else {

                $row = new Hideout (0, $_POST['code'], $_POST['address'], $_POST['type'], $_POST['id_country']);
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