
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
  //  chaque mission
  // si la personne est un contact il faut que les pays de ses missions soient
  //  le même que ce pays
  
  for (const [title, mission] of Object.entries(this.controlData.missionsData)) {

    let roles = {
      'Agent' : [],
      'Contact' : [],
      'Cible' : []
    }
  
    for (const [id, actorloop] of Object.entries(mission.actors)) {
      const actor = {
        'id_country' : actorloop.countries[0],
        'specialities' : actorloop.specialities
      }
      if (actorloop.roles.includes('Agent')) roles.Agent[id] = actor
      if (actorloop.roles.includes('Contact')) roles.Contact[id] = actor
      if (actorloop.roles.includes('Cible')) roles.Cible[id] = actor
    } 

    if (formGroupId == 'id_country') {
        
      const id_country = document.querySelector('#id_country').querySelector('.select').value
    
      if (Object.keys(roles['Cible']).includes(id_actor)) {
        for (const [id, actor] of Object.entries(roles.Agent)) {
          if (id !== id_actor && actor.id_country === id_country) {
            response(formGroupId, true, `ne peut pas être cible car la mission "${title}" a un agent du même pays !`)
          }
        }
      }

      if (Object.keys(roles['Contact']).includes(id_actor)) {
        if (mission.countries[0] !== id_country) {
          response(formGroupId, true, `personne "contact" et pays <> de celui de la mission "${title}" !`)
        }
      }

    }

    if (formGroupId == 'specialities') {

      const special = document.querySelector('#specialities')
      const specialit = special.querySelector('.multiselect')
      const specialities = Array.from(specialit.querySelectorAll("select option:checked"),e=>e.value)

      if (Object.keys(roles['Agent']).includes(id_actor)) {

        if (!specialities.includes(mission.specialities[0])) {
          
          for (const [id, agent] of Object.entries(roles.Agent)) {
            specialityExist = false
            if (id !== id_actor && agent.specialities.includes(mission.specialities[0])) {
              specialityExist = true
            }
          }
          if (!specialityExist) {
            response(formGroupId, true, `personne "contact" et pays <> de celui de la mission "${title}" !`)
          }

        }  

      }

    }
    
  }

  console.log('sortie', formGroupId)
  return true

}
