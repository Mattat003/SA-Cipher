// Função para gerar um número inteiro aleatório entre min e max (inclusive)
function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// --- Atualização dos Cartões de Estatísticas ---
function updateStatCards() {
    document.getElementById('total-users-stat').textContent = getRandomInt(1000, 5000).toLocaleString('pt-BR');
    document.getElementById('total-games-stat').textContent = getRandomInt(50, 500).toLocaleString('pt-BR');
    document.getElementById('pending-rentals-stat').textContent = getRandomInt(5, 50).toLocaleString('pt-BR');
    document.getElementById('total-sales-stat').textContent = getRandomInt(10000, 100000).toLocaleString('pt-BR');
}

// --- Atualização dos Gráficos com Chart.js ---

// Variáveis para armazenar as instâncias dos gráficos
let userProfileChartInstance;
let gameReleaseChartInstance;

function updateCharts() {
    // Dados para o Gráfico de Perfil de Usuários (ex: Idade, Tipo de Usuário, etc.)
    const userProfileData = {
        labels: ['Jovem (18-25)', 'Adulto (26-40)', 'Maduro (41-60)', 'Sênior (60+)'],
        datasets: [{
            label: 'Usuários por Perfil',
            data: [
                getRandomInt(200, 1000), // Jovem
                getRandomInt(500, 2000), // Adulto
                getRandomInt(100, 800),  // Maduro
                getRandomInt(20, 200)    // Sênior
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)', // Vermelho
                'rgba(54, 162, 235, 0.6)', // Azul
                'rgba(255, 206, 86, 0.6)', // Amarelo
                'rgba(75, 192, 192, 0.6)'  // Verde
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Dados para o Gráfico de Lançamento de Jogos (ex: Lançamentos por ano/mês)
    const gameReleaseLabels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    const gameReleaseData = {
        labels: gameReleaseLabels,
        datasets: [{
            label: 'Lançamentos de Jogos (2025)',
            data: Array.from({length: 12}, () => getRandomInt(5, 30)), // 12 meses
            backgroundColor: 'rgba(153, 102, 255, 0.6)', // Roxo
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
        }]
    };

    // Obtenha os contextos dos canvas
    const userCtx = document.getElementById('userProfileChart').getContext('2d');
    const gameCtx = document.getElementById('gameReleaseChart').getContext('2d');

    // Destroi instâncias de gráficos existentes para evitar duplicidade
    if (userProfileChartInstance) {
        userProfileChartInstance.destroy();
    }
    if (gameReleaseChartInstance) {
        gameReleaseChartInstance.destroy();
    }

    // Cria/Atualiza o Gráfico de Perfil de Usuários (Pizza)
    userProfileChartInstance = new Chart(userCtx, {
        type: 'pie', // Pode ser 'doughnut' também
        data: userProfileData,
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Distribuição de Usuários por Perfil'
                },
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Cria/Atualiza o Gráfico de Lançamento de Jogos (Barras)
    gameReleaseChartInstance = new Chart(gameCtx, {
        type: 'bar',
        data: gameReleaseData,
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Lançamentos de Jogos por Mês'
                },
                legend: {
                    display: false // Não precisa de legenda para um único dataset
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Número de Jogos'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Mês'
                    }
                }
            }
        }
    });
}

// --- Função Principal de Atualização ---
function updateDashboard() {
    updateStatCards(); // Atualiza os números dos cartões
    updateCharts();    // Atualiza os gráficos
    console.log("Dashboard atualizado com novos dados aleatórios!");
}

// --- Dispara a atualização inicial quando a página carrega ---
document.addEventListener('DOMContentLoaded', updateDashboard);

// --- Configura a atualização automática a cada X segundos (ex: 5 segundos) ---
const intervalInSeconds = 5;
setInterval(updateDashboard, intervalInSeconds * 1000); // Converte segundos para milissegundos