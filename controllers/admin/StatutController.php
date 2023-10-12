<?php

require_once 'models/Statut.php';

class StatutController {

    public function index() { 

        $nameEntity = "statut";

        $fields = $this->getFields(new Statut("0", ""));

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/statut') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                Statut::deleteDatabase($explode[count($explode) - 1]);
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $fields = $this->getFields(Statut::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new Statut ($_POST['id'], $_POST['statut']);
                    $row->updateDatabase();

                } else {

                    $row = new Statut (0, $_POST['statut']);
                    $row->insertDatabase();
                }    

            }

            $rows = $this->getRows();
            require_once 'views/admin/entityList.php';

    }

        require_once 'views/footer.php';

    }

    private function getFields (Statut $row): array
    {

        $fields[] = [
            'label' => 'Id',
            'name' => 'id',
            'type' => 'text',
            'value' => $row->getId()
        ];
        $fields[] = [
            'label' => 'Nom',
            'name' => 'statut',
            'type' => 'text',
            'value' => $row->getStatut()
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $rows = [];
        
        foreach (Statut::findAll() as $row) {
            $rows[] = [
                'id' => $row->getId(),
                'statut' => $row->getStatut()
            ];
        } 
        
        return $rows;

    }

}