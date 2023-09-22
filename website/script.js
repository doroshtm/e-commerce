document.addEventListener('DOMContentLoaded', () => {
    const imagem = document.querySelector('#sobre-mascotero-logo img');
    let chave = false;
    let isAnimationInProgress = false;
  
    imagem.addEventListener('click', () => {
      if (!isAnimationInProgress) {
        isAnimationInProgress = true;
        imagem.style.pointerEvents = 'none';
        chave = !chave;
  
        if (chave) {
          imagem.style.animation = 'roda3d 1s linear forwards';
        } else {
          imagem.style.animation = 'roda3dREVERSO 1s linear forwards';
        }
          setTimeout(() => {
          isAnimationInProgress = false;
          imagem.style.pointerEvents = 'auto'; 
        }, 1000); 
      }
    });
  });
  