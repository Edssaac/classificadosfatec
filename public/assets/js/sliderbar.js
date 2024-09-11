const sidebar = document.getElementById('sidebar');
const buttonMenu = document.getElementById('btn-menu');
const buttonClose = document.getElementById('btn-close');
const navLinks = document.getElementsByClassName('nav-link');

buttonMenu.addEventListener('click', () => {
    sidebar.classList.toggle('nav-show');
});
buttonClose.addEventListener('click', () => {
    sidebar.classList.remove('nav-show');
});

Array.from(navLinks).forEach(navItem => {
    navItem.addEventListener('click', () => {
        sidebar.classList.remove('nav-show');
    });
});

document.addEventListener("click", (e) => {
    if (window.innerWidth < 768 && e.clientX >= (0.70 * window.innerWidth)) {
        sidebar.classList.remove('nav-show');
    }
});