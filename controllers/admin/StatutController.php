<?php

require_once 'models/Statut.php';

class StatutController {

    public function index() { 

        $nameMenu = "Statuts Mission";
        $nameEntity = "statut";

        $fields = $this->getFields();

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/statut') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                $row = Statut::find($explode[count($explode) - 1]);
                $row->delete();
                $row->persist();

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $row = $this->getRow(Statut::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                $row = $this->getRow(new Statut("0", "", ""));
                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new Statut ($_POST['id'], $_POST['statut']);
                    $row->update();
                    $row->persist();

                } else {

                    $row = new Statut (0, $_POST['statut']);
                    $row->insert();
                    $row->persist();

                }    

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