// 💡 Função para adicionar um novo jogo dinamicamente à lista
document.getElementById("addJogo").addEventListener("click", function () {
    
    // Exibe um prompt para o usuário digitar o nome do jogo
    let novoJogo = prompt("Digite o nome do novo jogo:");

    // Exibe um segundo prompt para o usuário inserir a URL da imagem (opcional)
    let urlImagem = prompt("Cole a URL da imagem do jogo (ou deixe em branco para nenhum)");

    // Verifica se o usuário digitou um nome para o jogo
    if (novoJogo) {
        let lista = document.getElementById("listaJogos"); // Obtém a lista de jogos

        let novoItem = document.createElement("li"); // Cria um novo item <li> para a lista

        let nomeJogo = document.createElement("p"); // Cria um <p> para o nome do jogo
        nomeJogo.textContent = novoJogo; // Define o nome do jogo no <p>

        novoItem.appendChild(nomeJogo); // Adiciona o nome do jogo ao novo item da lista

        // Se o usuário forneceu uma URL de imagem, adiciona a imagem ao item da lista
        if (urlImagem) {
            let imagem = document.createElement("img");
            imagem.src = urlImagem; // Define a imagem com a URL fornecida
            imagem.alt = novoJogo; // Adiciona um texto alternativo
            imagem.style.width = "230px";       // Define a largura da imagem
            imagem.style.height = "100px";      // Define a altura da imagem
            imagem.style.borderRadius = "30px"; // Arredonda as bordas da imagem
            imagem.style.marginTop = "5px";     // Adiciona um pequeno espaçamento superior
            novoItem.appendChild(imagem); // Adiciona a imagem ao novo item da lista
        }

        lista.appendChild(novoItem); // Adiciona o novo item à lista de jogos
    }
});

// 🔍 Filtro de comentários por jogo
document.getElementById("filtroJogo").addEventListener("change", function () {
    const filtro = this.value; // Obtém o valor do filtro selecionado pelo usuário
    const comentarios = document.querySelectorAll(".comentario"); // Obtém todos os comentários

    comentarios.forEach(c => {
        // Se o filtro for "todos" ou se o comentário for do jogo selecionado, exibe o comentário
        if (filtro === "todos" || c.dataset.jogo === filtro) {
            c.style.display = "block";
        } else {
            c.style.display = "none"; // Oculta os comentários que não correspondem ao filtro
        }
    });
});

// 📊 Criação do gráfico com Chart.js quando a página carregar
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('graficoJogos').getContext('2d'); // Obtém o elemento <canvas>

    // Criação do gráfico de barras
    new Chart(ctx, {
        type: 'bar', // Tipo de gráfico
        data: {
            labels: ['The Walking Dead', 'Mario', 'Sonic'], // Nomes dos jogos
            datasets: [{
                label: 'Popularidade dos jogos', // Legenda do gráfico
                data: [12, 19, 8], // Valores da popularidade de cada jogo
                backgroundColor: ['#ff4d4d', '#4d79ff', '#00cc99'], // Cores das barras
                borderColor: ['#cc0000', '#0033cc', '#008060'], // Cor da borda das barras
                borderWidth: 1 // Espessura da borda
            }]
        },
        options: {
            responsive: true, // Torna o gráfico responsivo
            maintainAspectRatio: false, // Permite que ele se ajuste ao tamanho do container
            scales: {
                y: {
                    beginAtZero: true, // Começa a contagem do eixo Y a partir do zero
                    title: {
                        display: true,
                        text: 'Pontuação' // Nome do eixo Y
                    }
                }
            }
        }
    });
});

// 📩 Formulário de sugestão de novo jogo
document.getElementById('formSugestao').addEventListener('submit', function(e) {
    e.preventDefault(); // Impede o recarregamento da página ao enviar o formulário
    
    alert('Sugestão enviada com sucesso! 🎮'); // Exibe um alerta de sucesso
    
    this.reset(); // Reseta os campos do formulário após o envio
}); 

// Script para gerenciar as abas interativas (tabs.js)
document.addEventListener('DOMContentLoaded', function() {
    // Selecionar todos os botões de aba
    const tabButtons = document.querySelectorAll('.tab-btn');
    
    // Adicionar evento de clique a cada botão
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover classe 'active' de todos os botões e conteúdos
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Adicionar classe 'active' ao botão clicado
            this.classList.add('active');
            
            // Mostrar o conteúdo correspondente
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });
});
