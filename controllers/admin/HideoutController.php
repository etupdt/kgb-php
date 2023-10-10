<?php

require_once 'models/Hideout.php';

class HideoutController {

    public function index() { 

        if ($_SERVER['REQUEST_METHOD'] === "POST") {


            
        }

        $nameEntity = "Planque";
        $method = "post";

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