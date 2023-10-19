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

      $missionsData = [];

      // on recherche les misisons
      foreach (Mission::findAll() as $mission) {

        $actors = [];
        $actorActif = false;

        // on recherche les acteurs intervenent dans la mission dans
        // le but de pouvoir vérifier les contraintes 1 et 2
        foreach ($mission->getActorsRoles() as $actor_role) {

          $actor = Actor::find($actor_role['id_actor']);
          $actors[$actor->getId()] = [
            'countries' => [$actor->getId_country],
            'specialities' => $actor->getSpecialities()
          ];

          // pour les contraintes on a bessoin des roles de l'acteur
          array_push($actors[$actor->getId()]['roles'], $actor_role['id_role']);

          // on ne gardera que les missions où intervient l'acteur
          if ($actor->getId() === $_GET['id']) {
            $actorActif = true;
          }

        }
        
        if ($actorActif) {
          $missionsData[$mission->getId()] = [
            'countries' => [$mission->getId_country()],
            'specialities' => [$mission->getId_speciality()],
            'actors' => $actors
          ];
        }

      }
      
      // on retourne au front-end la liste des missions associées à l'acteur an cours
      $json = json_encode([
        'missionsData' => $missionsData
      ]);

      echo $json;

    }
    
}