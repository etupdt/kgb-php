<?php

require_once 'models/EntityManager.php';
require_once 'models/entities/Mission.php';
require_once 'models/repositories/MissionRepository.php';
require_once 'models/repositories/CountryRepository.php';
require_once 'models/repositories/HideoutRepository.php';
require_once 'models/repositories/StatutRepository.php';
require_once 'models/repositories/SpecialityRepository.php';
require_once 'models/repositories/TypeMissionRepository.php';
require_once 'models/repositories/ActorRepository.php';
require_once 'models/repositories/RoleRepository.php';

class MissionController {

    private $roles = [];
    private $actors = [];

    private MissionRepository $missionRepository;
    private CountryRepository $countryRepository;
    private HideoutRepository $hideoutRepository;
    private StatutRepository $statutRepository;
    private SpecialityRepository $specialityRepository;
    private TypeMissionRepository $typeMissionRepository;
    private ActorRepository $actorRepository;
    private RoleRepository $roleRepository;

    public function __construct() {
 
        error_log('===== Missions ====================================================================================================================>   ');

        $depth = 1;

        $this->actorRepository = new ActorRepository($depth);

        foreach ($this->actorRepository->findAll() as $actor) {
            $this->actors[] = [
                'id' => $actor->getId(),
                'name' => '<p>'.$actor->getIdentificationCode().' '.
                    $actor->getFirstname().' '.
                    $actor->getLastname().' '.
                    $actor->getBirthdate(),
            ]; 
        }
    
        $this->missionRepository = new MissionRepository(2);
        $this->countryRepository = new CountryRepository($depth);
        $this->hideoutRepository = new HideoutRepository($depth);
        $this->statutRepository = new StatutRepository($depth);
        $this->specialityRepository = new SpecialityRepository($depth);
        $this->typeMissionRepository = new TypeMissionRepository($depth);
        $this->roleRepository = new RoleRepository($depth);

        foreach ($this->roleRepository->findAll() as $role) {
            $this->roles[] = [
                'id' => $role->getId(),
                'role' => $role->getRole()
            ];
        }    
        
    }

    public function index() { 

        $em = new EntityManager();

        $nameMenu = "Missions";
        $nameEntity = BASE_URL.ADMIN_URL."/mission";

        $fields = $this->getFields();

        require_once 'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
        
                require_once 'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = $this->missionRepository->find($_GET['id']);
                        $em->remove($row);
                        $em->flush();
                        $rows = $this->getRows();
                        require_once 'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow($this->missionRepository->find($_GET['id']));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $mission = new Mission();
                        $mission->setTitle(''); 
                        $mission->setDescription(''); 
                        $mission->setCodeName(''); 
                        $mission->setBegin(''); 
                        $mission->setEnd(''); 
                        // $mission->setCountry(null);
                        // $mission->setStatut(null);
                        // $mission->setTypeMission(null);
                        // $mission->setSpeciality(null);

                        $row = $this->getRow($mission);
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            $actorsRoles = [];
            
            foreach ($this->roles as $role) {

                $actors = $_POST[str_replace(' ', '_', $role['role'])];

                foreach($actors as $id_actor) {
                    $actorsRoles[] = [
                        'id_actor' => $id_actor,
                        'id_role' => $role['id']
                    ];
                }

            }
    
            if ($_POST['id'] !== "0") {

                $row = $this->missionRepository->find($_POST['id']); 

            } else {

                $row = new Mission ();

            }    

            $mission->setTitle($_POST['title']); 
            $mission->setDescription($_POST['description']); 
            $mission->setCodeName($_POST['codeName']); 
            $mission->setBegin($_POST['begin']); 
            $mission->setEnd($_POST['end']); 
            $mission->setCountry($this->countryRepository->find($_POST['id_country'])); 
            $mission->setStatut($statutRepository->find($_POST['id_statut'])); 
            $mission->setTypeMission($typeMissionRepository->find($_POST['id_typeMission'])); 
            $mission->setSpeciality($specialityRepository->find($_POST['id_speciality'])); 

            $row->setActorsRoles($actorsRoles);
        
            $em->persist($row);
            $em->flush();

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
            'label' => 'Titre',
            'name' => 'title',
            'id' => 'title',
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
            'label' => 'Description',
            'name' => 'description',
            'id' => 'description',
            'type' => 'textarea',
            'events' => [
                'onchange' => [
                    [
                        'function' => 'isRequired',
                    ]
                ]
            ]
        ];
        $fields[] = [
            'label' => 'Nom de code',
            'name' => 'codeName',
            'id' => 'codeName',
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
            'label' => 'Date de début',
            'name' => 'begin',
            'id' => 'begin',
            'type' => 'date',
            'events' => [
                'onchange' => [
                    [
                        'function' => 'isRequired'
                    ]
                ]
            ]
        ];
        $fields[] = [
            'label' => 'Date de fin',
            'name' => 'end',
            'id' => 'end',
            'type' => 'date',
            'events' => [
                'onchange' => [
                    [
                        'function' => 'isRequired'
                    ]
                ]
            ]
        ];

