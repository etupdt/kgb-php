<?php

require_once 'models/Statut.php';

class StatutController {

    public function index() { 

        $nameMenu = "Statuts Mission";
        $nameEntity = BASE_URL.ADMIN_URL."/statut";

        $fields = $this->getFields();

        require_once 'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = Statut::find($_GET['id']);
                        $row->delete();
                        $row->persist();
                        $rows = $this->getRows();
                        require_once 'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow(Statut::find($_GET['id']));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $row = $this->getRow(new Statut("0", "", ""));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            if ($_POST['id'] !== "0") {

                $row = new Statut ($_POST['id'], $_POST['statut']);
                $row->update();
                $row->persist();

            } else {

                $row = new Statut (0, $_POST['statut']);
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
            'name' => 'statut',
            'type' => 'text'
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $statuts = [];

        foreach (Statut::findAll() as $statut) {

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