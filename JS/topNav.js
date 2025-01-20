/*==============================
  NAVBAR SCRIPT
==============================*/
// get the hamburger icon and navbar
const navbarHamburger = document.getElementById('navbar-hamburger');
const navbar = document.querySelector('.navbar');

// toggle the active class on navbar when hamburger is clicked
navbarHamburger.addEventListener('click', () => {
  navbar.classList.toggle('active');
});