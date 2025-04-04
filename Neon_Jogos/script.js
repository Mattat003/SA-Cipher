document.getElementById("addJogo").addEventListener("click", function() {
    let novoJogo = prompt("Digite o nome do novo jogo:");
    let urlImagem = prompt("Cole a URL da imagem do jogo (ou deixe em branco para nenhum)");

    if (novoJogo) {
        let lista = document.getElementById("listaJogos");

        let novoItem = document.createElement("li");

        let nomeJogo = document.createElement("p");
        nomeJogo.textContent = novoJogo;

        novoItem.appendChild(nomeJogo);

        if (urlImagem) {
            let imagem = document.createElement("img");
            imagem.src = urlImagem;
            imagem.alt = novoJogo;
            imagem.style.width = "100px";
            imagem.style.height = "100px";
            imagem.style.borderRadius = "5px";
            imagem.style.marginTop = "5px";
            novoItem.appendChild(imagem);
        }

        lista.appendChild(novoItem);
    }
});
