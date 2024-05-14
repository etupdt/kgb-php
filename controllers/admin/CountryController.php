<?php

require_once 'models/entities/Country.php';

class CountryController {

    private CountryRepository $countryRepository;

    public function __construct() {
 
        error_log('===== Country ====================================================================================================================>   ');

        $depth = 1;

        $this->countryRepository = new CountryRepository(0);

    }

    public function index() { 

        $help = false;
        $script = false;

        $em = new EntityManager();

        $nameMenu = "Pays";
        $nameEntity = "country";

        $fields = $this->getFields();

        require_once 'views/header.php';

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if (! isset($_GET['a']) || $_GET['a'] === 'c') {
                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';
            } else {
                switch ($_GET['a']) {
                    case 'd' : {
                        $row = $this->countryRepository->find($_GET['id']);
                        $em->remove($row);
                        $em->flush();
                        $rows = $this->getRows();
                        require_once 'views/admin/entityList.php';
                        break;
                    }
                    case 'u' : {
                        $row = $this->getRow($this->countryRepository->find($_GET['id']));
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                    case 'i' : {
                        $country = new Country();
                        $country->setName('');
                        $country->setNationality('');
                        $row = $this->getRow($country);
                        require_once 'views/admin/entityForm.php';
                        break;
                    }
                }
            }

        } else {

            if ($_POST['id'] !== "0") {

                $country = $this->countryRepository->find($_POST['id']); 

            } else {

                $country = new Country(0);

            }    

            $country->setName($_POST['name']);
            $country->setNationality($_POST['nationality']);

            $em->persist($country);
            $em->flush();

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
            'name' => 'name',
            'type' => 'text'
        ];
        $fields[] = [
            'label' => 'Nationalité',
            'name' => 'nationality',
            'type' => 'text'
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $countries = [];

        foreach ($this->countryRepository->findAll() as $country) {

            $countries[] = $this->getRow($country);
        } 

        return $countries;

    }

    private function getRow (Country $country): array 
    {

        return  [
            'id' => $country->getId(),
            'name' => $country->getName(),
            'nationality' => $country->getNationality(),
        ];

    }
    
}