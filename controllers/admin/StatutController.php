<?php

require_once $_SERVER['DOCUMENT_ROOT'].'models/entities/Statut.php';

class StatutController {

    private StatutRepository $statutRepository;

    public function __construct() {
 
        error_log('===== Statut ====================================================================================================================>   ');

        $depth = 1;

        $this->statutRepository = new StatutRepository(0);

    }

    public function index() { 

        $help = false;

        $em = new EntityManager();

        $nameMenu = "Statuts Mission";
        $nameEntity = "statut";

        $fields = $this->getFields();

        require_once $_SERVER['DOCUMENT_ROOT'].'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = $this->statutRepository->find($_GET['id']);
                        $em->remove($row);
                        $em->flush();
                        $rows = $this->getRows();
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow($this->statutRepository->find($_GET['id']));
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $statut = new Statut();
                        $statut->setStatut('');
                        $row = $this->getRow($statut);
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            if ($_POST['id'] !== "0") {

                $statut = $this->statutRepository->find($_POST['id']); 

            } else {

                $statut = new Statut(0);

            }    

            $statut->setStatut($_POST['statut']);

            $em->persist($statut);
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
            'name' => 'statut',
            'type' => 'text'
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $statuts = [];

        foreach ($this->statutRepository->findAll() as $statut) {

            $statuts[] = $this->getRow($statut);
        } 

        return $statuts;

    }

    private function getRow (Statut $statut): array 
    {

        return  [
            'id' => $statut->getId(),
            'statut' => $statut->getStatut()
    ];

    }
    
}