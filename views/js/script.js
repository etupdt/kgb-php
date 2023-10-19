
let data 

let controlActorData

getData ('mission')

function isValidForm (form) {

  console.log('form', form)
  const fields = form.querySelectorAll(".need-validation")

  console.log('fields', fields)

  let ok = false

  fields.forEach((field) => {
    ok = true
    field.classList.forEach((classe) => {
      if (ok && classe.indexOf('validation-') === 0) {
        ok = eval(`${classe.substring(11)}('${field.id}')`)
      }
    })
  })

  return ok

}

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
      if (this.data['Cible'][id_cible]['countries'][0] === this.data['Agent'][id_agent]['countries'][0]) {
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
    if (this.data[category][id_element]['countries'][0] !== selectCountry.value) {
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
    this.data['Agent'][id_agent]['specialities'].forEach((speciality) => {
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

function isRequired (formGroupId) {

  console.log('formGroupId', formGroupId)
  const formGroup = document.querySelector(`#${formGroupId}`)
  const message = formGroup.querySelector(".message")
  const type = formGroup.getAttribute('type')
  const control = formGroup.querySelector(type)
  const fieldType = formGroup.getAttribute('fieldtype')

  message.style.display = 'none'

  if ((fieldType === 'input' && control.value === '') ||
    (fieldType === 'textarea' && control.value === '') || 
    (fieldType === 'select' && control.value === '0') ||
    (fieldType === 'multiselect' && control.querySelectorAll("select option:checked").length === 0) 
  ) {
    console.log('ici')
    control.classList.add('is-invalid')
    message.innerHTML = 'Ce champs doit être renseigné !'
    message.style.display = 'block'
    return false
  } else {
    control.classList.remove('is-invalid')
    return true
  }
/*  } else if (!control.value.match(RegExp(param))) {
    control.classList.add('is-invalid')
    message.innerHTML = 'caractère(s) invalide(s) ou format incorrect !'
    message.style.display = 'block'*/

}

function displayAttributes (option, category, attribut1, attribut2) {

  const optionIndex = option.getAttribute('index') 
  const categories = this.data[category]
  const categoryId = Object.keys(categories)[optionIndex]
  const attribut1Id = this.data[category][categoryId][attribut1]
  const attribut1Libelle = this.data[attribut1][attribut1Id]
  console.log(attribut1Libelle ? attribut1Libelle['libelle'] : '')
  if (attribut2) {
    const attribut2Id = this.data[category][categoryId][attribut2]
    const attribut2Libelle = this.data[attribut2][attribut2Id]
    console.log(attribut2Libelle ? attribut2Libelle['libelle'] : '')
  }
  
}





function controlMission (formGroupId) {

  if (!controlActorData) {
    getData('mission')
  }

  switch (formGroupId) {
    case 'countries' : {
      const country = document.querySelector('#id_country')
      // si la personne est une cible il faut que les pays de ses missions soient
      //  le même que ce pays
      // sinon => message erreur
      // si la personne est une cible il faut que aucun agent n'ait le même pays dans 
      //  chaque mission
      break
    }
  }

}

function controlActor (formGroupId) {

  if (!controlActorData) {
    getData('actor')
  }

  switch (formGroupId) {
    case 'countries' : {
      const country = document.querySelector('#id_country')
      // si la personne est un contact il faut que les pays de ses missions soient
      //  le même que ce pays
      // si la personne est une cible il faut que aucun agent n'ait le même pays dans 
      //  chaque mission
      break
    }
    case 'specialities' : {
      const country = document.querySelector('#specialities')
      // Pour chaque mission, il ne faut pas que la personne, si elle est agent, soit 
      //  la seule à avoir la même spécialité que la mission
      break
    }
  }

}

async function getData (type) {

  const reponse = await fetch(`https://localhost:8000/api/control${type}`);

  const json = await reponse.json()

  this.data = {
    'hideouts' : json.hideoutsData,
    'Agent' : json.actorsData,
    'Contact' : json.actorsData,
    'Cible' : json.actorsData,
    'countries' : json.countriesData,
    'specialities' : json.specialitiesData
  }
    
  console.log(this.data)

}
