document.addEventListener('DOMContentLoaded', function() {
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