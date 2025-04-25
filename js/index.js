const searchInput = document.getElementById("searchInput");
const resultsContainer = document.getElementById("results");

const items = {
    "The Last of Us": "tlou.html",
    "Red Dead Redemption 2": "rdr2.html",
    "Minecraft": "minecraft.html",
    "God of War": "gow.html",
    "Cyberpunk 2077": "cyberpunk.html",
    "Hollow Knight": "hollowknight.html",
    "FIFA 24": "fifa.html"
};

searchInput.addEventListener("input", () => {
    const searchTerm = searchInput.value.toLowerCase().trim();
    resultsContainer.innerHTML = "";

    if (searchTerm === "") {
        resultsContainer.style.display = "none";
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
            link.href = items[item]; // Vai para a página correspondente
            resultsContainer.appendChild(link);
        });
    } else {
        const noResult = document.createElement("div");
        noResult.className = "result-item";
        noResult.textContent = "Nenhum resultado encontrado.";
        resultsContainer.appendChild(noResult);
    }

    resultsContainer.style.display = "block";
});
