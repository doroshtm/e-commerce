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


const checkboxes = document.querySelectorAll('.checkboxes input[type=checkbox]');
const corpo = document.getElementById('corpo-filtro-display');

function criarElemento(nome){
  console.log("Palmeiras");
  let htmlString = '<div class="filtro-display">';
  htmlString += nome + '</div>';
  corpo.innerHTML = htmlString;
}
for (let i=0; i<checkboxes.length; i++) {
  console.log("Palmeiras");
  checkboxes[i].addEventListener('change', () => {	
    console.log("Mudou o " + this.getAttribute('name'));
    if (this.checked)
    {
      let nome = this.getAttribute('name');
      criarElemento(nome);
    }
  });
}
});