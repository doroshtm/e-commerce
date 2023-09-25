document.addEventListener('DOMContentLoaded', () => {
  const imagem = document.querySelector('#sobre-mascotero-logo img');
  const equipe = document.getElementById('sobre-mascotero-equipe');
  const marca = document.getElementById('sobre-mascotero-marca');
  let chave = false;
  let isAnimationInProgress = false;

  imagem.addEventListener('click', () => {
    if (!isAnimationInProgress) {
      isAnimationInProgress = true;
      imagem.style.pointerEvents = 'none';
      chave = !chave;
      if (chave) { 
        imagem.style.animation = 'roda3d 1s linear forwards';
        marca.style.animation = 'desaparece 0.5s linear forwards';
        imagem.addEventListener('animationend', () => {
          marca.style.display = 'none';
          equipe.style.display = 'grid';
          equipe.style.animation = 'aparece 0.5s linear forwards';
          isAnimationInProgress = false; 
          imagem.style.pointerEvents = 'auto'; 
        });
      } else {
        imagem.style.animation = 'roda3dREVERSO 1s linear forwards';
        equipe.style.animation = 'desaparece 0.5s linear forwards';
        imagem.addEventListener('animationend', () => {
          equipe.style.display = 'none';
          marca.style.display = 'grid';
          marca.style.animation = 'aparece 0.5s linear forwards';
          isAnimationInProgress = false; // Define como false quando a animação terminar
          imagem.style.pointerEvents = 'auto'; // Reativa os eventos de clique
        });
      }
    }
  });
});
