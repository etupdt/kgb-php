<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/models/entities/Speciality.php';

class SpecialityController {

    private ActorRepository $actorRepository;
    private SpecialityRepository $specialityRepository;

    public function __construct() {
 
        error_log('===== Actors ====================================================================================================================>   ');

        $depth = 1;

        $this->specialityRepository = new SpecialityRepository(1);

        $this->actorRepository = new ActorRepository(0);

    }

    public function index() { 

        $help = false;

        $em = new EntityManager();

        $nameMenu = "Spécialités";
        $nameEntity = "speciality";

        $fields = $this->getFields();

        require_once $_SERVER['DOCUMENT_ROOT'].'/views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once $_SERVER['DOCUMENT_ROOT'].'/views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = $this->specialityRepository->find($_GET['id']);
                        $em->remove($row);
                        $em->flush();
                        $rows = $this->getRows();
                        require_once $_SERVER['DOCUMENT_ROOT'].'/views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow($this->specialityRepository->find($_GET['id']));
                        require_once $_SERVER['DOCUMENT_ROOT'].'/views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $speciality = new Speciality();
                        $speciality->setName('');
                        $row = $this->getRow($speciality);
                        require_once $_SERVER['DOCUMENT_ROOT'].'/views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            $actors = [];
            
            $idsActors = $_POST['actors'];

            foreach($idsActors as $id_actor) {
                $actors[] = [
                    'id_actor' => $id_actor,
                ];
            }

            if ($_POST['id'] !== "0") {

                $speciality = $this->specialityRepository->find($_POST['id']); 

            } else {

                $speciality = new Speciality();

            }    

            $speciality->setName($_POST['name']); 
            $speciality->setActors($actors); 

            $em->persist($speciality);
            $em->flush();

            $rows = $this->getRows();
            require_once $_SERVER['DOCUMENT_ROOT'].'/views/admin/entityList.php';

        }

        require_once $_SERVER['DOCUMENT_ROOT'].'/views/footer.php';

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
            'id' => 'name',
            'type' => 'text',
            'events' => [
                'onchange' => [
                    [
                        'function' => 'isRequired',
                    ]
                ]
            ]
        ];

        // $actors = [];

        // foreach ($this->actorRepository->findAll() as $actor) {
        //     $actors[] = [
        //         'id' => $actor->getId(),
        //         'name' => '<p>'.$actor->getIdentificationCode().' '.
        //         $actor->getFirstname().' '.
        //         $actor->getLastname().' '.
        //         $actor->getBirthdate(),
        //     ];
        // }

        // $fields[] = [
        //     'label' => 'Acteurs',
        //     'name' => 'actors',
        //     'id' => 'actors',
        //     'type' => 'multiSelect',
        //     'value' => $actors,
        //     'events' => [
        //         'select.onchange' => [
        //             [
        //                 'function' => 'isRequired'
        //             ],
        //             [
        //                 'function' => 'controlActor'
        //             ]
        //         ]
        //     ]
        // ];

        return $fields;

    }

    private function getRows (): array
    {

        $specialities = [];

        foreach ($this->specialityRepository->findAll() as $speciality) {

            $specialities[] = $this->getRow($speciality);

        } 

        return $specialities;

    }

    private function getRow (Speciality $speciality): array 
    {

        $actors = $speciality->getActors();

        return  [
            'id' => $speciality->getId(),
            'name' => $speciality->getName(),
            // 'actors' => isset($actors) ? $actors : [],
        ];

    }

}