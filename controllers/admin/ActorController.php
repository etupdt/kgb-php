<?php

require_once 'models/Actor.php';

class ActorController {

    public function index() { 

        $nameMenu = "Acteurs";
        $nameEntity = "actor";

        $fields = $this->getFields();

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/actor') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                $row = Actor::find($explode[count($explode) - 1]);
                $row->delete();
                $row->persist();

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $row = $this->getRow(Actor::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                $row = $this->getRow(new Actor("0", "", "", "", "", 1, []));
                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new Actor ($_POST['id'], $_POST['firstname'], $_POST['lastname'], $_POST['birthdate'], $_POST['identificationCode'], $_POST['id_country'], $_POST['specialities']);
                    $row->update();
                    $row->persist();

                } else {

                    $row = new Actor (0, $_POST['firstname'], $_POST['lastname'], $_POST['birthdate'], $_POST['identificationCode'], $_POST['id_country'], $_POST['specialities']);
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
            'name' => 'firstname',
            'type' => 'text'
        ];
        $fields[] = [
            'label' => 'Prénom',
            'name' => 'lastname',
            'type' => 'text'
        ];
        $fields[] = [
            'label' => 'Date de naissance',
            'name' => 'birthdate',
            'type' => 'date'
        ];
        $fields[] = [
            'label' => 'Code identification',
            'name' => 'identificationCode',
            'type' => 'text'
        ];

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

        $specialities = [];

        foreach (Speciality::findAll() as $speciality) {
            $specialities[] = [
                'id' => $speciality->getId(),
                'name' => $speciality->getName()
            ];
        }

        $fields[] = [
            'label' => 'Spécialités',
            'name' => 'specialities',
            'type' => 'multiSelect',
            'value' => $specialities
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $actors = [];

        foreach (Actor::findAll() as $actor) {

            $actors[] = $this->getRow($actor);
        } 

        return $actors;

    }

    private function getRow (Actor $actor): array 
    {

        $country = Country::find($actor->getId_country());

        return  [
            'id' => $actor->getId(),
            'firstname' => $actor->getFirstname(),
            'lastname' => $actor->getLastname(),
            'birthdate' => $actor->getBirthdate(),
            'identificationCode' => $actor->getIdentificationCode(),
            'id_country' => $actor->getId_country(),
            'specialities' => $actor->getSpecialities()
        ];

    }
    
}