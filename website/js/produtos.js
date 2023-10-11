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
            console.log("teste")
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

        const orderName = document.getElementById('seta-baixo-nome');
        const orderPrice = document.getElementById('seta-baixo-preco');
        const orderName2 = document.getElementById('seta-cima-nome');
        const orderPrice2 = document.getElementById('seta-cima-preco');

        orderName.addEventListener('click', function() {
            console.log("Eoasdopsakd");
            changeColor(orderName);
            orderByName('decreasing');
        });
        orderPrice.addEventListener('click', function() {
            changeColor(orderPrice);
            orderByPrice('decreasing');
        });
        orderName2.addEventListener('click', function() {
            changeColor(orderName2);
            orderByName('increasing');
        });
        orderPrice2.addEventListener('click', function() {
            changeColor(orderPrice2);
            orderByPrice('increasing');
        });

        function orderByName(order) {
            var products = document.querySelectorAll('.produto');
            var productsArray = Array.prototype.slice.call(products, 0);
            var productsContainer = document.querySelector('#grid-produtos');
            if (order == 'increasing') {
                productsArray.sort(function(a, b) {
                    var nameA = a.getAttribute('data-nome').toLowerCase();
                    var nameB = b.getAttribute('data-nome').toLowerCase();
                    if (nameA < nameB) {
                        return -1;
                    }
                    if (nameA > nameB) {
                        return 1;
                    }
                    return 0;
                });
                
                productsArray.forEach(function(item) {
                    productsContainer.appendChild(item);
                });
            } else {
                productsArray.sort(function(a, b) {
                    var nameA = a.getAttribute('data-nome').toLowerCase();
                    var nameB = b.getAttribute('data-nome').toLowerCase();
                    if (nameA > nameB) {
                        return -1;
                    }
                    if (nameA < nameB) {
                        return 1;
                    }
                    return 0;
                });
                
                productsArray.forEach(function(item) {
                    productsContainer.appendChild(item);
                });
            }
        }
        function changeColor(seta)
        {
            seta.style.filter = 'brightness(0) invert(19%) sepia(95%) saturate(3970%) hue-rotate(244deg) brightness(101%) contrast(106%)';
            let setas = document.querySelectorAll('.linha-checkbox img')
            console.log(setas.lenght);
            for (let i=0; i<setas.length;i++)
            {
                 if(setas[i] != seta) 
                    setas[i].style.filter = 'none';
            }
        }
        function orderByPrice(order) {
            var products = document.querySelectorAll('.produto');
            var productsArray = Array.prototype.slice.call(products, 0);
            var productsContainer = document.querySelector('#grid-produtos');
            if(order == 'increasing') {
                productsArray.sort(function(a, b) {
                    var priceA = parseFloat(a.getAttribute('data-preco'));
                    var priceB = parseFloat(b.getAttribute('data-preco'));
                    if (priceA < priceB) {
                        return -1;
                    }
                    if (priceA > priceB) {
                        return 1;
                    }
                    return 0;
                });
                
                productsArray.forEach(function(item) {
                    productsContainer.appendChild(item);
                });
            } else {
                productsArray.sort(function(a, b) {
                    var priceA = parseFloat(a.getAttribute('data-preco'));
                    var priceB = parseFloat(b.getAttribute('data-preco'));
                    if (priceA > priceB) {
                        return -1;
                    }
                    if (priceA < priceB) {
                        return 1;
                    }
                    return 0;
                });
                
                productsArray.forEach(function(item) {
                    productsContainer.appendChild(item);
                });
            }
        }
    });