
function isValidForm (form) {

  const fields = form.querySelectorAll(".need-validation")

  let ok = true

  fields.forEach((field) => {
    ok = true && ok
    field.classList.forEach((classe) => {
      if (ok && classe.indexOf('validation-') === 0) {
        ok = eval(`${classe.substring(11)}('${field.id}')`)
      }
    })
  })

  return ok

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

async function getControlData (type) {

  const response = await fetch(`https://localhost:8000/api/control${type}`);

  this.controlData = await response.json()

  console.log(this.controlData)

}

function response (formGroupIds, error, message) {

  let formGroupId
  let messageId
  let type
  let control

  formGroupIds.forEach((formGroupId) => {
    
    formGroup = document.querySelector(`#${formGroupId}`)
    messageId = formGroup.querySelector(".message")
    type = formGroup.getAttribute('type')
    control = formGroup.querySelector(type)

    messageId.style.display = 'none'
  
    if (error) {
      control.classList.add('is-invalid')
      messageId.innerHTML = message
      messageId.style.display = 'block'
    } else {
      control.classList.remove('is-invalid')
    }
    
  })

  return error

}
