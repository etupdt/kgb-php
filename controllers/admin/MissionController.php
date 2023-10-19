<?php

require_once 'models/Mission.php';

class MissionController {

    private $roles = [];
    private $actors = [];

    public function __construct() {
 
        foreach (Actor::findAll() as $actor) {
            $this->actors[] = [
                'id' => $actor->getId(),
                'name' => '<p>'.$actor->getIdentificationCode().' '.
                    $actor->getFirstname().' '.
                    $actor->getLastname().' '.
                    $actor->getBirthdate(),
            ]; 
        }
    
        foreach (Role::findAll() as $role) {
            $this->roles[] = [
                'id' => $role->getId(),
                'role' => $role->getRole()
            ];
        }    
        
    }

    public function index() { 

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
                        $row = Mission::find($_GET['id']);
                        $row->delete();
                        $row->persist();
                        $rows = $this->getRows();
                        require_once 'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow(Mission::find($_GET['id']));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $row = $this->getRow(new Mission("0", "", "", "", "", "", 0, 0, 0, 0, [], []));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            foreach ($this->roles as $role) {

                $actors = $_POST[str_replace(' ', '_', $role['role'])];

                $actorsRoles = [];
    
                foreach($actors as $id_actor) {
                    $actorsRoles[] = [
                        'id_actor' => $id_actor,
                        'id_role' => $role['id']
                    ];
                }

            }
    
            if ($_POST['id'] !== "0") {

                $row = new Mission ($_POST['id'], $_POST['title'], $_POST['description'], $_POST['codeName'], $_POST['begin'], $_POST['end'], 
                    $_POST['id_country'], $_POST['id_statut'], $_POST['id_typeMission'], $_POST['id_speciality'], 
                    $_POST['hideouts']);

            } else {

                $row = new Mission (0, $_POST['title'], $_POST['description'], $_POST['codeName'], $_POST['begin'], $_POST['end'], 
                    $_POST['id_country'], $_POST['id_statut'], $_POST['id_typeMission'], $_POST['id_speciality'], 
                    $_POST['hideouts']);
                
            }    

            $row->setActorsRoles($actorsRoles);
        
            $row->insert();
            $row->persist();

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

        foreach (Statut::findAll() as $statut) {
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

        foreach (TypeMission::findAll() as $typeMission) {
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

        foreach (Speciality::findAll() as $speciality) {
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

        foreach (Hideout::findAll() as $hideout) {
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

        foreach (Mission::findAll() as $mission) {

            $missions[] = $this->getRow($mission);
        } 

        return $missions;

    }

    private function getRow (Mission $mission): array 
    {

        $row = [
            'id' => $mission->getId(),
            'title' => $mission->getTitle(),
            'description' => $mission->getDescription(),
            'codeName' => $mission->getCodeName(),
            'begin' => $mission->getBegin(),
            'end' => $mission->getEnd(),
            'id_country' => $mission->getId_country(),
            'id_statut' => $mission->getId_statut(),
            'id_typeMission' => $mission->getId_typeMission(),
            'id_speciality' => $mission->getId_speciality(),
            'hideouts' => $mission->getHideouts(),
        ];

        $actorsRoles = $mission->getActorsRoles();

        foreach ($this->roles as $role) {

            $actors = [];

            foreach($actorsRoles as $actorRole) {
                if ($actorRole['id_role'] == $role['id']) {
                    $actors[] = $actorRole['id_actor'];
                }
            }

            $row[str_replace(' ', '_', $role['role'])] = $actors;
    
        }
        
        return $row;

    }
    
}