
const entity = 'mission'

let controlData 

getData (entity)

function controlPaysCiblesAgents () {

  const agents = document.querySelector(`#Agent`)
  const cibles = document.querySelector(`#Cible`)

  const selectAgents = agents.querySelector('.multiselect')
  const selectCibles = cibles.querySelector('.multiselect')

  const messageAgents = agents.querySelector(".message")
  const messageCibles = cibles.querySelector(".message")

  const missionAgentsSelected = Array.from(agents.querySelectorAll("select option:checked"),e=>e.value)
  const missionCiblesSelected = Array.from(cibles.querySelectorAll("select option:checked"),e=>e.value)

  messageAgents.style.display = 'none'
  messageCibles.style.display = 'none'

  let sameCountry = false

  missionCiblesSelected.forEach((id_cible) => {
    console.log('cible')
    missionAgentsSelected.forEach((id_agent) => {
      console.log('agent')
      if (this.controlData['Cible'][id_cible]['countries'][0] === this.controlData['Agent'][id_agent].countries[0]) {
        sameCountry = true
      }
    })
  })

  console.log(sameCountry)
  if (!sameCountry) {
    selectAgents.classList.remove('is-invalid')
    selectCibles.classList.remove('is-invalid')
  } else {
    selectAgents.classList.add('is-invalid')
    messageAgents.innerHTML = 'Un des agents séléctionnés est dans le même pays qu\'une des cibles !'
    messageAgents.style.display = 'block'
    selectCibles.classList.add('is-invalid')
    messageCibles.innerHTML = 'Une des cibles séléctionnées est dans le même pays qu\'un des agents !'
    messageCibles.style.display = 'block'
  }

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

function controlSpecialityMission () {

  const speciality = document.querySelector('#id_speciality')
  const agents = document.querySelector('#Agent')

  const selectSpeciality = speciality.querySelector('.select')
  const selectAgents = agents.querySelector('.multiselect')

  const message = agents.querySelector(".message")

  const missionAgentsSelected = Array.from(agents.querySelectorAll("select option:checked"),e=>e.value)

  message.style.display = 'none'

  let sameSpeciality = false

  missionAgentsSelected.forEach((id_agent) => {
    this.controlData['Agent'][id_agent]['specialities'].forEach((speciality) => {
      if (speciality == selectSpeciality.value) {
        sameSpeciality = true
      }
    })
  })

  if (sameSpeciality) {
    selectAgents.classList.remove('is-invalid')
  } else {
    selectAgents.classList.add('is-invalid')
    message.innerHTML = 'Un des agents séléctionnés doit avoir la spécialité requise de la mission !'
    message.style.display = 'block'
  }

}

function displayAttributes (option, category, attribut1, attribut2) {

  const optionIndex = option.getAttribute('index') 
  const categories = this.controlData[category]
  const categoryId = Object.keys(categories)[optionIndex]
  const attribut1Id = this.controlData[category][categoryId][attribut1]
  const attribut1Libelle = this.controlData[attribut1][attribut1Id]
  console.log(attribut1Libelle ? attribut1Libelle['libelle'] : '')
  if (attribut2) {
    const attribut2Id = this.controlData[category][categoryId][attribut2]
    const attribut2Libelle = this.controlData[attribut2][attribut2Id]
    console.log(attribut2Libelle ? attribut2Libelle['libelle'] : '')
  }
  
}
