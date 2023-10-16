
let actorsData
let hideoutsData

getControlMission ()

function controlMission (fieldId, fieldValue) {

  const missionCountry = document.getElementById('id_country').value
  const missionHideouts = Array.from(document.getElementById('hideouts').querySelectorAll("option:checked"),e=>e.value)

  console.log ('id_country', missionCountry)
  console.log ('missionHideouts', missionHideouts)
  
  let sameCountry = false

  missionHideouts.forEach((id_hideout) => {
    // console.log(this.hideoutsData[id_hideout]['country'])
    if (this.hideoutsData[id_hideout]['country'] === missionCountry) {
      console.log('pays match')
    } else {
      console.log('pays ne match pas')
    }

  })


/*
  for (let hideout in missionHideouts) {
    console.log('hideout', missionHideouts[hideout])
    console.log('loop', this.controlMissionData['hideoutsData'])
    if (this.controlMissionData['hideoutsData'][missionHideouts[hideout]]['country'] === missionCountry) {
      console.log('trouve')
    }
  }
*/
}

async function getControlMission () {

  const reponse = await fetch("https://localhost:8000/api/controlmission");

  const json = await reponse.json()

  this.actorsData = json.actorsData
  this.hideoutsData = json.hideoutsData

  console.log(json)

}
