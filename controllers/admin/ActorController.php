<?php

require_once 'models/entities/Actor.php';

class ActorController {

    public function index() { 

        $nameMenu = "Acteurs";
        $nameEntity = BASE_URL.ADMIN_URL."/acteur";

        $fields = $this->getFields();

        require_once 'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = Actor::find($_GET['id']);
                        $row->delete();
                        $row->persist();
                        $rows = $this->getRows();
                        require_once 'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow(Actor::find($_GET['id']));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $row = $this->getRow(new Actor("0", "", "", "", "", 1, []));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            if ($_POST['id'] !== "0") {

                $row = new Actor ($_POST['id'], $_POST['firstname'], $_POST['lastname'], $_POST['birthdate'], $_POST['identificationCode'], $_POST['id_country'], $_POST['specialities']);
                $row->update();
                $row->persist();

            } else {

                $row = new Actor (0, $_POST['firstname'], $_POST['lastname'], $_POST['birthdate'], $_POST['identificationCode'], $_POST['id_country'], $_POST['specialities']);
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
            'name' => 'firstname',
            'id' => 'firstname',
            'type' => 'text',
            'events' => [
                'onchange' => [
                    [
                        'function' => 'isRequired',
                    ]
                ]
            ]
        ];
        $fields[] = [
            'label' => 'Prénom',
            'name' => 'lastname',
            'id' => 'lastname',
            'type' => 'text',
            'events' => [
                'onchange' => [
                    [
                        'function' => 'isRequired',
                    ]
                ]
            ]
        ];
        $fields[] = [
            'label' => 'Date de naissance',
            'name' => 'birthdate',
            'id' => 'birthdate',
            'type' => 'date',
            'events' => [
                'onchange' => [
                    [
                        'function' => 'isRequired',
                    ]
                ]
            ]
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
            'id' => 'id_country',
            'type' => 'select',
            'value' => $countries,
            'events' => [
                'onchange' => [
                    [
                        'function' => 'isRequired'
                    ],
                    [
                        'function' => 'controlActor'
                    ]
                ]
            ]
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
            'id' => 'specialities',
            'type' => 'multiSelect',
            'value' => $specialities,
            'events' => [
                'select.onchange' => [
                    [
                        'function' => 'isRequired'
                    ],
                    [
                        'function' => 'controlActor'
                    ]
                ]
            ]
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