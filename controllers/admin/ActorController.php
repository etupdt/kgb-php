<?php

require_once $_SERVER['DOCUMENT_ROOT'].'models/entities/Actor.php';

class ActorController {

    private CountryRepository $countryRepository;
    private SpecialityRepository $specialityRepository;
    private ActorRepository $actorRepository;

    public function __construct() {
 
        error_log('===== Actors ====================================================================================================================>   ');

        $depth = 1;

        $this->actorRepository = new ActorRepository(1);

        $this->countryRepository = new CountryRepository(0);
        $this->specialityRepository = new SpecialityRepository(0);

    }

    public function index() { 

        $help = true;
        $script = true;

        $em = new EntityManager();

        $nameMenu = "Acteurs";
        $nameEntity = "actor";

        $fields = $this->getFields();

        require_once $_SERVER['DOCUMENT_ROOT'].'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = $this->actorRepository->find($_GET['id']);
                        $em->remove($row);
                        $em->flush();
                        $rows = $this->getRows();
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow($this->actorRepository->find($_GET['id']));
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $actor = new Actor();
                        $actor->setFirstname('');
                        $actor->setLastname('');
                        $actor->setBirthdate('');
                        $actor->setIdentificationCode('');
                        $row = $this->getRow($actor);
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            $specialities = [];
            
            $idsSpecialities = $_POST['specialities'];

            foreach($idsSpecialities as $id_speciality) {
                $specialities[] = [
                    'id_speciality' => $id_speciality,
                ];
            }

            if ($_POST['id'] !== "0") {

                $actor = $this->actorRepository->find($_POST['id']); 

            } else {

                $actor = new Actor();

            }    

            $actor->setFirstname($_POST['firstname']); 
            $actor->setlastname($_POST['lastname']); 
            $actor->setBirthdate($_POST['birthdate']); 
            $actor->setIdentificationCode($_POST['identificationCode']); 
            $actor->setCountry($this->countryRepository->find($_POST['id_country'])); 
            $actor->setSpecialities($specialities); 

            $em->persist($actor);
            $em->flush();

            $rows = $this->getRows();
            require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';

        }

        require_once $_SERVER['DOCUMENT_ROOT'].'views/footer.php';

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

        foreach ($this->countryRepository->findAll() as $country) {
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

        foreach ($this->specialityRepository->findAll() as $speciality) {
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
                        'function' => 'controlSpeciality'
                    ]
                ]
            ]
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $actors = [];

        foreach ($this->actorRepository->findAll() as $actor) {
            // echo '<pre>';
            // print_r($actor);
            $actors[] = $this->getRow($actor);
        } 

        return $actors;

    }

    private function getRow (Actor $actor): array 
    {

        $country = $actor->getCountry();
        $specialities = $actor->getSpecialities();

        return  [
            'id' => $actor->getId(),
            'firstname' => $actor->getFirstname(),
            'lastname' => $actor->getLastname(),
            'birthdate' => $actor->getBirthdate(),
            'identificationCode' => $actor->getIdentificationCode(),
            'id_country' => isset($country) ? $country->getId() : 0,
            'specialities' => isset($specialities) ? $specialities : [],
        ];

    }
    
}