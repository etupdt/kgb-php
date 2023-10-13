<?php

require_once 'models/Role.php';

class RoleController {

    public function index() { 

        $nameEntity = "role";

        $fields = $this->getFields();

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/role') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                $row = Role::find($explode[count($explode) - 1]);
                $row->delete();
                $row->persist();

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $row = $this->getRow(Role::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                $row = $this->getRow(new Role("0", "", ""));
                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new Role ($_POST['id'], $_POST['role']);
                    $row->update();
                    $row->persist();

                } else {

                    $row = new Role (0, $_POST['role']);
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
            'name' => 'role',
            'type' => 'text'
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $roles = [];

        foreach (Role::findAll() as $role) {

            $roles[] = $this->getRow($role);
        } 

        return $roles;

    }

    private function getRow (Role $role): array 
    {

        return  [
            'id' => $role->getId(),
            'role' => $role->getRole()
    ];

    }
    
}