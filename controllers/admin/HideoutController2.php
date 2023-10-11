<?php

require_once 'models/Hideout.php';

class HideoutController {

    public function index() { 

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            $hideout = new Hideout (0, $_POST['code'], $_POST['address'], $_POST['type']);

            $hideout->insertDatabase();

        }

        $nameEntity = "Planque";
        $method = "POST";

        $o = new Hideout('1', 'AlphaTango', '2 rue de la Paix', 'Chambre de bonne');

        $fields = array();

        $fields[] = [
            'label' => 'Code',
            'name' => 'code',
            'type' => 'text',
            'value' => $o->getCode()
        ];
        $fields[] = [
            'label' => 'Address',
            'name' => 'address',
            'type' => 'text',
            'value' => $o->getAddress()
        ];
        $fields[] = [
            'label' => 'Type',
            'name' => 'type',
            'type' => 'text',
            'value' => $o->getType()
        ];

        require_once 'views/header.php';
        require_once 'views/admin/hideoutForm.php';
        require_once 'views/footer.php';
    }

}