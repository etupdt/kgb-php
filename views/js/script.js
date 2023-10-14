
function toggleSideMenu () {

  const sidemenu = document.getElementById('sidemenu-div')
  const sidemenu_expand = document.getElementById('sidemenu-expand');
  const sidemenu_collapse = document.getElementById('sidemenu-collapse')

  if (sidemenu.style.display === 'none') {
    sidemenu.style.display = 'initial'
//    sidemenu.style.position = 'absolute'
    sidemenu_expand.style.display = 'none'
    sidemenu_collapse.style.display = 'initial'
  } else {
    sidemenu.style.display = 'none'    
//    sidemenu.style.position = 'initial'
    sidemenu_expand.style.display = 'initial'
    sidemenu_collapse.style.display = 'none'
  }

}
