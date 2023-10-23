
const entity = 'hideout'

let controlData 

getControlData (entity)

function controlHideout (formGroupId, id_hideout) {

  // il ne faut pas la suuprimmer si elle est la planque d'une mission

  const id_country = document.querySelector('#id_country').querySelector('.select').value
  
  for (const [index, mission] of Object.entries(this.controlData.missionsData)) {

    if (mission.hideouts.includes(parseInt(id_hideout)) && mission.countries[0] !== id_country) {
      response(formGroupId, true, `cett0.e planque est associée à la mission "${mission.title}" située dans un autre pays !`)
    }

  }

}
