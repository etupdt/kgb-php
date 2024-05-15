<?php

require_once $_SERVER['DOCUMENT_ROOT'].'models/entities/TypeMission.php';

class TypeMissionController {

    private TypeMissionRepository $typeMissionRepository;

    public function __construct() {
 
        error_log('===== TypeMission ====================================================================================================================>   ');

        $depth = 1;

        $this->typeMissionRepository = new TypeMissionRepository(0);

    }

    public function index() { 

        $help = false;

        $em = new EntityManager();

        $nameMenu = "TypeMissions";
        $nameEntity = "typemission";

        $fields = $this->getFields();

        require_once $_SERVER['DOCUMENT_ROOT'].'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = $this->typeMissionRepository->find($_GET['id']);
                        $em->remove($row);
                        $em->flush();
                        $rows = $this->getRows();
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow($this->typeMissionRepository->find($_GET['id']));
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $typeMission = new TypeMission();
                        $typeMission->setTypeMission('');
                        $row = $this->getRow($typeMission);
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            if ($_POST['id'] !== "0") {

                $typeMission = $this->typeMissionRepository->find($_POST['id']); 

            } else {

                $typeMission = new TypeMission(0);

            }    

            $typeMission->setTypeMission($_POST['typeMission']);

            $em->persist($typeMission);
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
            'name' => 'typeMission',
            'type' => 'text'
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $typeMissions = [];

        foreach ($this->typeMissionRepository->findAll() as $typeMission) {

            $typeMissions[] = $this->getRow($typeMission);
        } 

        return $typeMissions;

    }

    private function getRow (TypeMission $typeMission): array 
    {

        return  [
            'id' => $typeMission->getId(),
            'typeMission' => $typeMission->getTypeMission()
    ];

    }
    
}