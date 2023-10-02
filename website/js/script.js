document.addEventListener('DOMContentLoaded', () => {
  const imagem = document.querySelector('#sobre-mascotero-logo img');
  const equipe = document.getElementById('sobre-mascotero-equipe');
  const marca = document.getElementById('sobre-mascotero-marca');
  let chave = false;
  let animationImagem = false;
  let terminouEquipe = true;
  let terminouMarca = true;

imagem.addEventListener('click', () => {
    if (!animationImagem) {
      animationImagem = true;
      imagem.style.pointerEvents = 'none';
      chave = !chave; 
      if (chave) { 
          imagem.style.animation = 'roda3d 1s linear forwards';
          marca.style.animation = 'desaparece 0.5s linear forwards';
        setTimeout(()=> {
          if (terminouMarca) {
          marca.style.display = 'none';
          equipe.style.display = 'grid'; 
          equipe.style.animation = 'aparece 0.5s linear forwards';
          }
        },500);
      }
      
      else {
        imagem.style.animation = 'roda3dREVERSO 1s linear forwards';
        equipe.style.animation = 'desaparece 0.5s linear forwards';
        setTimeout(() => {
          if (terminouEquipe) {
          equipe.style.display = 'none';
          marca.style.display = 'grid';
          marca.style.animation = 'aparece 0.5s linear forwards';
          }
        },500);
      }
      
      imagem.addEventListener('animationend', () => {
        animationImagem = false;
        imagem.style.pointerEvents = 'auto'; 
      });
    }
  });
  
});
