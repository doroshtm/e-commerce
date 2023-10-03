    document.addEventListener('DOMContentLoaded', function() {
        
    const filtro = document.getElementById('filtro');
    const seta = document.querySelector('#filtro img');
    const dropdown = document.getElementById('dropdown');
    let chave = true;
    const checkboxes = document.querySelectorAll('.checkboxes input[type=checkbox]');
    let nomes = ["CTI", "Informática", "Mecânica", "Eletrônica"];
    const corpo = document.getElementById('corpo-filtro-display');
    const elementosCriados = {};

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
            filterProducts();
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
    
        const checkbox_cti = document.getElementById('cti');
        const checkbox_info = document.getElementById('info');
        const checkbox_mec = document.getElementById('mec');
        const checkbox_eletro = document.getElementById('eletro');
        var search = document.getElementById('pesquisa');

        checkbox_cti.addEventListener('change', function() {
            filterProducts();
        });

        checkbox_info.addEventListener('change', function() {
            filterProducts();
        });

        checkbox_mec.addEventListener('change', function() {
            filterProducts();
        });
        checkbox_eletro.addEventListener('change', function() {
            filterProducts();
        });
        search.addEventListener('keyup', function() {
            filterProducts();
        });


        function filterProducts() {
            const products = document.querySelectorAll('.produto');
            const ctiChecked = checkbox_cti.checked;
            const infoChecked = checkbox_info.checked;
            const mecChecked = checkbox_mec.checked;
            const eletroChecked = checkbox_eletro.checked;

            let hasFilters = false;
            products.forEach(function(product) {
                const category = product.getAttribute('data-categoria');
                let showProduct = false;

                if (ctiChecked && category == 'CTI') {
                    showProduct = true;
                    hasFilters = true;
                }

                else if (infoChecked && category == 'Informática') {
                    showProduct = true;
                    hasFilters = true;
                }

                else if (mecChecked && category == 'Mecânica') {
                    showProduct = true;
                    hasFilters = true;
                }
                else if (eletroChecked && category == 'Eletrônica') {
                    showProduct = true;
                    hasFilters = true;
                }
                if (search.value != '' && !product.getAttribute('data-nome').toLowerCase().includes(search.value.toLowerCase())) {
                    showProduct = false;
                }

                if (showProduct) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
            if (!hasFilters) {
                products.forEach(function(product) {
                    if (search.value == '' || product.getAttribute('data-nome').toLowerCase().includes(search.value.toLowerCase())) {
                        product.style.display = 'block';
                    }
                });
            }
        }
    });