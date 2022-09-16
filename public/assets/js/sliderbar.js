/*===== SELEÇÃO DE ELEMENTOS =====*/
const sidebar = document.getElementById('sidebar');
const buttonMenu = document.getElementById('btn-menu');
const buttonClose = document.getElementById('btn-close');
const navLinks = document.getElementsByClassName('nav-link');

/*===== SHOW/HIDE SIDEBAR ======*/
// ABRIR
buttonMenu.addEventListener('click', () => {
    sidebar.classList.toggle('nav-show');
});
// FECHAR
buttonClose.addEventListener('click', () => {
    sidebar.classList.remove('nav-show');
});

// HIDE SIDEBAR WHEN CLICK ON MENU ITEM
// ESCONDER SIDEBAR QUANDO CLICAR EM UM ITEM DO MENU
Array.from(navLinks).forEach(navItem => {
    navItem.addEventListener('click', () => {
        sidebar.classList.remove('nav-show');
    });
});

// ESCONDER SIDEBAR QUANDO CLICAR FORA DA ÁREA DELA (MOBILE)
document.addEventListener("click", (e) => {
    if (window.innerWidth < 768 && e.clientX >= (0.70 * window.innerWidth)) {
        sidebar.classList.remove('nav-show');
    }
});