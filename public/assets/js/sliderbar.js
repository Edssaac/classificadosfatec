/*===== ELEMENT SELECTOR =====*/
const sidebar = document.getElementById('sidebar');
const buttonMenu = document.getElementById('btn-menu');
const buttonClose = document.getElementById('btn-close');
const navLinks = document.getElementsByClassName('nav-link');

/*===== SHOW/HIDE SIDEBAR ======*/
// OPEN
buttonMenu.addEventListener('click', () => {
  sidebar.classList.toggle('nav-show');
});
// CLOSE
buttonClose.addEventListener('click', () => {
  sidebar.classList.remove('nav-show');
});

// HIDE SIDEBAR WHEN CLICK ON MENU ITEM
Array.from(navLinks).forEach(navItem => {
  navItem.addEventListener('click', () => {
    sidebar.classList.remove('nav-show');
  });
});