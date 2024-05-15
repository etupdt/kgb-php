<?php

require_once $_SERVER['DOCUMENT_ROOT'].'models/entities/Role.php';

class RoleController {

    private RoleRepository $roleRepository;

    public function __construct() {
 
        error_log('===== Role ====================================================================================================================>   ');

        $depth = 1;

        $this->roleRepository = new RoleRepository(0);

    }

    public function index() { 

        $help = false;

        $em = new EntityManager();

        $nameMenu = "Roles";
        $nameEntity = "role";

        $fields = $this->getFields();

        require_once $_SERVER['DOCUMENT_ROOT'].'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = $this->roleRepository->find($_GET['id']);
                        $em->remove($row);
                        $em->flush();
                        $rows = $this->getRows();
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow($this->roleRepository->find($_GET['id']));
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $role = new Role();
                        $role->setRole('');
                        $row = $this->getRow($role);
                        require_once $_SERVER['DOCUMENT_ROOT'].'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            if ($_POST['id'] !== "0") {

                $role = $this->roleRepository->find($_POST['id']); 

            } else {

                $role = new Role(0);

            }    

            $role->setRole($_POST['role']);

            $em->persist($role);
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
            'name' => 'role',
            'type' => 'text'
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $roles = [];

        foreach ($this->roleRepository->findAll() as $role) {

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