document.addEventListener('DOMContentLoaded', () => {
    let chave = true;
    const emcima = document.getElementById('emcima');
    const embaixo = document.getElementById('embaixo');
    emcima.addEventListener('click', () => {
        console.log("clicou");
        if (chave)
            embaixo.style.animation = 'aumenta 0.5s forwards';
        else
            embaixo.style.animation = 'diminui 0.5s forwards';
        chave = !chave;

    });
});