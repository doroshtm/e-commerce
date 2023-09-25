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
          imagem.addEventListener('animationend', () => {
            marca.style.animation = 'desaparece 1s linear forwards';
          }
          );
          marca.style.display = 'none';
          equipe.style.display = 'grid';
          equipe.style.animation = 'aparece 1s linear forwards';
         } 
        else {
          imagem.style.animation = 'roda3dREVERSO 1s linear forwards';
          marca.style.display = 'grid';
          equipe.style.display = 'none';
        }
          setTimeout(() => {
          isAnimationInProgress = false;
          imagem.style.pointerEvents = 'auto'; 
        }, 1000); 
      }
    });
  });
  