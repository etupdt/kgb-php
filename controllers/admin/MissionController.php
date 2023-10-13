<?php

require_once 'models/Mission.php';

class MissionController {

    public function index() { 

        $nameEntity = "mission";

        $fields = $this->getFields();

        require_once 'views/header.php';

        $explode = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

        if ($_SERVER['REQUEST_METHOD'] === "GET") {

            if ($_SERVER['REQUEST_URI'] === '/mission') {

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/d/")) {

                $row = Mission::find($explode[count($explode) - 1]);
                $row->delete();
                $row->persist();

                $rows = $this->getRows();
                require_once 'views/admin/entityList.php';

            } elseif (strpos($_SERVER['REQUEST_URI'], "/u/")) {
            
                $row = $this->getRow(Mission::find($explode[count($explode) - 1]));
                require_once 'views/admin/entityForm.php';

            } else {

                $row = $this->getRow(new Mission("0", "", "", "", "", "", 1, 1, 1, 1, [], []));
                require_once 'views/admin/entityForm.php';

            }    

        } else {

            if ($explode[count($explode) - 1] !== "c") {
                
                if ($_POST['id'] !== "0") {

                    $row = new Mission ($_POST['id'], $_POST['title'], $_POST['description'], $_POST['codeName'], $_POST['begin'], $_POST['end'], 
                        $_POST['id_country'], $_POST['id_statut'], $_POST['id_typeMission'], $_POST['id_speciality'], 
                        $_POST['hideouts'], $_POST['actors']);
                    $row->update();
                    $row->persist();

                } else {

                    $row = new Mission (0, $_POST['title'], $_POST['description'], $_POST['codeName'], $_POST['begin'], $_POST['end'], 
                        $_POST['id_country'], $_POST['id_statut'], $_POST['id_typeMission'], $_POST['id_speciality'], 
                        $_POST['hideouts'], $_POST['actors']);
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
            'label' => 'Titre',
            'name' => 'title',
            'type' => 'text'
        ];
        $fields[] = [
            'label' => 'Description',
            'name' => 'description',
            'type' => 'text'
        ];
        $fields[] = [
            'label' => 'Nom de code',
            'name' => 'codeName',
            'type' => 'text'
        ];
        $fields[] = [
            'label' => 'Date de début',
            'name' => 'begin',
            'type' => 'text'
        ];
        $fields[] = [
            'label' => 'Date de fin',
            'name' => 'end',
            'type' => 'text'
        ];

        $countries = [];

        foreach (Country::findAll() as $country) {
            $countries[$country->getId()] = $country->getName();
        }

        $fields[] = [
            'label' => 'Pays',
            'name' => 'id_country',
            'type' => 'select',
            'value' => $countries
        ];

        $statuts = [];

        foreach (Country::findAll() as $statut) {
            $statuts[$statut->getId()] = $statut->getStatut();
        }

        $fields[] = [
            'label' => 'Staut de mission',
            'name' => 'id_statut',
            'type' => 'select',
            'value' => $statuts
        ];

        $typeMissions = [];

        foreach (Country::findAll() as $typeMission) {
            $typeMissions[$typeMission->getId()] = $typeMission->getName();
        }

        $fields[] = [
            'label' => 'Type de mission',
            'name' => 'id_typemission',
            'type' => 'select',
            'value' => $typeMissions
        ];

        $specialities = [];

        foreach (Country::findAll() as $speciality) {
            $specialities[$speciality->getId()] = $speciality->getName();
        }

        $fields[] = [
            'label' => 'Spécialité de l\'agent',
            'name' => 'id_speciality',
            'type' => 'select',
            'value' => $specialities
        ];

        $hideouts = [];

        foreach (Hideout::findAll() as $hideout) {
            $hideouts[] = [
                'id' => $hideout->getId(),
                'name' => $hideout->getName()
            ];
        }

        $fields[] = [
            'label' => 'Planques de la mission',
            'name' => 'hideouts',
            'type' => 'multiSelect',
            'value' => $hideouts
        ];

        $actors = [];

        foreach (Hideout::findAll() as $hideout) {
            $actors[] = [
                'id' => $hideout->getId(),
                'name' => $hideout->getName()
            ];
        }

        $fields[] = [
            'label' => 'Agents',
            'name' => 'agents',
            'type' => 'multiSelect',
            'value' => $actors
        ];

        $fields[] = [
            'label' => 'Contacts',
            'name' => 'contacts',
            'type' => 'multiSelect',
            'value' => $actors
        ];

        $fields[] = [
            'label' => 'Cibles',
            'name' => 'cibles',
            'type' => 'multiSelect',
            'value' => $actors
        ];

        return $fields;

    }

    private function getRows (): array
    {

        $missions = [];

        foreach (Mission::findAll() as $mission) {

            $missions[] = $this->getRow($mission);
        } 

        return $missions;

    }

    private function getRow (Mission $mission): array 
    {



        return  [
            'id' => $mission->getId(),
            'title' => $mission->getTitle(),
            'description' => $mission->getDescription(),
            'codeName' => $mission->getCodeName(),
            'begin' => $mission->getBegin(),
            'end' => $mission->getEnd(),
            'id_country' => $mission->getId_country(),
            'id_statut' => $mission->getId_statut(),
            'id_typeMission' => $mission->getId_typeMission(),
            'id_speciality' => $mission->getId_speciality(),
            'hideouts' => $mission->getHideouts(),
//            'agents' => $mission->getActorsByRole(),
//            'contacts' => $mission->getSpecialities(),
//            'cibles' => $mission->getSpecialities(),
        ];

    }
    
}