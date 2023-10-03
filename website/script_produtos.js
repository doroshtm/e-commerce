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
let nomes = ["CTI", "Informática", "Mecânica" , "Eletrônica"];
const corpo = document.getElementById('corpo-filtro-display');

function criarElemento(nome) {
  console.log("Na função");
  const novoElemento = document.createElement('div');
  novoElemento.className = 'filtro-display';
  novoElemento.id = nome;
  novoElemento.textContent = nome;
  corpo.appendChild(novoElemento);
  document.getElementById(nome).style.animation = 'mostra-filtro 0.2s linear forwards';
  novoElemento.addEventListener('click', () => {
    corpo.removeChild(novoElemento);
    const checkboxIndex = nomes.indexOf(nome);
    checkboxes[checkboxIndex].checked = false;
  });
}

for (let i=0; i<checkboxes.length; i++) {
  console.log("Palmeiras");
  checkboxes[i].addEventListener('change', () => {	
    console.log("Mudou o " + nomes[i]);
    if (checkboxes[i].checked)
    {
      console.log("TÁ CHECADO");
      criarElemento(nomes[i]);
    }
    else {
      let elemento = document.getElementById(nomes[i])
      elemento.style.animation = 'esconde-filtro 0.2s linear forwards';
      elemento.addEventListener('animationend', () => {
      corpo.removeChild(elemento);
      });
    }

  });
}   

});