        $countries = ['0' => ''];

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
                        'function' => 'isRequired',
                    ],
                    [
                        'function' => 'controlPaysMission',
                        'param' => 'hideouts',
                    ],
                    [
                        'function' => 'controlPaysMission',
                        'param' => 'Contact',
                    ]
                ]
            ]
        ];

        $statuts = ['0' => ''];

        foreach ($this->statutRepository->findAll() as $statut) {
            $statuts[$statut->getId()] = $statut->getStatut();
        }

        $fields[] = [
            'label' => 'Statut de mission',
            'name' => 'id_statut',
            'id' => 'id_statut',
            'type' => 'select',
            'value' => $statuts,
            'events' => [
                'onchange' => [
                    [
                        'function' => 'isRequired'
                    ]
                ]
            ]
        ];

        $typeMissions = ['0' => ''];

        foreach ($this->typeMissionRepository->findAll() as $typeMission) {
            $typeMissions[$typeMission->getId()] = $typeMission->getTypeMission();
        }

        $fields[] = [
            'label' => 'Type de mission',
            'name' => 'id_typeMission',
            'id' => 'id_typeMission',
            'type' => 'select',
            'value' => $typeMissions,
            'events' => [
                'onchange' => [
                    [
                        'function' => 'isRequired'
                    ]
                ]
            ]
        ];

        $specialities = ['0' => ''];

        foreach ($this->specialityRepository->findAll() as $speciality) {
            $specialities[$speciality->getId()] = $speciality->getName();
        }

        $fields[] = [
            'label' => 'Spécialité requise',
            'name' => 'id_speciality',
            'id' => 'id_speciality',
            'type' => 'select',
            'value' => $specialities,
            'events' => [
                'onchange' => [
                    [
                        'function' => 'isRequired'
                    ],
                    [
                        'function' => 'controlSpecialityMission'
                    ]
                ]
            ]
        ];
        
        $hideouts = [];

        foreach ($this->hideoutRepository->findAll() as $hideout) {
            $hideouts[] = [
                'id' => $hideout->getId(),
                'name' => $hideout->getCode().' '.$hideout->getAddress().' '.$hideout->getType()
            ];
        }

        $fields[] = [
            'label' => 'Planques de la mission',
            'name' => 'hideouts',
            'id' => 'hideouts',
            'type' => 'multiSelect',
            'value' => $hideouts,
            'events' => [
                'select.onchange' => [
                    [
                        'function' => 'isRequired'
                    ],
                    [
                        'function' => 'controlPaysMission',
                        'param' => [
                            'hideouts'
                        ]
                    ]
                ],
                'option.onmouseover' => [
                    [
                        'function' => 'displayAttributes',
                        'param' => [
                            'countries'
                        ]
                    ]
                ]
            ]
        ];

        foreach ($this->roles as $role) {

            $onchange = [];

            $onchange[] = [
                'function' => 'isRequired'
            ];

            switch ($role['role']) {
                case 'Agent' : {
                    $onchange[] = [
                        'function' => 'controlPaysCiblesAgents',
                    ];
                    $onchange[] = [
                        'function' => 'controlSpecialityMission',
                    ];
                    break;
                }
                case 'Contact' : {
                    $onchange[] = [
                        'function' => 'controlPaysMission',
                        'param' => [
                            'Contact',
                        ]
                    ];
                    break;
                }
                case 'Cible' : {
                    $onchange[] = [
                        'function' => 'controlPaysCiblesAgents',
                    ];
                    break;
                }
            }
            
            $fields[] = [
                'label' => $role['role'].'s',
                'name' => str_replace(' ', '_', $role['role']),
                'id' => str_replace(' ', '_', $role['role']),
                'type' => 'multiSelect',
                'value' => $this->actors,
                'events' => [
                    'onchange' => $onchange
                ],
                'events' => [
                    'select.onchange' => $onchange,
                    'option.onmouseover' => [
                        [
                            'function' => 'displayAttributes',
                            'param' => [
                                'countries',
                                'specialities'
                            ]
                        ]
                    ]
                ]
            
            ];

        }    

        return $fields;

    }

    private function getRows (): array
    {

        $missions = [];

        foreach ($this->missionRepository->findAll() as $mission) {
            // echo '<pre>';
            // print_r($mission);

            $missions[] = $this->getRow($mission);
        } 

        return $missions;

    }

    private function getRow (Mission $mission): array 
    {

        $country = $mission->getCountry();
        $statut = $mission->getStatut();
        $typeMission = $mission->getTypeMission();
        $speciality = $mission->getSpeciality();

        $row = [
            'object' => $mission,
            'id' => $mission->getId(),
            'title' => $mission->getTitle(),
            'description' => $mission->getDescription(),
            'codeName' => $mission->getCodeName(),
            'begin' => $mission->getBegin(),
            'end' => $mission->getEnd(),
            'id_country' => isset($country) ? $country->getId() : 0,
            'id_statut' => isset($statut) ? $statut->getId() : 0,
            'id_typeMission' => isset($typeMission) ? $typeMission->getId() : 0,
            'id_speciality' => isset($speciality) ? $speciality->getId() : 0,
            'hideouts' => $mission->getHideouts(),
        ];

        $actorsRoles = $mission->getActorsRoles();

        foreach ($this->roles as $role) {

            $actors = [];

            foreach($actorsRoles as $actorRole) {
                if ($actorRole['role']->getId() == $role['id']) {
                    $actors[] = ['Actor' => $actorRole['actor']];
                }
            }

            $row[str_replace(' ', '_', $role['role'])] = $actors;
    
        }
        
        return $row;

    }
    
}