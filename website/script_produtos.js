document.addEventListener('DOMContentLoaded', () => {
  const filtro = document.getElementById('filtro');
  const seta = document.querySelector('#filtro img');
  const dropdown = document.getElementById('dropdown');
  let chave = true;
  const checkboxes = document.querySelectorAll('.checkboxes input[type=checkbox]');
  let nomes = ["CTI", "Informática", "Mecânica", "Eletrônica"];
  const corpo = document.getElementById('corpo-filtro-display');
  const elementosCriados = {}; // Track created elements

  function criarElemento(nome) {
    if (!elementosCriados[nome]) {
      const novoElemento = document.createElement('div');
      novoElemento.className = 'filtro-display';
      novoElemento.id = nome;
      novoElemento.textContent = nome;
      novoElemento.style.animation = 'mostra-filtro 0.2s forwards';
      corpo.appendChild(novoElemento);

      novoElemento.addEventListener('click', () => {
        destruirElemento(novoElemento);
        const checkboxIndex = nomes.indexOf(nome);
        checkboxes[checkboxIndex].checked = false;
        elementosCriados[nome] = false;
      });

      elementosCriados[nome] = true;
    }
  }

function destruirElemento(elemento){
  elemento.style.animation = 'esconde-filtro 0.2s forwards';
  elemento.addEventListener('animationend', () => {
    corpo.removeChild(elemento);
  });
}

  filtro.addEventListener('click', () => {
    if (chave) {
      filtro.style.borderColor = 'var(--cor-interac)';
      dropdown.style.animation = 'mostra-dropdown 0.4s forwards';
      seta.style.transform = 'rotate(180deg)';
    } else {
      filtro.style.borderColor = 'grey';
      dropdown.style.animation = 'esconde-dropdown 0.4s forwards';
      seta.style.transform = 'rotate(0deg)';
    }
    chave = !chave;
  });

  for (let i = 0; i < checkboxes.length; i++) {
    checkboxes[i].addEventListener('change', () => {
      if (checkboxes[i].checked) {
        criarElemento(nomes[i]);
      } else {
        const elementoParaRemover = elementosCriados[nomes[i]];
        console.log(elementoParaRemover);
        if (elementoParaRemover) {
          destruirElemento(document.getElementById(nomes[i]));
          elementosCriados[nomes[i]] = false;
        }
      }
    });
  }
});
