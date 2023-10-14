

function toggleBurger () {

  const sidemenu = document.getElementById('sidemenu-ul');

  if (sidemenu.style.display === 'none') {
    sidemenu.style.display = 'initial'
  } else {
    sidemenu.style.display = 'none'
  }

}
