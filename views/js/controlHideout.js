
const entity = 'hideout'

let controlData 

getControlData (entity)

function controlHideout (formGroupId, id_hideout) {

  // il ne faut pas la suuprimmer si elle est la planque d'une mission

  const id_country = document.querySelector('#id_country').querySelector('.select').value
  
  for (const [title, mission] of Object.entries(this.controlData.missionsData)) {

    if (id_country !== mission.country.id) {
      hideoutExist = false
      for (const [id, hideout] of Object.entries(mission.hideouts)) {
        if (id !== id_hideout && mission.country.id === hideout.country.id) {
          hideoutExist = true
        }
      }
      if (!hideoutExist) {
        $('#errorMessage').text(`La planque que vous modifiez est la seule qui soit de la mission "${title}" et  
                                 située dans le même pays : "${mission.country.name}". Vous ne pouvez donc pas le changer. Avant vous 
                                 devez affecter à cette mission une "planque" du même pays.`)
        $('#errorpage').modal('show')
        response(formGroupId, true, `cett0.e planque est associée à la mission "${title}" située dans un autre pays !`)
      }
    }

  }

}
