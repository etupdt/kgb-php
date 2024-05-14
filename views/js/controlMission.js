
const entity = 'mission'

let controlData 

getControlData (entity)

function controlOnChangeAgentsCibles (formGroupId) {

  if (controlPaysCiblesAgents()) {
    response(formGroupId, true, `Un des agents séléctionnés est dans le même pays qu'une des cibles !`)
  } else {
    response(formGroupId, false, ``)
  }

}

function controlOnBlurAgentsCibles (formGroupId) {

  if (controlPaysCiblesAgents()) {
    $('#errorMessage').text(`Un des agents séléctionnés est dans le même pays qu'une des cibles !`)
    $('#errorpage').modal('show')
    response(formGroupId, true, `Un des agents séléctionnés est dans le même pays qu'une des cibles !`)
  } else {
    response(formGroupId, false, ``)
  }

}

function controlPaysCiblesAgents () {

  // const hideo = document.querySelector('#hideouts')
  // const hideou = hideo.querySelector('.multiselect')
  // const hideouts = Array.from(hideou.querySelectorAll("select option:checked"),e=>e.value)

  const agents = document.querySelector(`#Agent`)
  const cibles = document.querySelector(`#Cible`)

  const selectAgents = agents.querySelector('.multiselect')
  const selectCibles = cibles.querySelector('.multiselect')

  const messageAgents = agents.querySelector(".message")
  const messageCibles = cibles.querySelector(".message")

  const missionAgentsSelected = Array.from(selectAgents.querySelectorAll("select option:checked"),e=>e.value)
  const missionCiblesSelected = Array.from(selectCibles.querySelectorAll("select option:checked"),e=>e.value)

  let sameCountry = false

  missionCiblesSelected.forEach((id_cible) => {
    missionAgentsSelected.forEach((id_agent) => {
      console.log(this.controlData.actorsData[id_agent].country.id, this.controlData.actorsData[id_cible].country.id)
      if (this.controlData.actorsData[id_agent].country.id == this.controlData.actorsData[id_cible].country.id) {
        sameCountry = true
      }
    })
  })

  return sameCountry

}

function controlPaysMission (formGroupId, category) {

  console.log('category', category)
  const country = document.querySelector('#id_country')
  const elements = document.querySelector(`#${category}`)

  const selectCountry = country.querySelector('.select')
  const selectElements = elements.querySelector('.multiselect')

  const message = elements.querySelector(".message")

  const missionElementsSelected = Array.from(elements.querySelectorAll("select option:checked"),e=>e.value)

  message.style.display = 'none'

  let sameCountry = true

  missionElementsSelected.forEach((id_element) => {
    if (this.controlData[category][id_element]['countries'][0] !== selectCountry.value) {
      sameCountry = false
    }
  })

  if (sameCountry) {
    selectElements.classList.remove('is-invalid')
  } else {
    selectElements.classList.add('is-invalid')
    message.innerHTML = 'Un des éléments séléctionnés est dans un pays différent de celui de la mission !'
    message.style.display = 'block'
  }

}

function controlOnChangeSpecialityAgent (formGroupId, id, formGroupIds) {

  if (!controlSpecialityMission()) {
    response(formGroupIds, true, `Un des agents séléctionnés doit avoir la spécialité requise de la mission !`)
  } else {
    response(formGroupIds, false, ``)
  }

}

function controlOnBlurSpecialityAgent (formGroupId, id, formGroupIds) {

  if (!controlSpecialityMission()) {
    $('#errorMessage').text(`Un des agents séléctionnés doit avoir la spécialité requise de la mission !`)
    $('#errorpage').modal('show')
    response(formGroupIds, true, `Un des agents séléctionnés doit avoir la spécialité requise de la mission !`)
  } else {
    response(formGroupIds, false, ``)
  }

}

function controlSpecialityMission () {

  const speciality = document.querySelector('#id_speciality')
  const agents = document.querySelector('#Agent')

  const selectSpeciality = speciality.querySelector('.select')
  const selectAgents = agents.querySelector('.multiselect')

  const message = agents.querySelector(".message")

  const missionAgentsSelected = Array.from(selectAgents.querySelectorAll("select option:checked"),e=>e.value)

  let sameSpeciality = false

  missionAgentsSelected.forEach((id_agent) => {
    for (const [id_speciality, speciality] of Object.entries(this.controlData.actorsData[id_agent].specialities)) {
      if (id_speciality === selectSpeciality.value) {
        sameSpeciality = true
      }
    }
  })

  return sameSpeciality

}

function controlOnChangeHideouts (formGroupId, id_hideout) {

  if (!controlMissionHideouts(id_hideout)) {
    response(formGroupId, true, `La mission doit avoir au moins une planque du même pays !`)
  } else {
    response(formGroupId, false, ``)
  }

}

function controlOnBlurHideouts (formGroupId, id_hideout) {

  if (!controlMissionHideouts(id_hideout)) {
    $('#errorMessage').text(`La mission que vous modifiez n'a pas de planque dans le même pays.   
                             Vous devez donc en associer une.`)
    $('#errorpage').modal('show')
    response(formGroupId, true, `La mission doit avoir au moins une planque du même pays !`)
  } else {
    response(formGroupId, false, ``)
  }

}

function controlMissionHideouts (id_hideout) {

  const mission_country = document.querySelector('#id_country').querySelector('.select').value
  const hideo = document.querySelector('#hideouts')
  const hideou = hideo.querySelector('.multiselect')
  const hideouts = Array.from(hideou.querySelectorAll("select option:checked"),e=>e.value)

  hideoutExist = false
  for (const [index, id_hideout] of Object.entries(hideouts)) {
    console.log(mission_country, this.controlData.hideoutsData[id_hideout].country.id)

    if (mission_country == this.controlData.hideoutsData[id_hideout].country.id) {
      hideoutExist = true
    }
    
  }

  return hideoutExist

}
