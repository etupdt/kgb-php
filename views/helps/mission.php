
<p>Les missions ont un titre, une description, un nom de code, un pays, 1 ou plusieurs agents, 1 ou
plusieurs contacts, 1 ou plusieurs cibles, un type de mission (Surveillance, Assassinat, Infiltration …), un
statut (En préparation, en cours, terminé, échec), 0 ou plusieurs planque, 1 spécialité requise, date de
début, date de fin.</p>

<p>Les acteurs de cette mission sont :</p>
<ul>
    <?php 
        foreach ($row['object']->getActors_roles() as $actorRoleArray) {
            echo '<li>';
            $actor = $actorRoleArray['actor'];
            $role = $actorRoleArray['role'];
            echo $actor->getLastname().' '.$actor->getFirstname().' comme '.$role->getRole();
            echo '</li>';
        }
    ?>
</ul>

<p>Les planques de cette mission sont :</p>
<ul>
    <?php 
        foreach ($row['object']->getHideouts() as $hideoutArray) {
            echo '<li>';
            $hideout = $hideoutArray['hideout'];
            echo $hideout->getAddress().' de '.$hideout->getCountry()->getNationality();
            echo '</li>';
        }
    ?>
</ul>
