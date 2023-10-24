
document.addEventListener('DOMContentLoaded', () => {
    function mostrarPopup(popup) {
        popup.showModal();
        popup.style.display = 'flex';
    }
    function esconderPopup(popup) {
        popup.close();
        popup.style.display = 'none';
    }

    const formPopup = document.querySelector('.content-popup form');
    const botaoAbrir = document.getElementById('botao-abrir-popup');
    const botaoFechar = document.getElementById('botao-fechar-popup');
    const checkboxConfirmar = document.getElementById('mostrar-confirmacao');
    let chave = true;
    checkboxConfirmar.addEventListener('change', () => {
        if (chave) {
            chave = false;
            formPopup.style.animation = "mostraform 1s linear forwards";
        } else {
            chave = true;
            formPopup.style.animation = "escondeform 0.8s linear forwards";
        }
    }	);

    botaoAbrir.addEventListener('click', () => {
        const overlay = document.getElementById('popup-confirmar');
        console.log("funcionando");
        mostrarPopup(overlay);
    });
    botaoFechar.addEventListener('click', () => {
        const overlay = document.getElementById('popup-confirmar');
        esconderPopup(overlay);
    });
});
