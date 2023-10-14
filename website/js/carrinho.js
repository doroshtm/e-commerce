function verificateAdd(amount, stock, id) {
    if (amount+1 > stock) {
        alert("Não há estoque suficiente para essa quantidade!");
    } else {
        window.location.href = "carrinho.php?id=" + id +"&action=add&amount=1&url=carrinho.php";
    }
}