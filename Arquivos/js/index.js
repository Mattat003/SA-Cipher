document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const resultsContainer = document.getElementById("results");

    // Itens de exemplo para busca
    const items = {
        "The Last of Us": "tlou.html",
        "Red Dead Redemption 2": "rdr.html",
        "Minecraft": "minecraft.html",
        "God of War": "gow.html",
        "Cyberpunk 2077": "cyberpunk.html",
        "Hollow Knight": "hollowknight.html",
        "FIFA 22": "fifa.html"
    };

    // Limpar o campo de busca quando a página for carregada
    window.addEventListener('load', function() {
        searchInput.value = '';  // Limpa o campo de busca
        resultsContainer.innerHTML = '';  // Limpa qualquer resultado anterior
        resultsContainer.style.display = "none";  // Esconde a lista de resultados
    });

    // Função para tratar o input de busca
    searchInput.addEventListener("input", () => {
        const searchTerm = searchInput.value.toLowerCase().trim();
        resultsContainer.innerHTML = "";  // Limpa os resultados anteriores

        if (searchTerm === "") {
            resultsContainer.style.display = "none";  // Esconde resultados quando não há texto
            return;
        }

        const filteredItems = Object.keys(items).filter(item =>
            item.toLowerCase().includes(searchTerm)
        );

        if (filteredItems.length > 0) {
            filteredItems.forEach(item => {
                const link = document.createElement("a");
                link.className = "result-item";
                link.textContent = item;
                link.href = items[item];  // Vai para a página correspondente
                resultsContainer.appendChild(link);
            });
        } else {
            const noResult = document.createElement("div");
            noResult.className = "result-item";
            noResult.textContent = "Nenhum resultado encontrado.";
            resultsContainer.appendChild(noResult);
        }

        resultsContainer.style.display = "block";  // Exibe os resultados
    });
});
