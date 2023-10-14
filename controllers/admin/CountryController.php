<?php

require_once 'models/Country.php';

class CountryController {

    public function index() { 

        $nameMenu = "Pays";
        $nameEntity = "pays";

        $fields = $this->getFields();

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/pays') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                $row = Country::find($explode[count($explode) - 1]);
                $row->delete();
                $row->persist();

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $row = $this->getRow(Country::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                $row = $this->getRow(new Country("0", "", ""));
                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new Country ($_POST['id'], $_POST['name'], $_POST['nationality']);
                    $row->update();
                    $row->persist();

                } else {

                    $row = new Country (0, $_POST['name'], $_POST['nationality']);
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