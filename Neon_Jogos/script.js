document.getElementById("addJogo").addEventListener("click", function() {
    let novoJogo = prompt("Digite o nome do novo jogo:");
    if (novoJogo) {
        let lista = document.getElementById("listaJogos");
        let novoItem = document.createElement("li");
        novoItem.textContent = novoJogo;
        lista.appendChild(novoItem);
    }
});
