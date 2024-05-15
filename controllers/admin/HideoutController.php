<?php

require_once $_SERVER['DOCUMENT_ROOT'].'models/entities/Hideout.php';

class HideoutController {

    private CountryRepository $countryRepository;
    private MissionRepository $missionRepository;
    private HideoutRepository $hideoutRepository;

    public function __construct() {
 
        error_log('===== Hideout ====================================================================================================================>   ');

        $depth = 1;

        $this->hideoutRepository = new HideoutRepository(1);

        $this->countryRepository = new CountryRepository(0);
        $this->missionRepository = new MissionRepository(0);

    }

    public function index() { 

        $help = false;
        $script = true;

        $em = new EntityManager();

        $nameMenu = "Planques";
        $nameEntity = "hideout";

        $fields = $this->getFields();

        require_once $_SERVER['DOCUMENT_ROOT'].'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = $this->hideoutRepository->find($_GET['id']);
                        $em->remove($row);
                        $em->flush();
                        $rows = $this->getRows();
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow($this->hideoutRepository->find($_GET['id']));
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $hideout = new Hideout();
                        $hideout->setCode('');
                        $hideout->setAddress('');
                        $hideout->setType('');
                        $row = $this->getRow($hideout);
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            $missions = [];
            
            $idsMissions = $_POST['missions'];

            foreach($idsMissions as $id_mission) {
                $missions[] = [
                    'id_mission' => $id_mission,
                ];
            }

            if ($_POST['id'] !== "0") {

                $hideout = $this->hideoutRepository->find($_POST['id']); 

            } else {

                $hideout = new Hideout();

            }    

            $hideout->setCode($_POST['code']); 
            $hideout->setAddress($_POST['address']); 
            $hideout->setType($_POST['type']); 
            $hideout->setCountry($this->countryRepository->find($_POST['id_country'])); 
            $hideout->setMissions($missions); 

            $em->persist($hideout);
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
                        'function' => 'controlHideout'
                    ]
                ]
            ]
        ];

        $missions = [];

        foreach ($this->missionRepository->findAll() as $mission) {
            $missions[] = [
                'id' => $mission->getId(),
                'name' => $mission->getTitle()
            ];
        }

        $fields[] = [
            'label' => 'Missions',
            'name' => 'missions',
            'id' => 'missions',
            'type' => 'multiSelect',
            'value' => $missions,
            'events' => [
                'select.onchange' => [
                    [
                        'function' => 'isRequired'
                    ],
                    [
                        'function' => 'controlMission'
                    ]
                ]
            ]
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $hideouts = [];

        foreach ($this->hideoutRepository->findAll() as $hideout) {
            $hideouts[] = $this->getRow($hideout);
        } 

        return $hideouts;

    }

    private function getRow (Hideout $hideout): array 
    {

        $country = $hideout->getCountry();
        $missions = $hideout->getMissions();

        return  [
            'id' => $hideout->getId(),
            'code' => $hideout->getCode(),
            'address' => $hideout->getAddress(),
            'type' => $hideout->gettype(),
            'id_country' => isset($country) ? $country->getId() : 0,
            'missions' => isset($missions) ? $missions : [],
        ];

    }
    
}