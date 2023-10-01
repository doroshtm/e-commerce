document.addEventListener('DOMContentLoaded', () => {
const filtro = document.getElementById('filtro');
const seta = document.querySelector('#filtro img');
const dropdown = document.getElementById('dropdown');
let chave = true;
filtro.addEventListener('click', () => {
  if (chave) {
    filtro.style.borderColor = 'var(--cor-interac)';
    dropdown.style.animation= 'mostra-dropdown 0.4s forwards';
    seta.style.transform = 'rotate(180deg)';
  }
  else {
    filtro.style.borderColor = 'grey';
    dropdown.style.animation = 'esconde-dropdown 0.4s forwards';
    seta.style.transform = 'rotate(0deg)';
  }
  chave = !chave;
});

});