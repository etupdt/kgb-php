<?php

require_once $_SERVER['DOCUMENT_ROOT'].'models/EntityManager.php';
require_once $_SERVER['DOCUMENT_ROOT'].'models/entities/Mission.php';
require_once $_SERVER['DOCUMENT_ROOT'].'models/repositories/MissionRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'models/repositories/CountryRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'models/repositories/HideoutRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'models/repositories/StatutRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'models/repositories/SpecialityRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'models/repositories/TypeMissionRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'models/repositories/ActorRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'models/repositories/RoleRepository.php';

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

        $this->actorRepository = new ActorRepository(0);

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
        $this->countryRepository = new CountryRepository(0);
        $this->hideoutRepository = new HideoutRepository(0);
        $this->statutRepository = new StatutRepository(0);
        $this->specialityRepository = new SpecialityRepository(0);
        $this->typeMissionRepository = new TypeMissionRepository(0);
        $this->roleRepository = new RoleRepository(0);

        foreach ($this->roleRepository->findAll() as $role) {
            $this->roles[] = [
                'id' => $role->getId(),
                'role' => $role->getRole()
            ];
        }    
        
    }

    public function index() { 

        $help = true;
        $script = true;

        $em = new EntityManager();

        $nameMenu = "Missions";
        $nameEntity = "mission";

        $fields = $this->getFields();

        require_once $_SERVER['DOCUMENT_ROOT'].'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
        
                require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = $this->missionRepository->find($_GET['id']);
                        $em->remove($row);
                        $em->flush();
                        $rows = $this->getRows();
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow($this->missionRepository->find($_GET['id']));
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityForm.php';
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
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            $hideouts = [];
            
            $idsHideouts = $_POST['hideouts'];

            foreach($idsHideouts as $id_hideout) {
                $hideouts[] = [
                    'id_hideout' => $id_hideout,
                ];
            }

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

                $mission = $this->missionRepository->find($_POST['id']); 

            } else {

                $mission = new Mission ();

            }    

            $mission->setTitle($_POST['title']); 
            $mission->setDescription($_POST['description']); 
            $mission->setCodeName($_POST['codeName']); 
            $mission->setBegin($_POST['begin']); 
            $mission->setEnd($_POST['end']); 
            $mission->setCountry($this->countryRepository->find($_POST['id_country'])); 
            $mission->setStatut($this->statutRepository->find($_POST['id_statut'])); 
            $mission->setTypeMission($this->typeMissionRepository->find($_POST['id_typeMission'])); 
            $mission->setSpeciality($this->specialityRepository->find($_POST['id_speciality'])); 

            $mission->setHideouts($hideouts);
            $mission->setActors_roles($actorsRoles);
        
            $em->persist($mission);
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
                        'function' => 'controlOnChangeSpecialityMission'
                    ],
                ],
                'onblur' => [
                    [
                        'function' => 'isRequired'
                    ],
                    [
                        'function' => 'controlOnChangeSpecialityMission',
                        'param' => '["id_speciality", "Agent"]'
                    ],
                    [
                        'function' => 'controlOnBlurSpecialityMission',
                        'param' => '["id_speciality", "Agent"]'
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
                        'function' => 'controlOnChangeHideouts',
                    ]
                ],
                'select.onblur' => [
                    [
                        'function' => 'controlOnBlurHideouts',
                    ]
                ],
            ]
        ];

        foreach ($this->roles as $role) {

            $events = [];

            switch ($role['role']) {
                case 'Agent' : {
                    $events[] = [
                        'select.onchange' => [
                            [
                                'function' => 'controlOnChangeAgentsCibles',
                            ],
                            [
                                'function' => 'controlOnChangeSpecialityAgent',
                                'param' => '["id_speciality", "Agent"]'
                            ],        
                        ]
                    ];
                    $events[] = [
                        'select.onblur' => [
                            [
                                'function' => 'isRequired'
                            ],
                            [
                                'function' => 'controlOnBlurAgentsCibles',
                            ],
                            [
                                'function' => 'controlOnBlurSpecialityAgent',
                                'param' => '["id_speciality", "Agent"]'
                            ]
                        ]
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
                        'function' => 'controlOnChangeAgentsCibles',
                    ];
                    $onblur[] = [
                        'function' => 'controlOnBlurAgentsCibles',
                    ];
                    break;
                }
            }
            // echo '<pre>';
            // print_r($events);
            $fields[] = [
                'label' => $role['role'].'s',
                'name' => str_replace(' ', '_', $role['role']),
                'id' => str_replace(' ', '_', $role['role']),
                'type' => 'multiSelect',
                'value' => $this->actors,
                'events' => $events
            
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

        $actorsRoles = $mission->getActors_roles();

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