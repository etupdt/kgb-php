
let entity = 'actor'

let controlData 

getControlData (entity)

function controlMission (formGroupId) {

  switch (formGroupId) {
    case 'countries' : {
      const country = document.querySelector('#id_country')
      // si la personne est une cible il faut que aucun agent n'ait le même pays
      // si la personne est un contact il faut que son pays soit le même que celui de 
      //  la msission
      // Pour chqaue planque, il faut que son pays soit le même que celui de la mission
      // pour chaque agent, il faut qu'au moins un ait la même specialité que la mission
      break
    }
  }

}

function controlSpeciality (formGroupId) {

  // il ne faut pas la suuprimmer si elle est la spécialité d'une mission ou d'un acteur

}

function controlActor (formGroupId, id_actor) {

  const country = document.querySelector('#id_country').querySelector('.select').value
  
  // si la personne est une cible il faut que aucun agent n'ait le même pays dans 
  //  une mission
  // si la personne est un contact il faut que les pays de ses missions soient
  //  le même que son pays
  
  for (const [title, mission] of Object.entries(this.controlData.missionsData)) {

    let roles = {
      'Agent' : [],
      'Contact' : [],
      'Cible' : []
    }
  
    for (const [id, actorloop] of Object.entries(mission.actors)) {
      if (actorloop.roles.includes('Agent')) roles.Agent[id] = actorloop
      if (actorloop.roles.includes('Contact')) roles.Contact[id] = actorloop
      if (actorloop.roles.includes('Cible')) roles.Cible[id] = actorloop
    } 

    if (formGroupId == 'id_country') {
        
      const id_country = document.querySelector('#id_country').querySelector('.select').value

      if (Object.keys(roles.Agent).includes(id_actor)) {
        for (const [id, actor] of Object.entries(roles.Cible)) {
          if (id !== id_actor && actor.country.id === id_country) {
            $('#errorMessage').text(`Dans la mission "${title}" la personne que vous modifiez es un "agent". 
                                     Sa nationalité ne peut donc pas être la même que "${actor.name}" qui est une "cible" de 
                                     la même mission. Vous devez donc mettre un pays différent de "${actor.country.name}" 
                                     ou modifier la mission dans le menu "Missions" pour changer les rôles de ces
                                     personnes.`)
            $('#errorpage').modal('show')
            response(formGroupId, true, `ne peut pas être cible car la mission "${title}" a un agent du même pays !`)
          }
        }
      }

      if (Object.keys(roles.Cible).includes(id_actor)) {
        for (const [id, actor] of Object.entries(roles.Agent)) {
          if (id !== id_actor && actor.country.id === id_country) {
            $('#errorMessage').text(`Dans la mission "${title}" la personne que vous modifiez est une "cible". 
                                     Sa nationalité ne peut donc pas être la même que "${actor.name}" qui est un "agent" de 
                                     la même mission. Vous devez donc mettre un pays différent de "${actor.country.name}" 
                                     ou modifier la mission dans le menu "Missions" pour changer les rôles de ces
                                     personnes.`)
            $('#errorpage').modal('show')
            response(formGroupId, true, `ne peut pas être cible car la mission "${title}" a un agent du même pays !`)
          }
        }
      }

      if (Object.keys(roles['Contact']).includes(id_actor)) {
        if (mission.country.id !== id_country) {
          $('#errorMessage').text(`La personne que vous modifiez a un rôle de "contact" dans la mission "${title}". 
                                   Sa nationalité ne peut donc pas être différente du pays de cette mission.
                                   Vous pouvez donc lui donner le pays de la mission : "${mission.country.name}" 
                                   ou modifier la mission dans le menu "Missions" pour changer le rôle de cette
                                   personne.`)
          $('#errorpage').modal('show')
          response(formGroupId, true, `"contact" et pays <> de celui de la mission "${title}" !`)
        }
      }

    }

    if (formGroupId == 'specialities') {

      const special = document.querySelector('#specialities')
      const specialit = special.querySelector('.multiselect')
      const specialities = Array.from(specialit.querySelectorAll("select option:checked"),e=>e.value)

      if (Object.keys(roles.Agent).includes(id_actor)) {

        if (!specialities.includes(mission.speciality)) {
          
          specialityExist = false
          for (const [id, agent] of Object.entries(roles.Agent)) {
            if (id !== id_actor && agent.specialities.includes(mission.speciality)) {
              specialityExist = true
            }
          }
          if (!specialityExist) {
            $('#errorMessage').text(`La personne que vous modifiez est le seul "agent" qui ait la spécialité
                                     "${mission.speciality}" dans la mission "${title}". Vous ne pouvez donc pas 
                                     lui enlever. Avant vous devez affecter à cette mission un autre "agent"
                                     qui ait cette spécialité.`)
            $('#errorpage').modal('show')
            response(formGroupId, true, `personne "contact" et pays <> de celui de la mission "${title}" !`)
          }

        }  

      }

    }
    
  }

  console.log('sortie', formGroupId)
  return true

}
