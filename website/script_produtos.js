document.addEventListener('DOMContentLoaded', () => {
const filtro = document.getElementById('filtro');
const seta = document.querySelector('#filtro img');
const dropdown = document.getElementById('dropdown');

filtro.addEventListener('click', () => {
  if (dropdown.style.display == 'none') {
    dropdown.style.display = 'block';
    seta.style.transform = 'rotate(180deg)';
  }
  else {
    dropdown.style.display = 'none';
    seta.style.transform = 'rotate(0deg)';
  }
});

});