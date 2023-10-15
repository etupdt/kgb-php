<?php

require_once 'models/Country.php';

class CountryController {

    public function index() { 

        $nameMenu = "Pays";
        $nameEntity = BASE_URL.ADMIN_URL."/pays";

        $fields = $this->getFields();

        require_once 'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = Country::find($_GET['id']);
                        $row->delete();
                        $row->persist();
                        $rows = $this->getRows();
                        require_once 'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow(Country::find($_GET['id']));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $row = $this->getRow(new Country("0", "", ""));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            if ($_POST['id'] !== "0") {

                $row = new Country ($_POST['id'], $_POST['name'], $_POST['nationality']);
                $row->update();
                $row->persist();

            } else {

                $row = new Country (0, $_POST['name'], $_POST['nationality']);
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
        $fields[] = [
            'label' => 'Nationalité',
            'name' => 'nationality',
            'type' => 'text'
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $countries = [];

        foreach (Country::findAll() as $country) {

            $countries[] = $this->getRow($country);
        } 

        return $countries;

    }

    private function getRow (Country $country): array 
    {

        return  [
            'id' => $country->getId(),
            'name' => $country->getName(),
            'nationality' => $country->getNationality(),
        ];

    }
    
}