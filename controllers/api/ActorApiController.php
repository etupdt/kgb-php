<?php

/*
1/ Sur une mission, la ou les cibles ne peuvent pas avoir la même nationalité que le ou les agents.
2/ Sur une mission, les contacts sont obligatoirement de la nationalité du pays de la mission.
3/ Sur une mission, la planque est obligatoirement dans le même pays que la mission.
4/ Sur une mission, il faut assigner au moins 1 agent disposant de la spécialité requise.
*/

// Api pour controle lors de la création-modification d'un acteur
class ActorApiController {

    public function index() { 

      $actorRepository = new ActorRepository(1);
      $roleRepository = new RoleRepository(1);
      $missionRepository = new MissionRepository(1);

      $missionsData = [];

      // on recherche les misisons
      foreach ($missionRepository->findAll() as $mission) {

        $actors = [];

        // on recherche les acteurs intervenent dans la mission dans
        // le but de pouvoir vérifier les contraintes 1 et 2
        foreach ($mission->getActors_roles() as $actor_role) {

          $actor = $actorRepository->find($actor_role['actor']->getId());
          if (!isset( $actors[$actor->getId()])) {
            $actors[$actor->getId()] = [
              'country' => [
                'id' => $actor->getCountry()->getId(),
                'name' => $actor->getCountry()->getName()
              ],
              'specialities' => $actor->getSpecialities(),
              'name' => $actor->getLastname().' '.$actor->getFirstname(),
              'roles' => []  
            ];
          }

          // pour les contraintes on a bessoin des roles de l'acteur
          $actors[$actor->getId()]['roles'][] = $roleRepository->find($actor_role['role']->getId())->getRole();

        }
        
        $missionsData[$mission->getTitle()] = [
          'id_mission' => $mission->getId(),
          'country' => [
            'id' => $mission->getCountry()->getId(),
            'name' => $mission->getCountry()->getName()
          ],
          'speciality' => $mission->getSpeciality()->getId(),
          'actors' => $actors
        ];

      }
      
      // on retourne au front-end la liste des missions associées à l'acteur an cours
      $json = json_encode([
        'missionsData' => $missionsData
      ]);

      echo $json;

    }
    
}