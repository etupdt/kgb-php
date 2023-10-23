<?php

require_once 'models/entities/Role.php';

class RoleController {

    public function index() { 

        $nameMenu = "Roles";
        $nameEntity = BASE_URL.ADMIN_URL."/role";

        $fields = $this->getFields();

        require_once 'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = Role::find($_GET['id']);
                        $row->delete();
                        $row->persist();
                        $rows = $this->getRows();
                        require_once 'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow(Role::find($_GET['id']));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $row = $this->getRow(new Role("0", "", ""));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            if ($_POST['id'] !== "0") {

                $row = new Role ($_POST['id'], $_POST['role']);
                $row->update();
                $row->persist();

            } else {

                $row = new Role (0, $_POST['role']);
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