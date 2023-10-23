// O objetivo dessa função é melhorar a experiência do usuário, evitando que ele tenha que 
// recarregar a página para adicionar um produto ao carrinho, somente para descobrir que não há estoque suficiente para a quantidade que ele deseja comprar.
// O site também fica mais bonito, porque o PHP não carrega a página inteira se houver um erro, somente o alerta.
function verificateAdd(amount, stock, id) {
    if (amount+1 > stock) {
        alert("Não há estoque suficiente para essa quantidade!");
    } else {
        window.location.href = "carrinho.php?id=" + id +"&action=add&amount=1&url=carrinho.php";
    }
}