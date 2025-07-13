// Tela_ADM.js

document.addEventListener('DOMContentLoaded', () => {
    // --- Elementos Comuns do DOM ---
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const sidebarToggle = document.querySelector('.sidebar-toggle'); // Botão desktop
    const mobileSidebarToggle = document.querySelector('.sidebar-toggle-mobile'); // Botão mobile
    const navLinks = document.querySelectorAll('.sidebar-menu a[data-section]');
    const contentSections = document.querySelectorAll('.content-section');
    const modalBackdrop = document.getElementById('main-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalBody = document.getElementById('modal-body');
    const sidebarOverlay = document.querySelector('.sidebar-overlay'); // Overlay para mobile

    // Variáveis para instâncias do Chart.js
    let userProfileChartInstance;
    let gameReleaseChartInstance;

    // --- Dados em Memória (Simulando um Banco de Dados Frontend) ---
    // Estes arrays serão manipulados pelas funções de CRUD.
    // Em um ambiente real, estes dados viriam de um backend/API.

    // Função para gerar um ID único simples (para simulação)
    const generateId = (arr) => arr.length > 0 ? Math.max(...arr.map(item => item.id || item.pk_cargo || item.pk_usuario || item.pk_adm || item.pk_funcionario || item.pk_codgame || item.pk_jogo || item.pk_genero || item.pk_tema || item.pk_estilo || item.pk_plataforma || item.pk_idioma || item.pk_modo)) + 1 : 1;

    let db = {
        cargos: JSON.parse(localStorage.getItem('db_cargos')) || [
            { pk_cargo: 1, nome_cargo: 'Administrador Geral', nivel_cargo: 5 },
            { pk_cargo: 2, nome_cargo: 'Funcionário', nivel_cargo: 1 }
        ],
        usuarios: JSON.parse(localStorage.getItem('db_usuarios')) || [
            { pk_usuario: 1, nome_user: 'Lúcio Andrade', email_user: 'lucio@email.com', senha_user: '12345678', data_criacao: '2024-03-15', senha_temporaria: false, foto_perfil: '', perfil: 'cliente' },
            { pk_usuario: 2, nome_user: 'Maya Costa', email_user: 'maya@email.com', senha_user: '12345678', data_criacao: '2024-07-02', senha_temporaria: false, foto_perfil: '', perfil: 'cliente' },
            { pk_usuario: 3, nome_user: 'Henrique Vasques', email_user: 'henrique@email.com', senha_user: '12345678', data_criacao: '2024-11-28', senha_temporaria: false, foto_perfil: '', perfil: 'cliente' },
            { pk_usuario: 4, nome_user: 'Júlia Monteiro', email_user: 'julia@email.com', senha_user: '12345678', data_criacao: '2025-01-10', senha_temporaria: false, foto_perfil: '', perfil: 'cliente' },
            { pk_usuario: 5, nome_user: 'Caio Silveira', email_user: 'caio@email.com', senha_user: '12345678', data_criacao: '2025-04-05', senha_temporaria: false, foto_perfil: '', perfil: 'cliente' }
        ],
        adms: JSON.parse(localStorage.getItem('db_adms')) || [
            { pk_adm: 1, nome_adm: 'Matheus Leal', email_adm: 'matheus@admin.com', senha_user: '12345678', fk_cargo: 1 },
            { pk_adm: 2, nome_adm: 'Endryo Bittencourt', email_adm: 'endryo@email.com', senha_user: '12345678', fk_cargo: 1 },
            { pk_adm: 3, nome_adm: 'Pamella Rafaeli', email_adm: 'pamella@admin.com', senha_user: '12345678', fk_cargo: 1 },
            { pk_adm: 4, nome_adm: 'Neon Gustavo', email_adm: 'neon@email.com', senha_user: '12345678', fk_cargo: 2 },
            { pk_adm: 5, nome_adm: 'Amanda de Oliveira', email_adm: 'amanda@email.com', senha_user: '12345678', fk_cargo: 2 }
        ],
        funcionarios: JSON.parse(localStorage.getItem('db_funcionarios')) || [
            // Exemplos de funcionários se a tabela estiver separada do ADM
            // Caso contrário, funcionários seriam gerenciados via tabela 'adm' com fk_cargo 2
            // Para este cenário, vamos manter adm e funcionario separados como no SQL.
            // O SQL fornecido não tem inserts para a tabela 'funcionario', então vamos adicionar alguns aqui para a simulação:
            { pk_funcionario: 1, nome_func: 'Fernanda Martins', email_func: 'fer.martins@cypher.com', senha_func: 'senhafunc1', fk_cargo: 2 },
            { pk_funcionario: 2, nome_func: 'Rafael Santos', email_func: 'rafael.santos@cypher.com', senha_func: 'senhafunc2', fk_cargo: 2 }
        ],
        codigo_games: JSON.parse(localStorage.getItem('db_codigo_games')) || [
            { pk_codgame: 1, codigo: 'ABCD1-EFGH2-IJKL3-MNOP4-QRST5' },
            { pk_codgame: 2, codigo: 'ZXCV6-ASDF7-QWER8-TYUI9-GHJK0' },
            { pk_codgame: 3, codigo: 'LMNO1-PQRS2-TUVW3-XYZA4-BCDE5' },
            { pk_codgame: 4, codigo: 'F1G2H-3J4K5-L6M7N-8O9P0-Q1R2S' },
            { pk_codgame: 5, codigo: '1234A-5678B-9CDEF-GHIJK-LMNOP' },
            { pk_codgame: 6, codigo: 'QAZ12-WSX34-EDC56-RFV78-TGB90' }
        ],
        jogos: JSON.parse(localStorage.getItem('db_jogos')) || [
            { pk_jogo: 1, nome_jogo: 'The Last of Us Part II', data_lanc: '2020-06-19', fk_codigo: 1, desenvolvedora: 'Naughty Dog', disponivel_locacao: true, imagem_jogo: 'uploads/jogo_685aebf609de3.jpg', url_jogo: 'https://example.com/tlou2' },
            { pk_jogo: 2, nome_jogo: 'Elden Ring', data_lanc: '2022-02-25', fk_codigo: 2, desenvolvedora: 'FromSoftware', disponivel_locacao: true, imagem_jogo: 'uploads/jogo_685ae7e5a55a9.jpg', url_jogo: 'https://example.com/eldenring' },
            { pk_jogo: 3, nome_jogo: 'Mario Odyssey', data_lanc: '2017-10-27', fk_codigo: 3, desenvolvedora: 'Nintendo', disponivel_locacao: false, imagem_jogo: 'uploads/jogo_685ae82644148.jpg', url_jogo: 'https://example.com/mario' },
            { pk_jogo: 4, nome_jogo: 'Enigma do Medo', data_lanc: '2024-11-28', fk_codigo: 4, desenvolvedora: 'Independente', disponivel_locacao: true, imagem_jogo: 'uploads/jogo_685adc87c44be.jpg', url_jogo: 'https://example.com/enigma' },
            { pk_jogo: 5, nome_jogo: 'Blue Prince', data_lanc: '2025-04-10', fk_codigo: 5, desenvolvedora: 'Independente', disponivel_locacao: true, imagem_jogo: 'uploads/jogo_685ae89243103.jpg', url_jogo: 'https://example.com/blueprince' },
            { pk_jogo: 6, nome_jogo: 'Assassin\'s Creed Valhalla', data_lanc: '2020-11-10', fk_codigo: 6, desenvolvedora: 'Ubisoft', disponivel_locacao: true, imagem_jogo: 'uploads/jogo_685ae8cc1ba2a.jpg', url_jogo: 'https://example.com/acvalhalla' }
        ],
        biblioteca_usuarios: JSON.parse(localStorage.getItem('db_biblioteca_usuarios')) || [],
        compras: JSON.parse(localStorage.getItem('db_compras')) || [],
        locacoes_pendentes: JSON.parse(localStorage.getItem('db_locacoes_pendentes')) || [],
        publicadoras: JSON.parse(localStorage.getItem('db_publicadoras')) || [
            { pk_publicadora: 1, nome_publicadora: 'Sony Interactive Entertainment' },
            { pk_publicadora: 2, nome_publicadora: 'Bandai Namco Entertainment' },
            { pk_publicadora: 3, nome_publicadora: 'Nintendo' },
            { pk_publicadora: 4, nome_publicadora: 'Ubisoft' }
        ],
        generos: JSON.parse(localStorage.getItem('db_generos')) || [
            { pk_genero: 1, nome_gen: 'Ação' }, { pk_genero: 2, nome_gen: 'Aventura' }, { pk_genero: 3, nome_gen: 'Battle Royale' }, { pk_genero: 4, nome_gen: 'Cartas/TCG (Trading Card Game)' }, { pk_genero: 5, nome_gen: 'Corrida' },
            { pk_genero: 6, nome_gen: 'Educacional' }, { pk_genero: 7, nome_gen: 'Esporte' }, { pk_genero: 8, nome_gen: 'Estratégia' }, { pk_genero: 9, nome_gen: 'Furtivo (Stealth)' }, { pk_genero: 10, nome_gen: 'Hack and Slash' },
            { pk_genero: 11, nome_gen: 'Horror/Terror' }, { pk_genero: 12, nome_gen: 'Indie' }, { pk_genero: 13, nome_gen: 'Luta' }, { pk_genero: 14, nome_gen: 'Metroidvania' }, { pk_genero: 15, nome_gen: 'MMO' },
            { pk_genero: 16, nome_gen: 'Mundo Aberto' }, { pk_genero: 17, nome_gen: 'Musical/Ritmo' }, { pk_genero: 18, nome_gen: 'Plataforma' }, { pk_genero: 19, nome_gen: 'Puzzle/Quebra-cabeça' }, { pk_genero: 20, nome_gen: 'RPG' },
            { pk_genero: 21, nome_gen: 'Roguelike/Roguelite' }, { pk_genero: 22, nome_gen: 'Sandbox' }, { pk_genero: 23, nome_gen: 'Simulação' }, { pk_genero: 24, nome_gen: 'Survival' }, { pk_genero: 25, nome_gen: 'Tiro (FPS/TPS)' },
            { pk_genero: 26, nome_gen: 'Visual Novel' }, { pk_genero: 27, nome_gen: 'Co-op Local' }, { pk_genero: 28, nome_gen: 'Co-op Online' }, { pk_genero: 29, nome_gen: 'LAN' }, { pk_genero: 30, nome_gen: 'Multijogador Online' },
            { pk_genero: 31, nome_gen: 'PvE (Player vs Enviroment)' }, { pk_genero: 32, nome_gen: 'PvP (Player vc Player)' }, { pk_genero: 33, nome_gen: 'Singleplayer' }
        ],
        temas: JSON.parse(localStorage.getItem('db_temas')) || [
            { pk_tema: 1, nome_tema: 'Apocalipse' }, { pk_tema: 2, nome_tema: 'Cyberpunk' }, { pk_tema: 3, nome_tema: 'Fantasia' }, { pk_tema: 4, nome_tema: 'Faroeste' }, { pk_tema: 5, nome_tema: 'Horror/Sobrenatural' },
            { pk_tema: 6, nome_tema: 'Magia' }, { pk_tema: 7, nome_tema: 'Romance' }, { pk_tema: 8, nome_tema: 'Mundo Pós-apocalíptico' }, { pk_tema: 9, nome_tema: 'Piratas' }, { pk_tema: 10, nome_tema: 'Terror Psicológico' }
        ],
        estilos: JSON.parse(localStorage.getItem('db_estilos')) || [
            { pk_estilo: 1, nome_estilo: 'Mundo Aberto' }, { pk_estilo: 2, nome_estilo: 'Point and Click' }, { pk_estilo: 3, nome_estilo: 'Roguelike' }, { pk_estilo: 4, nome_estilo: 'Sandbox' }, { pk_estilo: 5, nome_estilo: 'Crafting' }
        ],
        plataformas: JSON.parse(localStorage.getItem('db_plataformas')) || [
            { pk_plataforma: 1, nome_plat: 'Windows' }, { pk_plataforma: 2, nome_plat: 'Linux' }, { pk_plataforma: 3, nome_plat: 'MacOS' }, { pk_plataforma: 4, nome_plat: 'PlayStation 4|5' }, { pk_plataforma: 5, nome_plat: 'Xbox Series X|S' }, { pk_plataforma: 6, nome_plat: 'Nintendo Switch' }
        ],
        idiomas: JSON.parse(localStorage.getItem('db_idiomas')) || [
            { pk_idioma: 1, nome_idioma: 'Alemão' }, { pk_idioma: 2, nome_idioma: 'Chiês (Simplificado/Tradicional)' }, { pk_idioma: 3, nome_idioma: 'Coreano' }, { pk_idioma: 4, nome_idioma: 'Espanhol' }, { pk_idioma: 5, nome_idioma: 'Francês' },
            { pk_idioma: 6, nome_idioma: 'Inglês' }, { pk_idioma: 7, nome_idioma: 'Italiano' }, { pk_idioma: 8, nome_idioma: 'Japonês' }, { pk_idioma: 9, nome_idioma: 'Português' }, { pk_idioma: 10, nome_idioma: 'Russo' }
        ],
        modos: JSON.parse(localStorage.getItem('db_modos')) || [
            { pk_modo: 1, nome_modo: 'Co-op Local' }, { pk_modo: 2, nome_modo: 'Co-op Online' }, { pk_modo: 3, nome_modo: 'LAN' }, { pk_modo: 4, nome_modo: 'Multijogador Online' }, { pk_modo: 5, nome_modo: 'PvE (Player vs Enviroment)' },
            { pk_modo: 6, nome_modo: 'PvP (Player vc Player)' }, { pk_modo: 7, nome_modo: 'Singleplayer' }
        ],
        jogo_genero: JSON.parse(localStorage.getItem('db_jogo_genero')) || [],
        jogo_tema: JSON.parse(localStorage.getItem('db_jogo_tema')) || [],
        jogo_estilo: JSON.parse(localStorage.getItem('db_jogo_estilo')) || [],
        jogo_plataforma: JSON.parse(localStorage.getItem('db_jogo_plataforma')) || [],
        jogo_idioma: JSON.parse(localStorage.getItem('db_jogo_idioma')) || [],
        jogo_modo: JSON.parse(localStorage.getItem('db_jogo_modo')) || [],
        amigos: JSON.parse(localStorage.getItem('db_amigos')) || [],
        mensagens: JSON.parse(localStorage.getItem('db_mensagens')) || [],
        pedidos_amizade: JSON.parse(localStorage.getItem('db_pedidos_amizade')) || [],
        historico_jogos: JSON.parse(localStorage.getItem('db_historico_jogos')) || [
            { id: 1, usuario: 'Lúcio Andrade', nome_jogo: 'The Last of Us Part II', hora_entrada: '2024-06-20T10:30:00' },
            { id: 2, usuario: 'Maya Costa', nome_jogo: 'Elden Ring', hora_entrada: '2024-06-20T14:00:00' },
            { id: 3, usuario: 'Henrique Vasques', nome_jogo: 'Mario Odyssey', hora_entrada: '2024-06-21T09:15:00' },
            { id: 4, usuario: 'Lúcio Andrade', nome_jogo: 'Elden Ring', hora_entrada: '2024-06-21T18:45:00' }
        ]
    };

    // Salvar dados no localStorage
    const saveData = () => {
        for (const key in db) {
            localStorage.setItem(`db_${key}`, JSON.stringify(db[key]));
        }
    };

    // Carregar dados (feito na inicialização)
    // Se nenhum dado existir no localStorage, ele usará os valores padrão.

    // Carregar configurações do usuário (tema e idioma)
    let currentLanguage = localStorage.getItem('adminLang') || 'pt-BR';
    let currentTheme = localStorage.getItem('adminTheme') || 'dark'; // 'dark' por padrão
    let currentPrimaryColor = localStorage.getItem('adminPrimaryColor') || '#6f42c1'; // Cor padrão

    // Aplicar tema e cor primária na inicialização
    document.body.setAttribute('data-theme', currentTheme);
    document.documentElement.style.setProperty('--primary-color', currentPrimaryColor);


    // Tradução
    const translations = {
        'pt-BR': {
            dashboard: 'Dashboard',
            users: 'Usuários',
            employees: 'Funcionários',
            categories: 'Categorias',
            games: 'Jogos',
            reports: 'Relatórios',
            history: 'Histórico',
            profile: 'Perfil',
            settings: 'Configurações',
            logout: 'Sair',
            totalUsers: 'Total de Usuários',
            totalGames: 'Total de Jogos',
            pendingRentals: 'Locações Pendentes',
            totalSales: 'Total de Compras',
            searchPlaceholder: 'Buscar...',
            allProfiles: 'Todos os Perfis',
            admin: 'Admin',
            employee: 'Funcionário',
            client: 'Cliente',
            allStatuses: 'Todos os Status',
            temporaryPassword: 'Senha Temporária',
            permanentPassword: 'Senha Permanente',
            addUser: 'Adicionar Usuário',
            userName: 'Nome do Usuário',
            userEmail: 'Email',
            userProfile: 'Perfil',
            userTemporaryPassword: 'Senha Temporária',
            actions: 'Ações',
            edit: 'Editar',
            delete: 'Excluir',
            addEditUser: 'Adicionar/Editar Usuário',
            save: 'Salvar',
            cancel: 'Cancelar',
            allRoles: 'Todos os Cargos',
            addEmployee: 'Adicionar Funcionário',
            employeeName: 'Nome',
            employeeEmail: 'Email',
            employeeRole: 'Cargo',
            addEditEmployee: 'Adicionar/Editar Funcionário',
            genre: 'Gênero',
            theme: 'Tema',
            style: 'Estilo',
            platform: 'Plataforma',
            language: 'Idioma',
            mode: 'Modo',
            searchCategoryPlaceholder: 'Buscar categoria...',
            addCategory: 'Adicionar Categoria',
            categoryName: 'Nome da Categoria',
            addEditCategory: 'Adicionar/Editar Categoria',
            allAvailability: 'Todos',
            availableForRent: 'Disponível para Locação',
            notAvailableForRent: 'Não Disponível para Locação',
            addGame: 'Adicionar Jogo',
            gameName: 'Nome do Jogo',
            releaseDate: 'Data de Lançamento',
            developer: 'Desenvolvedora',
            gameCode: 'Código',
            addEditGame: 'Adicionar/Editar Jogo',
            gameImage: 'Imagem do Jogo (URL)',
            gameUrl: 'URL do Jogo',
            reportsTitle: 'Gerar Relatórios',
            reportType: 'Tipo de Relatório',
            reportTypeUsers: 'Usuários',
            reportTypeEmployees: 'Funcionários',
            reportTypeGames: 'Jogos',
            reportTypePurchases: 'Compras',
            reportTypePendingRentals: 'Locações Pendentes',
            reportTypeGameHistory: 'Histórico de Jogos',
            reportTypeCustom: 'Relatório Personalizado (Texto Livre)',
            reportPeriodStart: 'Data de Início',
            reportPeriodEnd: 'Data de Fim',
            customReportText: 'Conteúdo do Relatório Personalizado:',
            customReportFilename: 'Nome do Arquivo (sem extensão):',
            generateReport: 'Gerar Relatório',
            downloadReport: 'Download Relatório',
            reportPreview: 'Prévia do Relatório:',
            searchHistoryPlaceholder: 'Buscar por usuário ou jogo...',
            historyUser: 'Usuário',
            historyGame: 'Jogo',
            entryTime: 'Hora de Entrada',
            profileTitle: 'Meu Perfil',
            changeAvatar: 'Alterar Avatar',
            personalInfo: 'Informações Pessoais',
            profileName: 'Nome:',
            profileEmail: 'Email:',
            profileRole: 'Função:',
            updateProfile: 'Atualizar Perfil',
            changePassword: 'Alterar Senha',
            currentPassword: 'Senha Atual:',
            newPassword: 'Nova Senha:',
            confirmNewPassword: 'Confirmar Nova Senha:',
            settingsTitle: 'Configurações do Painel',
            appearanceSettings: 'Aparência',
            themeMode: 'Modo do Tema:',
            themeLight: 'Claro',
            themeDark: 'Escuro',
            primaryColor: 'Cor Primária:',
            languageSettings: 'Configurações de Idioma',
            selectLanguage: 'Selecionar Idioma:',
            dataUtilities: 'Dados & Utilidades',
            clearCache: 'Limpar Cache Local',
            exportSettings: 'Exportar Configurações',
            importSettings: 'Importar Configurações',
            activityLogs: 'Logs de Atividade Recente:',
            aboutApp: 'Sobre o Cypher Corporation',
            appVersion: 'Versão:',
            lastUpdated: 'Última Atualização:',
            helpDocs: 'Acessar Documentação de Ajuda',
            logActivityLogin: 'Usuário logado no painel administrativo.',
            logActivityLogout: 'Usuário deslogado do painel administrativo.',
            logActivityViewSection: 'Visualizou a seção: {section}',
            logActivityAddUser: 'Adicionado novo usuário: {name}',
            logActivityUpdateUser: 'Atualizado usuário: {name}',
            logActivityDeleteUser: 'Excluído usuário: {name}',
            logActivityAddEmployee: 'Adicionado novo funcionário: {name}',
            logActivityUpdateEmployee: 'Atualizado funcionário: {name}',
            logActivityDeleteEmployee: 'Excluído funcionário: {name}',
            logActivityAddCategory: 'Adicionado nova categoria ({type}): {name}',
            logActivityUpdateCategory: 'Atualizado categoria ({type}): {name}',
            logActivityDeleteCategory: 'Excluído categoria ({type}): {name}',
            logActivityAddGame: 'Adicionado novo jogo: {name}',
            logActivityUpdateGame: 'Atualizado jogo: {name}',
            logActivityDeleteGame: 'Excluído jogo: {name}',
            logActivityGenerateReport: 'Gerado relatório do tipo: {type}',
            logActivityUpdatedProfile: 'Perfil do usuário atualizado.',
            logActivityUpdatedPassword: 'Senha do usuário alterada.',
            logActivityThemeChanged: 'Tema alterado para: {theme}',
            logActivityColorChanged: 'Cor primária alterada para: {color}',
            logActivityLanguageChanged: 'Idioma alterado para: {lang}',
            logActivityClearedCache: 'Cache local limpo.',
            logActivityExportedSettings: 'Configurações exportadas.',
            logActivityImportedSettings: 'Configurações importadas.',
            selectAvatarFile: 'Por favor, selecione um arquivo de imagem para o avatar.',
            logActivityUpdatedAvatar: 'Avatar do perfil atualizado.',
            confirmDelete: 'Tem certeza que deseja excluir este item?'
        },
        'en-US': {
            dashboard: 'Dashboard',
            users: 'Users',
            employees: 'Employees',
            categories: 'Categories',
            games: 'Games',
            reports: 'Reports',
            history: 'History',
            profile: 'Profile',
            settings: 'Settings',
            logout: 'Logout',
            totalUsers: 'Total Users',
            totalGames: 'Total Games',
            pendingRentals: 'Pending Rentals',
            totalSales: 'Total Sales',
            searchPlaceholder: 'Search...',
            allProfiles: 'All Profiles',
            admin: 'Admin',
            employee: 'Employee',
            client: 'Client',
            allStatuses: 'All Statuses',
            temporaryPassword: 'Temporary Password',
            permanentPassword: 'Permanent Password',
            addUser: 'Add User',
            userName: 'User Name',
            userEmail: 'Email',
            userProfile: 'Profile',
            userTemporaryPassword: 'Temporary Password',
            actions: 'Actions',
            edit: 'Edit',
            delete: 'Delete',
            addEditUser: 'Add/Edit User',
            save: 'Save',
            cancel: 'Cancel',
            allRoles: 'All Roles',
            addEmployee: 'Add Employee',
            employeeName: 'Name',
            employeeEmail: 'Email',
            employeeRole: 'Role',
            addEditEmployee: 'Add/Edit Employee',
            genre: 'Genre',
            theme: 'Theme',
            style: 'Style',
            platform: 'Platform',
            language: 'Language',
            mode: 'Mode',
            searchCategoryPlaceholder: 'Search category...',
            addCategory: 'Add Category',
            categoryName: 'Category Name',
            addEditCategory: 'Add/Edit Category',
            allAvailability: 'All',
            availableForRent: 'Available for Rent',
            notAvailableForRent: 'Not Available for Rent',
            addGame: 'Add Game',
            gameName: 'Game Name',
            releaseDate: 'Release Date',
            developer: 'Developer',
            gameCode: 'Code',
            addEditGame: 'Add/Edit Game',
            gameImage: 'Game Image (URL)',
            gameUrl: 'Game URL',
            reportsTitle: 'Generate Reports',
            reportType: 'Report Type',
            reportTypeUsers: 'Users',
            reportTypeEmployees: 'Employees',
            reportTypeGames: 'Games',
            reportTypePurchases: 'Purchases',
            reportTypePendingRentals: 'Pending Rentals',
            reportTypeGameHistory: 'Game History',
            reportTypeCustom: 'Custom Report (Free Text)',
            reportPeriodStart: 'Start Date',
            reportPeriodEnd: 'End Date',
            customReportText: 'Custom Report Content:',
            customReportFilename: 'Filename (no extension):',
            generateReport: 'Generate Report',
            downloadReport: 'Download Report',
            reportPreview: 'Report Preview:',
            searchHistoryPlaceholder: 'Search by user or game...',
            historyUser: 'User',
            historyGame: 'Game',
            entryTime: 'Entry Time',
            profileTitle: 'My Profile',
            changeAvatar: 'Change Avatar',
            personalInfo: 'Personal Information',
            profileName: 'Name:',
            profileEmail: 'Email:',
            profileRole: 'Role:',
            updateProfile: 'Update Profile',
            changePassword: 'Change Password',
            currentPassword: 'Current Password:',
            newPassword: 'New Password:',
            confirmNewPassword: 'Confirm New Password:',
            settingsTitle: 'Panel Settings',
            appearanceSettings: 'Appearance',
            themeMode: 'Theme Mode:',
            themeLight: 'Light',
            themeDark: 'Dark',
            primaryColor: 'Primary Color:',
            languageSettings: 'Language Settings',
            selectLanguage: 'Select Language:',
            dataUtilities: 'Data & Utilities',
            clearCache: 'Clear Local Cache',
            exportSettings: 'Export Settings',
            importSettings: 'Import Settings',
            activityLogs: 'Recent Activity Logs:',
            aboutApp: 'About Cypher Corporation',
            appVersion: 'Version:',
            lastUpdated: 'Last Updated:',
            helpDocs: 'Access Help Docs',
            logActivityLogin: 'User logged into admin panel.',
            logActivityLogout: 'User logged out of admin panel.',
            logActivityViewSection: 'Viewed section: {section}',
            logActivityAddUser: 'Added new user: {name}',
            logActivityUpdateUser: 'Updated user: {name}',
            logActivityDeleteUser: 'Deleted user: {name}',
            logActivityAddEmployee: 'Added new employee: {name}',
            logActivityUpdateEmployee: 'Updated employee: {name}',
            logActivityDeleteEmployee: 'Deleted employee: {name}',
            logActivityAddCategory: 'Added new category ({type}): {name}',
            logActivityUpdateCategory: 'Updated category ({type}): {name}',
            logActivityDeleteCategory: 'Deleted category ({type}): {name}',
            logActivityAddGame: 'Added new game: {name}',
            logActivityUpdateGame: 'Updated game: {name}',
            logActivityDeleteGame: 'Deleted game: {name}',
            logActivityGenerateReport: 'Generated report of type: {type}',
            logActivityUpdatedProfile: 'User profile updated.',
            logActivityUpdatedPassword: 'User password changed.',
            logActivityThemeChanged: 'Theme changed to: {theme}',
            logActivityColorChanged: 'Primary color changed to: {color}',
            logActivityLanguageChanged: 'Language changed to: {lang}',
            logActivityClearedCache: 'Local cache cleared.',
            logActivityExportedSettings: 'Settings exported.',
            logActivityImportedSettings: 'Settings imported.',
            selectAvatarFile: 'Please select an image file for the avatar.',
            logActivityUpdatedAvatar: 'Profile avatar updated.',
            confirmDelete: 'Are you sure you want to delete this item?'
        },
        'de-DE': {
            dashboard: 'Armaturenbrett',
            users: 'Benutzer',
            employees: 'Mitarbeiter',
            categories: 'Kategorien',
            games: 'Spiele',
            reports: 'Berichte',
            history: 'Verlauf',
            profile: 'Profil',
            settings: 'Einstellungen',
            logout: 'Abmelden',
            totalUsers: 'Benutzer insgesamt',
            totalGames: 'Spiele insgesamt',
            pendingRentals: 'Ausstehende Mieten',
            totalSales: 'Gesamtumsatz',
            searchPlaceholder: 'Suchen...',
            allProfiles: 'Alle Profile',
            admin: 'Administrator',
            employee: 'Mitarbeiter',
            client: 'Kunde',
            allStatuses: 'Alle Status',
            temporaryPassword: 'Temporäres Passwort',
            permanentPassword: 'Permanentes Passwort',
            addUser: 'Benutzer hinzufügen',
            userName: 'Benutzername',
            userEmail: 'E-Mail',
            userProfile: 'Profil',
            userTemporaryPassword: 'Temporäres Passwort',
            actions: 'Aktionen',
            edit: 'Bearbeiten',
            delete: 'Löschen',
            addEditUser: 'Benutzer hinzufügen/bearbeiten',
            save: 'Speichern',
            cancel: 'Abbrechen',
            allRoles: 'Alle Rollen',
            addEmployee: 'Mitarbeiter hinzufügen',
            employeeName: 'Name',
            employeeEmail: 'E-Mail',
            employeeRole: 'Rolle',
            addEditEmployee: 'Mitarbeiter hinzufügen/bearbeiten',
            genre: 'Genre',
            theme: 'Thema',
            style: 'Stil',
            platform: 'Plattform',
            language: 'Sprache',
            mode: 'Modus',
            searchCategoryPlaceholder: 'Kategorie suchen...',
            addCategory: 'Kategorie hinzufügen',
            categoryName: 'Kategoriename',
            addEditCategory: 'Kategorie hinzufügen/bearbeiten',
            allAvailability: 'Alle',
            availableForRent: 'Zur Miete verfügbar',
            notAvailableForRent: 'Nicht zur Miete verfügbar',
            addGame: 'Spiel hinzufügen',
            gameName: 'Spielname',
            releaseDate: 'Veröffentlichungsdatum',
            developer: 'Entwickler',
            gameCode: 'Code',
            addEditGame: 'Spiel hinzufügen/bearbeiten',
            gameImage: 'Spielbild (URL)',
            gameUrl: 'Spiel-URL',
            reportsTitle: 'Berichte generieren',
            reportType: 'Berichtstyp',
            reportTypeUsers: 'Benutzer',
            reportTypeEmployees: 'Mitarbeiter',
            reportTypeGames: 'Spiele',
            reportTypePurchases: 'Käufe',
            reportTypePendingRentals: 'Ausstehende Mieten',
            reportTypeGameHistory: 'Spielverlauf',
            reportTypeCustom: 'Benutzerdefinierter Bericht (Freitext)',
            reportPeriodStart: 'Startdatum',
            reportPeriodEnd: 'Enddatum',
            customReportText: 'Inhalt des benutzerdefinierten Berichts:',
            customReportFilename: 'Dateiname (ohne Erweiterung):',
            generateReport: 'Bericht generieren',
            downloadReport: 'Bericht herunterladen',
            reportPreview: 'Berichtsvorschau:',
            searchHistoryPlaceholder: 'Nach Benutzer oder Spiel suchen...',
            historyUser: 'Benutzer',
            historyGame: 'Spiel',
            entryTime: 'Eintragszeit',
            profileTitle: 'Mein Profil',
            changeAvatar: 'Avatar ändern',
            personalInfo: 'Persönliche Informationen',
            profileName: 'Name:',
            profileEmail: 'E-Mail:',
            profileRole: 'Rolle:',
            updateProfile: 'Profil aktualisieren',
            changePassword: 'Passwort ändern',
            currentPassword: 'Aktuelles Passwort:',
            newPassword: 'Neues Passwort:',
            confirmNewPassword: 'Neues Passwort bestätigen:',
            settingsTitle: 'Panel-Einstellungen',
            appearanceSettings: 'Erscheinungsbild',
            themeMode: 'Designmodus:',
            themeLight: 'Hell',
            themeDark: 'Dunkel',
            primaryColor: 'Primärfarbe:',
            languageSettings: 'Spracheinstellungen',
            selectLanguage: 'Sprache auswählen:',
            dataUtilities: 'Daten & Dienstprogramme',
            clearCache: 'Lokalen Cache leeren',
            exportSettings: 'Einstellungen exportieren',
            importSettings: 'Einstellungen importieren',
            activityLogs: 'Letzte Aktivitätsprotokolle:',
            aboutApp: 'Über Cypher Corporation',
            appVersion: 'Version:',
            lastUpdated: 'Zuletzt aktualisiert:',
            helpDocs: 'Hilfedokumente aufrufen',
            logActivityLogin: 'Benutzer hat sich im Admin-Panel angemeldet.',
            logActivityLogout: 'Benutzer hat sich vom Admin-Panel abgemeldet.',
            logActivityViewSection: 'Abschnitt angezeigt: {section}',
            logActivityAddUser: 'Neuen Benutzer hinzugefügt: {name}',
            logActivityUpdateUser: 'Benutzer aktualisiert: {name}',
            logActivityDeleteUser: 'Benutzer gelöscht: {name}',
            logActivityAddEmployee: 'Neuen Mitarbeiter hinzugefügt: {name}',
            logActivityUpdateEmployee: 'Mitarbeiter aktualisiert: {name}',
            logActivityDeleteEmployee: 'Mitarbeiter gelöscht: {name}',
            logActivityAddCategory: 'Neue Kategorie hinzugefügt ({type}): {name}',
            logActivityUpdateCategory: 'Kategorie aktualisiert ({type}): {name}',
            logActivityDeleteCategory: 'Kategorie gelöscht ({type}): {name}',
            logActivityAddGame: 'Neues Spiel hinzugefügt: {name}',
            logActivityUpdateGame: 'Spiel aktualisiert: {name}',
            logActivityDeleteGame: 'Spiel gelöscht: {name}',
            logActivityGenerateReport: 'Bericht vom Typ generiert: {type}',
            logActivityUpdatedProfile: 'Benutzerprofil aktualisiert.',
            logActivityUpdatedPassword: 'Benutzerpasswort geändert.',
            logActivityThemeChanged: 'Design geändert zu: {theme}',
            logActivityColorChanged: 'Primärfarbe geändert zu: {color}',
            logActivityLanguageChanged: 'Sprache geändert zu: {lang}',
            logActivityClearedCache: 'Lokaler Cache geleert.',
            logActivityExportedSettings: 'Einstellungen exportiert.',
            logActivityImportedSettings: 'Einstellungen importiert.',
            selectAvatarFile: 'Bitte wählen Sie eine Bilddatei für den Avatar aus.',
            logActivityUpdatedAvatar: 'Profil-Avatar aktualisiert.',
            confirmDelete: 'Möchten Sie dieses Element wirklich löschen?'
        },
    };

    // Função para aplicar traduções
    const applyTranslations = () => {
        document.querySelectorAll('[data-translate]').forEach(element => {
            const key = element.getAttribute('data-translate');
            if (translations[currentLanguage][key]) {
                element.textContent = translations[currentLanguage][key];
            }
        });
        document.querySelectorAll('[data-translate-placeholder]').forEach(element => {
            const key = element.getAttribute('data-translate-placeholder');
            if (translations[currentLanguage][key]) {
                element.placeholder = translations[currentLanguage][key];
            }
        });
        // Atualiza a seleção do idioma na sidebar e nas configurações
        document.getElementById('lang-select').value = currentLanguage;
        if (document.getElementById('language-select-settings')) {
            document.getElementById('language-select-settings').value = currentLanguage;
        }
    };

    // Troca de idioma
    document.getElementById('lang-select').addEventListener('change', (e) => {
        currentLanguage = e.target.value;
        localStorage.setItem('adminLang', currentLanguage);
        applyTranslations();
        logActivity(translations[currentLanguage]['logActivityLanguageChanged'].replace('{lang}', currentLanguage));
    });

    // Função para adicionar log de atividade
    const logActivity = (message) => {
        let logs = JSON.parse(localStorage.getItem('activityLogs')) || [];
        const timestamp = new Date().toLocaleString(currentLanguage);
        logs.unshift(`[${timestamp}] ${message}`); // Adiciona no início
        if (logs.length > 50) { // Limita o número de logs
            logs = logs.slice(0, 50);
        }
        localStorage.setItem('activityLogs', JSON.stringify(logs));
        renderActivityLogs();
    };

    // Renderiza os logs de atividade na seção de configurações
    const renderActivityLogs = () => {
        const logDisplay = document.getElementById('activity-log-display');
        if (logDisplay) {
            let logs = JSON.parse(localStorage.getItem('activityLogs')) || [];
            logDisplay.textContent = logs.join('\n');
        }
    };


    // --- Navegação da Sidebar ---
    const showSection = (sectionId) => {
        contentSections.forEach(section => {
            section.classList.remove('active');
        });
        const activeSection = document.getElementById(sectionId);
        if (activeSection) {
            activeSection.classList.add('active');
            logActivity(translations[currentLanguage]['logActivityViewSection'].replace('{section}', sectionId));
        }

        // Atualizar o estado 'active' nos links da sidebar
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('data-section') === sectionId) {
                link.classList.add('active');
            }
        });

        // Fechar sidebar em mobile após clique
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('active');
            sidebarOverlay.style.display = 'none';
        }

        // Renderizar conteúdo específico da seção
        switch (sectionId) {
            case 'dashboard':
                renderDashboard();
                break;
            case 'users-management':
                renderUsersTable();
                break;
            case 'employees-management':
                renderEmployeesTable();
                populateEmployeeRoleFilter();
                break;
            case 'categories-management':
                renderCategoriesTable();
                break;
            case 'games-management':
                renderGamesTable();
                break;
            case 'reports-management':
                resetReportSection();
                break;
            case 'history-management':
                renderGameHistoryTable();
                break;
            case 'profile-settings':
                loadProfileData();
                break;
            case 'settings-management':
                loadSettings();
                break;
            case 'logout':
                // Implementar lógica de logout
                logActivity(translations[currentLanguage]['logActivityLogout']);
                alert('Você foi desconectado.'); // Substituir por redirecionamento real
                break;
        }
    };

    // Adicionar event listeners para os links da sidebar
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const sectionId = link.getAttribute('data-section');
            showSection(sectionId);
        });
    });

    // Toggle da Sidebar (Desktop)
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('collapsed');
    });

    // Toggle da Sidebar (Mobile)
    mobileSidebarToggle.addEventListener('click', () => {
        sidebar.classList.add('active');
        sidebarOverlay.style.display = 'block';
    });

    // Fechar sidebar ao clicar no overlay em mobile
    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        sidebarOverlay.style.display = 'none';
    });


    // --- Funções Auxiliares para Modais ---
    const openModal = (title, contentHtml, onSaveCallback = null) => {
        modalTitle.textContent = title;
        modalBody.innerHTML = contentHtml;
        modalBackdrop.style.display = 'flex';

        // Lidar com o callback de salvar
        const saveBtn = modalBody.querySelector('.modal-save-btn');
        if (saveBtn && onSaveCallback) {
            saveBtn.onclick = onSaveCallback;
        }

        // Lidar com o botão de fechar
        modalBody.querySelector('.modal-cancel-btn').onclick = closeModal;
    };

    const closeModal = () => {
        modalBackdrop.style.display = 'none';
        modalTitle.textContent = '';
        modalBody.innerHTML = '';
    };

    // --- DASHBOARD ---
    const renderDashboard = () => {
        document.getElementById('total-users-stat').textContent = db.usuarios.length;
        document.getElementById('total-games-stat').textContent = db.jogos.length;
        document.getElementById('pending-rentals-stat').textContent = db.locacoes_pendentes.filter(l => l.status === 'pendente').length;
        document.getElementById('total-sales-stat').textContent = db.compras.length; // Contagem simples de compras

        renderUserProfileChart();
        renderGameReleaseChart();
    };

    const renderUserProfileChart = () => {
        if (userProfileChartInstance) {
            userProfileChartInstance.destroy();
        }

        const profileCounts = {
            'adm': db.adms.length,
            'funcionario': db.funcionarios.length,
            'cliente': db.usuarios.length
        };

        const ctx = document.getElementById('userProfileChart').getContext('2d');
        userProfileChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(profileCounts).map(key => translations[currentLanguage][key]),
                datasets: [{
                    data: Object.values(profileCounts),
                    backgroundColor: [
                        'rgba(111, 66, 193, 0.8)', // Primary color
                        'rgba(0, 123, 255, 0.8)', // Secondary color
                        'rgba(40, 167, 69, 0.8)'  // Success color
                    ],
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: var_to_rgb('--text-light')
                        }
                    },
                    title: {
                        display: true,
                        text: translations[currentLanguage]['userProfile'],
                        color: var_to_rgb('--text-light')
                    }
                }
            }
        });
    };

    const renderGameReleaseChart = () => {
        if (gameReleaseChartInstance) {
            gameReleaseChartInstance.destroy();
        }

        // Agrupa jogos por ano de lançamento
        const gameReleaseYears = {};
        db.jogos.forEach(game => {
            const year = new Date(game.data_lanc).getFullYear();
            gameReleaseYears[year] = (gameReleaseYears[year] || 0) + 1;
        });

        // Ordena por ano
        const sortedYears = Object.keys(gameReleaseYears).sort();
        const data = sortedYears.map(year => gameReleaseYears[year]);

        const ctx = document.getElementById('gameReleaseChart').getContext('2d');
        gameReleaseChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: sortedYears,
                datasets: [{
                    label: translations[currentLanguage]['totalGames'],
                    data: data,
                    backgroundColor: 'rgba(0, 123, 255, 0.7)', // Secondary color
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: translations[currentLanguage]['releaseDate'],
                        color: var_to_rgb('--text-light')
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: var_to_rgb('--text-muted')
                        },
                        grid: {
                            color: var_to_rgb('--border-color')
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: var_to_rgb('--text-muted'),
                            precision: 0
                        },
                        grid: {
                            color: var_to_rgb('--border-color')
                        }
                    }
                }
            }
        });
    };

    // Helper para converter cor CSS var para RGB para Chart.js
    function var_to_rgb(cssVar) {
        const style = getComputedStyle(document.documentElement);
        const color = style.getPropertyValue(cssVar).trim();
        // Remove "rgba(a, b, c, d)" ou "rgb(a, b, c)" e deixa apenas "a, b, c"
        const matches = color.match(/\d+,\s*\d+,\s*\d+/);
        return matches ? `rgb(${matches[0]})` : color; // Retorna rgb(a,b,c) ou a cor original se não for RGB/RGBA
    }


    // --- GERENCIAMENTO DE USUÁRIOS ---
    const userTableBody = document.getElementById('user-table-body');
    const userSearchInput = document.getElementById('user-search');
    const userProfileFilter = document.getElementById('user-profile-filter');
    const userStatusFilter = document.getElementById('user-status-filter');
    const addUserBtn = document.getElementById('add-user-btn');

    const renderUsersTable = () => {
        const searchTerm = userSearchInput.value.toLowerCase();
        const profileFilter = userProfileFilter.value;
        const statusFilter = userStatusFilter.value; // 'true', 'false', 'all'

        let filteredUsers = db.usuarios.filter(user => {
            const matchesSearch = user.nome_user.toLowerCase().includes(searchTerm) || user.email_user.toLowerCase().includes(searchTerm);
            const matchesProfile = profileFilter === 'all' || user.perfil === profileFilter;
            const matchesStatus = statusFilter === 'all' || String(user.senha_temporaria) === statusFilter;
            return matchesSearch && matchesProfile && matchesStatus;
        });

        userTableBody.innerHTML = '';
        if (filteredUsers.length === 0) {
            userTableBody.innerHTML = `<tr><td colspan="6" style="text-align: center;">${translations[currentLanguage]['noUsersFound']}</td></tr>`;
            return;
        }

        filteredUsers.forEach(user => {
            const row = userTableBody.insertRow();
            row.insertCell(0).textContent = user.pk_usuario;
            row.insertCell(1).textContent = user.nome_user;
            row.insertCell(2).textContent = user.email_user;
            row.insertCell(3).textContent = translations[currentLanguage][user.perfil] || user.perfil;
            const statusCell = row.insertCell(4);
            statusCell.innerHTML = `<span class="status-badge ${user.senha_temporaria}">${user.senha_temporaria ? translations[currentLanguage]['temporaryPassword'] : translations[currentLanguage]['permanentPassword']}</span>`;

            const actionsCell = row.insertCell(5);
            actionsCell.innerHTML = `
                <button class="btn btn-sm btn-info edit-user-btn" data-id="${user.pk_usuario}">${translations[currentLanguage]['edit']}</button>
                <button class="btn btn-sm btn-danger delete-user-btn" data-id="${user.pk_usuario}">${translations[currentLanguage]['delete']}</button>
            `;
        });

        addUsersEventListeners();
    };

    const addUsersEventListeners = () => {
        document.querySelectorAll('.edit-user-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const userId = parseInt(e.target.dataset.id);
                const user = db.usuarios.find(u => u.pk_usuario === userId);
                if (user) {
                    openUserModal(user);
                }
            });
        });

        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const userId = parseInt(e.target.dataset.id);
                if (confirm(translations[currentLanguage]['confirmDelete'])) {
                    deleteUser(userId);
                }
            });
        });
    };

    addUserBtn.addEventListener('click', () => openUserModal());
    userSearchInput.addEventListener('input', renderUsersTable);
    userProfileFilter.addEventListener('change', renderUsersTable);
    userStatusFilter.addEventListener('change', renderUsersTable);

    const openUserModal = (user = null) => {
        const isEdit = user !== null;
        const title = isEdit ? translations[currentLanguage]['addEditUser'] : translations[currentLanguage]['addEditUser'];
        const formHtml = `
            <form id="user-form">
                <div class="form-group">
                    <label for="userNameInput">${translations[currentLanguage]['userName']}</label>
                    <input type="text" id="userNameInput" value="${user ? user.nome_user : ''}" required>
                </div>
                <div class="form-group">
                    <label for="userEmailInput">${translations[currentLanguage]['userEmail']}</label>
                    <input type="email" id="userEmailInput" value="${user ? user.email_user : ''}" required>
                </div>
                <div class="form-group">
                    <label for="userPasswordInput">${translations[currentLanguage]['newPassword']}</label>
                    <input type="password" id="userPasswordInput" value="${user ? user.senha_user : ''}" ${isEdit ? '' : 'required'}>
                    ${isEdit ? `<small>${translations[currentLanguage]['leaveBlankForNoChange'] || 'Deixe em branco para não alterar a senha'}</small>` : ''}
                </div>
                <div class="form-group">
                    <label for="userProfileSelect">${translations[currentLanguage]['userProfile']}</label>
                    <select id="userProfileSelect" required>
                        <option value="adm" ${user && user.perfil === 'adm' ? 'selected' : ''}>${translations[currentLanguage]['admin']}</option>
                        <option value="funcionario" ${user && user.perfil === 'funcionario' ? 'selected' : ''}>${translations[currentLanguage]['employee']}</option>
                        <option value="cliente" ${user && user.perfil === 'cliente' ? 'selected' : ''}>${translations[currentLanguage]['client']}</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="checkbox" id="userTempPassCheckbox" ${user && user.senha_temporaria ? 'checked' : ''}>
                    <label for="userTempPassCheckbox">${translations[currentLanguage]['temporaryPassword']}</label>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary modal-save-btn">${translations[currentLanguage]['save']}</button>
                    <button type="button" class="btn btn-secondary modal-cancel-btn">${translations[currentLanguage]['cancel']}</button>
                </div>
            </form>
        `;

        openModal(title, formHtml, () => {
            const name = document.getElementById('userNameInput').value;
            const email = document.getElementById('userEmailInput').value;
            const password = document.getElementById('userPasswordInput').value;
            const profile = document.getElementById('userProfileSelect').value;
            const tempPass = document.getElementById('userTempPassCheckbox').checked;

            if (isEdit) {
                user.nome_user = name;
                user.email_user = email;
                if (password) user.senha_user = password; // Só atualiza se a senha for fornecida
                user.perfil = profile;
                user.senha_temporaria = tempPass;
                logActivity(translations[currentLanguage]['logActivityUpdateUser'].replace('{name}', user.nome_user));
            } else {
                const newUser = {
                    pk_usuario: generateId(db.usuarios),
                    nome_user: name,
                    email_user: email,
                    senha_user: password,
                    data_criacao: new Date().toISOString().split('T')[0],
                    senha_temporaria: tempPass,
                    foto_perfil: '',
                    perfil: profile
                };
                db.usuarios.push(newUser);
                logActivity(translations[currentLanguage]['logActivityAddUser'].replace('{name}', newUser.nome_user));
            }
            saveData();
            renderUsersTable();
            closeModal();
        });
    };

    const deleteUser = (userId) => {
        const userIndex = db.usuarios.findIndex(u => u.pk_usuario === userId);
        if (userIndex > -1) {
            const userName = db.usuarios[userIndex].nome_user;
            db.usuarios.splice(userIndex, 1);
            saveData();
            renderUsersTable();
            logActivity(translations[currentLanguage]['logActivityDeleteUser'].replace('{name}', userName));
        }
    };


    // --- GERENCIAMENTO DE FUNCIONÁRIOS ---
    const employeeTableBody = document.getElementById('employee-table-body');
    const employeeSearchInput = document.getElementById('employee-search');
    const employeeRoleFilter = document.getElementById('employee-role-filter');
    const addEmployeeBtn = document.getElementById('add-employee-btn');

    // Popula o filtro de cargos com base nos dados do banco
    const populateEmployeeRoleFilter = () => {
        employeeRoleFilter.innerHTML = `<option value="all">${translations[currentLanguage]['allRoles']}</option>`;
        db.cargos.forEach(cargo => {
            const option = document.createElement('option');
            option.value = cargo.pk_cargo;
            option.textContent = cargo.nome_cargo;
            employeeRoleFilter.appendChild(option);
        });
    };

    const renderEmployeesTable = () => {
        const searchTerm = employeeSearchInput.value.toLowerCase();
        const roleFilter = parseInt(employeeRoleFilter.value);

        // Combina ADMs e Funcionários para exibir como 'funcionários' no painel
        let combinedEmployees = [
            ...db.adms.map(adm => ({ ...adm, is_adm: true, id: adm.pk_adm, name: adm.nome_adm, email: adm.email_adm, fk_cargo: adm.fk_cargo })),
            ...db.funcionarios.map(func => ({ ...func, is_adm: false, id: func.pk_funcionario, name: func.nome_func, email: func.email_func, fk_cargo: func.fk_cargo }))
        ];

        let filteredEmployees = combinedEmployees.filter(emp => {
            const matchesSearch = emp.name.toLowerCase().includes(searchTerm) || emp.email.toLowerCase().includes(searchTerm);
            const matchesRole = roleFilter === 'all' || emp.fk_cargo === roleFilter;
            return matchesSearch && matchesRole;
        });

        employeeTableBody.innerHTML = '';
        if (filteredEmployees.length === 0) {
            employeeTableBody.innerHTML = `<tr><td colspan="5" style="text-align: center;">${translations[currentLanguage]['noEmployeesFound'] || 'Nenhum funcionário encontrado.'}</td></tr>`;
            return;
        }

        filteredEmployees.forEach(emp => {
            const cargo = db.cargos.find(c => c.pk_cargo === emp.fk_cargo);
            const cargoNome = cargo ? cargo.nome_cargo : 'N/A';

            const row = employeeTableBody.insertRow();
            row.insertCell(0).textContent = emp.id;
            row.insertCell(1).textContent = emp.name;
            row.insertCell(2).textContent = emp.email;
            row.insertCell(3).textContent = cargoNome;

            const actionsCell = row.insertCell(4);
            actionsCell.innerHTML = `
                <button class="btn btn-sm btn-info edit-employee-btn" data-id="${emp.id}" data-type="${emp.is_adm ? 'adm' : 'funcionario'}">${translations[currentLanguage]['edit']}</button>
                <button class="btn btn-sm btn-danger delete-employee-btn" data-id="${emp.id}" data-type="${emp.is_adm ? 'adm' : 'funcionario'}">${translations[currentLanguage]['delete']}</button>
            `;
        });

        addEmployeesEventListeners();
    };

    const addEmployeesEventListeners = () => {
        document.querySelectorAll('.edit-employee-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const empId = parseInt(e.target.dataset.id);
                const empType = e.target.dataset.type;
                let employee;
                if (empType === 'adm') {
                    employee = db.adms.find(a => a.pk_adm === empId);
                } else {
                    employee = db.funcionarios.find(f => f.pk_funcionario === empId);
                }
                if (employee) {
                    openEmployeeModal(employee, empType);
                }
            });
        });

        document.querySelectorAll('.delete-employee-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const empId = parseInt(e.target.dataset.id);
                const empType = e.target.dataset.type;
                if (confirm(translations[currentLanguage]['confirmDelete'])) {
                    deleteEmployee(empId, empType);
                }
            });
        });
    };

    addEmployeeBtn.addEventListener('click', () => openEmployeeModal(null, 'funcionario')); // Padrão para adicionar funcionário
    employeeSearchInput.addEventListener('input', renderEmployeesTable);
    employeeRoleFilter.addEventListener('change', renderEmployeesTable);

    const openEmployeeModal = (employee = null, type = 'funcionario') => {
        const isEdit = employee !== null;
        const title = isEdit ? translations[currentLanguage]['addEditEmployee'] : translations[currentLanguage]['addEditEmployee'];

        let currentName = isEdit ? (type === 'adm' ? employee.nome_adm : employee.nome_func) : '';
        let currentEmail = isEdit ? (type === 'adm' ? employee.email_adm : employee.email_func) : '';
        let currentPassword = isEdit ? (type === 'adm' ? employee.senha_user : employee.senha_func) : '';
        let currentCargo = isEdit ? employee.fk_cargo : '';

        const cargoOptions = db.cargos.map(cargo => `<option value="${cargo.pk_cargo}" ${currentCargo === cargo.pk_cargo ? 'selected' : ''}>${cargo.nome_cargo}</option>`).join('');

        const formHtml = `
            <form id="employee-form">
                <div class="form-group">
                    <label for="employeeNameInput">${translations[currentLanguage]['employeeName']}</label>
                    <input type="text" id="employeeNameInput" value="${currentName}" required>
                </div>
                <div class="form-group">
                    <label for="employeeEmailInput">${translations[currentLanguage]['employeeEmail']}</label>
                    <input type="email" id="employeeEmailInput" value="${currentEmail}" required>
                </div>
                <div class="form-group">
                    <label for="employeePasswordInput">${translations[currentLanguage]['newPassword']}</label>
                    <input type="password" id="employeePasswordInput" value="${currentPassword}" ${isEdit ? '' : 'required'}>
                    ${isEdit ? `<small>${translations[currentLanguage]['leaveBlankForNoChange'] || 'Deixe em branco para não alterar a senha'}</small>` : ''}
                </div>
                <div class="form-group">
                    <label for="employeeCargoSelect">${translations[currentLanguage]['employeeRole']}</label>
                    <select id="employeeCargoSelect" required>
                        ${cargoOptions}
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary modal-save-btn">${translations[currentLanguage]['save']}</button>
                    <button type="button" class="btn btn-secondary modal-cancel-btn">${translations[currentLanguage]['cancel']}</button>
                </div>
            </form>
        `;

        openModal(title, formHtml, () => {
            const name = document.getElementById('employeeNameInput').value;
            const email = document.getElementById('employeeEmailInput').value;
            const password = document.getElementById('employeePasswordInput').value;
            const cargoId = parseInt(document.getElementById('employeeCargoSelect').value);

            if (isEdit) {
                if (type === 'adm') {
                    employee.nome_adm = name;
                    employee.email_adm = email;
                    if (password) employee.senha_user = password;
                    employee.fk_cargo = cargoId;
                    logActivity(translations[currentLanguage]['logActivityUpdateEmployee'].replace('{name}', employee.nome_adm));
                } else {
                    employee.nome_func = name;
                    employee.email_func = email;
                    if (password) employee.senha_func = password;
                    employee.fk_cargo = cargoId;
                    logActivity(translations[currentLanguage]['logActivityUpdateEmployee'].replace('{name}', employee.nome_func));
                }
            } else {
                // Determine if it's an admin or regular employee based on cargoId
                const newEmployee = {
                    fk_cargo: cargoId
                };
                if (db.cargos.find(c => c.pk_cargo === cargoId && c.nome_cargo.toLowerCase().includes('administrador'))) {
                    // This is an admin
                    newEmployee.pk_adm = generateId(db.adms);
                    newEmployee.nome_adm = name;
                    newEmployee.email_adm = email;
                    newEmployee.senha_user = password;
                    db.adms.push(newEmployee);
                } else {
                    // This is a regular employee (funcionario)
                    newEmployee.pk_funcionario = generateId(db.funcionarios);
                    newEmployee.nome_func = name;
                    newEmployee.email_func = email;
                    newEmployee.senha_func = password;
                    db.funcionarios.push(newEmployee);
                }
                logActivity(translations[currentLanguage]['logActivityAddEmployee'].replace('{name}', name));
            }
            saveData();
            renderEmployeesTable();
            closeModal();
        });
    };

    const deleteEmployee = (empId, type) => {
        if (type === 'adm') {
            const admIndex = db.adms.findIndex(a => a.pk_adm === empId);
            if (admIndex > -1) {
                const admName = db.adms[admIndex].nome_adm;
                db.adms.splice(admIndex, 1);
                saveData();
                renderEmployeesTable();
                logActivity(translations[currentLanguage]['logActivityDeleteEmployee'].replace('{name}', admName));
            }
        } else {
            const funcIndex = db.funcionarios.findIndex(f => f.pk_funcionario === empId);
            if (funcIndex > -1) {
                const funcName = db.funcionarios[funcIndex].nome_func;
                db.funcionarios.splice(funcIndex, 1);
                saveData();
                renderEmployeesTable();
                logActivity(translations[currentLanguage]['logActivityDeleteEmployee'].replace('{name}', funcName));
            }
        }
    };


    // --- GERENCIAMENTO DE CATEGORIAS ---
    const categoryTableBody = document.getElementById('category-table-body');
    const categoryTypeSelect = document.getElementById('category-type-select');
    const categorySearchInput = document.getElementById('category-search');
    const addCategoryBtn = document.getElementById('add-category-btn');

    let currentCategoryType = categoryTypeSelect.value; // 'genero' por padrão

    const getCategoryData = (type) => {
        switch (type) {
            case 'genero': return { data: db.generos, pk_field: 'pk_genero', name_field: 'nome_gen' };
            case 'tema': return { data: db.temas, pk_field: 'pk_tema', name_field: 'nome_tema' };
            case 'estilo': return { data: db.estilos, pk_field: 'pk_estilo', name_field: 'nome_estilo' };
            case 'plataforma': return { data: db.plataformas, pk_field: 'pk_plataforma', name_field: 'nome_plat' };
            case 'idioma': return { data: db.idiomas, pk_field: 'pk_idioma', name_field: 'nome_idioma' };
            case 'modo': return { data: db.modos, pk_field: 'pk_modo', name_field: 'nome_modo' };
            default: return { data: [], pk_field: '', name_field: '' };
        }
    };

    const renderCategoriesTable = () => {
        currentCategoryType = categoryTypeSelect.value;
        const { data, pk_field, name_field } = getCategoryData(currentCategoryType);
        const searchTerm = categorySearchInput.value.toLowerCase();

        let filteredCategories = data.filter(cat =>
            cat[name_field].toLowerCase().includes(searchTerm)
        );

        categoryTableBody.innerHTML = '';
        if (filteredCategories.length === 0) {
            categoryTableBody.innerHTML = `<tr><td colspan="3" style="text-align: center;">${translations[currentLanguage]['noCategoriesFound'] || 'Nenhuma categoria encontrada.'}</td></tr>`;
            return;
        }

        filteredCategories.forEach(cat => {
            const row = categoryTableBody.insertRow();
            row.insertCell(0).textContent = cat[pk_field];
            row.insertCell(1).textContent = cat[name_field];
            const actionsCell = row.insertCell(2);
            actionsCell.innerHTML = `
                <button class="btn btn-sm btn-info edit-category-btn" data-id="${cat[pk_field]}" data-type="${currentCategoryType}">${translations[currentLanguage]['edit']}</button>
                <button class="btn btn-sm btn-danger delete-category-btn" data-id="${cat[pk_field]}" data-type="${currentCategoryType}">${translations[currentLanguage]['delete']}</button>
            `;
        });

        addCategoryEventListeners();
    };

    const addCategoryEventListeners = () => {
        document.querySelectorAll('.edit-category-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const catId = parseInt(e.target.dataset.id);
                const catType = e.target.dataset.type;
                const { data, pk_field, name_field } = getCategoryData(catType);
                const category = data.find(c => c[pk_field] === catId);
                if (category) {
                    openCategoryModal(category, catType);
                }
            });
        });

        document.querySelectorAll('.delete-category-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const catId = parseInt(e.target.dataset.id);
                const catType = e.target.dataset.type;
                if (confirm(translations[currentLanguage]['confirmDelete'])) {
                    deleteCategory(catId, catType);
                }
            });
        });
    };

    categoryTypeSelect.addEventListener('change', renderCategoriesTable);
    categorySearchInput.addEventListener('input', renderCategoriesTable);
    addCategoryBtn.addEventListener('click', () => openCategoryModal(null, currentCategoryType));

    const openCategoryModal = (category = null, type) => {
        const isEdit = category !== null;
        const title = isEdit ? translations[currentLanguage]['addEditCategory'] : translations[currentLanguage]['addEditCategory'];
        const { pk_field, name_field } = getCategoryData(type);

        const formHtml = `
            <form id="category-form">
                <div class="form-group">
                    <label for="categoryNameInput">${translations[currentLanguage]['categoryName']}</label>
                    <input type="text" id="categoryNameInput" value="${category ? category[name_field] : ''}" required>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary modal-save-btn">${translations[currentLanguage]['save']}</button>
                    <button type="button" class="btn btn-secondary modal-cancel-btn">${translations[currentLanguage]['cancel']}</button>
                </div>
            </form>
        `;

        openModal(title, formHtml, () => {
            const name = document.getElementById('categoryNameInput').value;
            const { data, pk_field, name_field } = getCategoryData(type);

            if (isEdit) {
                category[name_field] = name;
                logActivity(translations[currentLanguage]['logActivityUpdateCategory'].replace('{type}', type).replace('{name}', name));
            } else {
                const newCat = {};
                newCat[pk_field] = generateId(data);
                newCat[name_field] = name;
                data.push(newCat);
                logActivity(translations[currentLanguage]['logActivityAddCategory'].replace('{type}', type).replace('{name}', name));
            }
            saveData();
            renderCategoriesTable();
            closeModal();
        });
    };

    const deleteCategory = (catId, type) => {
        const { data, pk_field, name_field } = getCategoryData(type);
        const categoryIndex = data.findIndex(c => c[pk_field] === catId);
        if (categoryIndex > -1) {
            const categoryName = data[categoryIndex][name_field];
            data.splice(categoryIndex, 1);
            saveData();
            renderCategoriesTable();
            logActivity(translations[currentLanguage]['logActivityDeleteCategory'].replace('{type}', type).replace('{name}', categoryName));
        }
    };


    // --- GERENCIAMENTO DE JOGOS ---
    const gameTableBody = document.getElementById('game-table-body');
    const gameSearchInput = document.getElementById('game-search');
    const gameAvailabilityFilter = document.getElementById('game-availability-filter');
    const addGameBtn = document.getElementById('add-game-btn');

    const renderGamesTable = () => {
        const searchTerm = gameSearchInput.value.toLowerCase();
        const availabilityFilter = gameAvailabilityFilter.value; // 'true', 'false', 'all'

        let filteredGames = db.jogos.filter(game => {
            const matchesSearch = game.nome_jogo.toLowerCase().includes(searchTerm) || game.desenvolvedora.toLowerCase().includes(searchTerm);
            const matchesAvailability = availabilityFilter === 'all' || String(game.disponivel_locacao) === availabilityFilter;
            return matchesSearch && matchesAvailability;
        });

        gameTableBody.innerHTML = '';
        if (filteredGames.length === 0) {
            gameTableBody.innerHTML = `<tr><td colspan="7" style="text-align: center;">${translations[currentLanguage]['noGamesFound'] || 'Nenhum jogo encontrado.'}</td></tr>`;
            return;
        }

        filteredGames.forEach(game => {
            const codigoGame = db.codigo_games.find(c => c.pk_codgame === game.fk_codigo);
            const codigo = codigoGame ? codigoGame.codigo : 'N/A';

            const row = gameTableBody.insertRow();
            row.insertCell(0).textContent = game.pk_jogo;
            row.insertCell(1).textContent = game.nome_jogo;
            row.insertCell(2).textContent = game.data_lanc;
            row.insertCell(3).textContent = game.desenvolvedora;
            row.insertCell(4).textContent = codigo;
            const availabilityCell = row.insertCell(5);
            availabilityCell.innerHTML = `<span class="status-badge ${game.disponivel_locacao ? 'available' : 'not-available'}">${game.disponivel_locacao ? translations[currentLanguage]['availableForRent'] : translations[currentLanguage]['notAvailableForRent']}</span>`;

            const actionsCell = row.insertCell(6);
            actionsCell.innerHTML = `
                <button class="btn btn-sm btn-info edit-game-btn" data-id="${game.pk_jogo}">${translations[currentLanguage]['edit']}</button>
                <button class="btn btn-sm btn-danger delete-game-btn" data-id="${game.pk_jogo}">${translations[currentLanguage]['delete']}</button>
            `;
        });

        addGamesEventListeners();
    };

    const addGamesEventListeners = () => {
        document.querySelectorAll('.edit-game-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const gameId = parseInt(e.target.dataset.id);
                const game = db.jogos.find(g => g.pk_jogo === gameId);
                if (game) {
                    openGameModal(game);
                }
            });
        });

        document.querySelectorAll('.delete-game-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const gameId = parseInt(e.target.dataset.id);
                if (confirm(translations[currentLanguage]['confirmDelete'])) {
                    deleteGame(gameId);
                }
            });
        });
    };

    addGameBtn.addEventListener('click', () => openGameModal());
    gameSearchInput.addEventListener('input', renderGamesTable);
    gameAvailabilityFilter.addEventListener('change', renderGamesTable);

    const openGameModal = (game = null) => {
        const isEdit = game !== null;
        const title = isEdit ? translations[currentLanguage]['addEditGame'] : translations[currentLanguage]['addEditGame'];

        // Crie opções para os códigos de jogo disponíveis
        const availableCodes = db.codigo_games.map(code => `<option value="${code.pk_codgame}" ${game && game.fk_codigo === code.pk_codgame ? 'selected' : ''}>${code.codigo}</option>`).join('');

        const formHtml = `
            <form id="game-form">
                <div class="form-group">
                    <label for="gameNameInput">${translations[currentLanguage]['gameName']}</label>
                    <input type="text" id="gameNameInput" value="${game ? game.nome_jogo : ''}" required>
                </div>
                <div class="form-group">
                    <label for="gameReleaseDateInput">${translations[currentLanguage]['releaseDate']}</label>
                    <input type="date" id="gameReleaseDateInput" value="${game ? game.data_lanc : ''}" required>
                </div>
                <div class="form-group">
                    <label for="gameDeveloperInput">${translations[currentLanguage]['developer']}</label>
                    <input type="text" id="gameDeveloperInput" value="${game ? game.desenvolvedora : ''}">
                </div>
                <div class="form-group">
                    <label for="gameCodeSelect">${translations[currentLanguage]['gameCode']}</label>
                    <select id="gameCodeSelect" required>
                        ${availableCodes}
                    </select>
                </div>
                 <div class="form-group">
                    <label for="gameImageUrlInput">${translations[currentLanguage]['gameImage']}</label>
                    <input type="text" id="gameImageUrlInput" value="${game ? game.imagem_jogo : ''}" placeholder="URL da imagem (opcional)">
                </div>
                <div class="form-group">
                    <label for="gameUrlInput">${translations[currentLanguage]['gameUrl']}</label>
                    <input type="text" id="gameUrlInput" value="${game ? game.url_jogo : ''}" placeholder="URL do jogo (opcional)">
                </div>
                <div class="form-group">
                    <input type="checkbox" id="gameAvailableCheckbox" ${game && game.disponivel_locacao ? 'checked' : ''}>
                    <label for="gameAvailableCheckbox">${translations[currentLanguage]['availableForRent']}</label>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary modal-save-btn">${translations[currentLanguage]['save']}</button>
                    <button type="button" class="btn btn-secondary modal-cancel-btn">${translations[currentLanguage]['cancel']}</button>
                </div>
            </form>
        `;

        openModal(title, formHtml, () => {
            const name = document.getElementById('gameNameInput').value;
            const releaseDate = document.getElementById('gameReleaseDateInput').value;
            const developer = document.getElementById('gameDeveloperInput').value;
            const fkCode = parseInt(document.getElementById('gameCodeSelect').value);
            const imageUrl = document.getElementById('gameImageUrlInput').value;
            const gameUrl = document.getElementById('gameUrlInput').value;
            const available = document.getElementById('gameAvailableCheckbox').checked;

            if (isEdit) {
                game.nome_jogo = name;
                game.data_lanc = releaseDate;
                game.desenvolvedora = developer;
                game.fk_codigo = fkCode;
                game.imagem_jogo = imageUrl;
                game.url_jogo = gameUrl;
                game.disponivel_locacao = available;
                logActivity(translations[currentLanguage]['logActivityUpdateGame'].replace('{name}', game.nome_jogo));
            } else {
                const newGame = {
                    pk_jogo: generateId(db.jogos),
                    nome_jogo: name,
                    data_lanc: releaseDate,
                    fk_codigo: fkCode,
                    desenvolvedora: developer,
                    disponivel_locacao: available,
                    imagem_jogo: imageUrl,
                    url_jogo: gameUrl
                };
                db.jogos.push(newGame);
                logActivity(translations[currentLanguage]['logActivityAddGame'].replace('{name}', newGame.nome_jogo));
            }
            saveData();
            renderGamesTable();
            closeModal();
        });
    };

    const deleteGame = (gameId) => {
        const gameIndex = db.jogos.findIndex(g => g.pk_jogo === gameId);
        if (gameIndex > -1) {
            const gameName = db.jogos[gameIndex].nome_jogo;
            db.jogos.splice(gameIndex, 1);
            saveData();
            renderGamesTable();
            logActivity(translations[currentLanguage]['logActivityDeleteGame'].replace('{name}', gameName));
        }
    };


    // --- RELATÓRIOS ---
    const reportTypeSelect = document.getElementById('report-type');
    const dataReportFields = document.getElementById('data-report-fields');
    const customReportFields = document.getElementById('custom-report-fields');
    const generateReportBtn = document.getElementById('generate-report-btn');
    const downloadReportBtn = document.getElementById('download-report-btn');
    const reportPreview = document.getElementById('report-preview');
    const reportDataDisplay = document.getElementById('report-data');
    const reportPeriodStart = document.getElementById('report-period-start');
    const reportPeriodEnd = document.getElementById('report-period-end');
    const customReportText = document.getElementById('custom-report-text');
    const customReportFilename = document.getElementById('custom-report-filename');

    const resetReportSection = () => {
        reportTypeSelect.value = 'users'; // Reset para o padrão
        reportTypeSelect.dispatchEvent(new Event('change')); // Dispara change para atualizar campos
        reportPreview.style.display = 'none';
        downloadReportBtn.style.display = 'none';
        reportDataDisplay.textContent = '';
        reportPeriodStart.value = '';
        reportPeriodEnd.value = '';
        customReportText.value = '';
        customReportFilename.value = 'relatorio_personalizado';
    };

    reportTypeSelect.addEventListener('change', () => {
        const selectedType = reportTypeSelect.value;
        if (selectedType === 'custom') {
            dataReportFields.style.display = 'none';
            customReportFields.style.display = 'block';
        } else {
            dataReportFields.style.display = 'block';
            customReportFields.style.display = 'none';
        }
        reportPreview.style.display = 'none'; // Esconde a prévia ao mudar o tipo
        downloadReportBtn.style.display = 'none';
    });

    generateReportBtn.addEventListener('click', () => {
        const type = reportTypeSelect.value;
        let reportContent = '';
        let fileName = '';

        const startDate = reportPeriodStart.value ? new Date(reportPeriodStart.value) : null;
        const endDate = reportPeriodEnd.value ? new Date(reportPeriodEnd.value) : null;

        const formatDate = (dateString) => {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString(currentLanguage) + ' ' + date.toLocaleTimeString(currentLanguage);
        };

        switch (type) {
            case 'users':
                reportContent = 'ID,Nome do Usuário,Email,Perfil,Senha Temporária,Data de Criação\n';
                db.usuarios.filter(user => {
                    const userDate = new Date(user.data_criacao);
                    return (!startDate || userDate >= startDate) && (!endDate || userDate <= endDate);
                }).forEach(user => {
                    reportContent += `${user.pk_usuario},"${user.nome_user}","${user.email_user}",${translations[currentLanguage][user.perfil]},${user.senha_temporaria ? 'Sim' : 'Não'},${formatDate(user.data_criacao)}\n`;
                });
                fileName = translations[currentLanguage]['reportTypeUsers'].toLowerCase();
                break;
            case 'employees':
                reportContent = 'ID,Nome,Email,Cargo\n';
                [...db.adms, ...db.funcionarios].filter(emp => {
                    // Sem campo de data direto nas tabelas adm/funcionario, pode-se filtrar por data de criação do cargo ou apenas listar todos
                    return true; // Para simplificar, lista todos
                }).forEach(emp => {
                    const cargo = db.cargos.find(c => c.pk_cargo === emp.fk_cargo);
                    const empName = emp.nome_adm || emp.nome_func;
                    const empEmail = emp.email_adm || emp.email_func;
                    reportContent += `${emp.pk_adm || emp.pk_funcionario},"${empName}","${empEmail}",${cargo ? cargo.nome_cargo : 'N/A'}\n`;
                });
                fileName = translations[currentLanguage]['reportTypeEmployees'].toLowerCase();
                break;
            case 'games':
                reportContent = 'ID,Nome do Jogo,Data de Lançamento,Desenvolvedora,Código,Disponível para Locação\n';
                db.jogos.filter(game => {
                    const gameDate = new Date(game.data_lanc);
                    return (!startDate || gameDate >= startDate) && (!endDate || gameDate <= endDate);
                }).forEach(game => {
                    const gameCode = db.codigo_games.find(c => c.pk_codgame === game.fk_codigo);
                    reportContent += `${game.pk_jogo},"${game.nome_jogo}",${game.data_lanc},"${game.desenvolvedora}",${gameCode ? gameCode.codigo : 'N/A'},${game.disponivel_locacao ? 'Sim' : 'Não'}\n`;
                });
                fileName = translations[currentLanguage]['reportTypeGames'].toLowerCase();
                break;
            case 'purchases':
                reportContent = 'ID,Nome do Comprador,Email do Comprador,Número do Cartão (últimos 4),Data da Compra,Nome do Jogo\n';
                db.compras.filter(purchase => {
                    const purchaseDate = new Date(purchase.data_compra);
                    return (!startDate || purchaseDate >= startDate) && (!endDate || purchaseDate <= endDate);
                }).forEach(purchase => {
                    const game = db.jogos.find(g => g.pk_jogo === purchase.jogo_id);
                    reportContent += `${purchase.id},"${purchase.nome}","${purchase.email}","xxxx-xxxx-xxxx-${purchase.cartao.slice(-4)}",${formatDate(purchase.data_compra)},"${game ? game.nome_jogo : 'N/A'}"\n`;
                });
                fileName = translations[currentLanguage]['reportTypePurchases'].toLowerCase();
                break;
            case 'pendingRentals':
                reportContent = 'ID,ID do Usuário,Nome do Usuário,ID do Jogo,Nome do Jogo,Data do Pedido,Status\n';
                db.locacoes_pendentes.filter(rental => {
                    const rentalDate = new Date(rental.data_pedido);
                    return (!startDate || rentalDate >= startDate) && (!endDate || rentalDate <= endDate);
                }).forEach(rental => {
                    const user = db.usuarios.find(u => u.pk_usuario === rental.usuario_id);
                    const game = db.jogos.find(g => g.pk_jogo === rental.jogo_id);
                    reportContent += `${rental.id},${rental.usuario_id},"${user ? user.nome_user : 'N/A'}",${rental.jogo_id},"${game ? game.nome_jogo : 'N/A'}",${formatDate(rental.data_pedido)},${translations[currentLanguage][rental.status] || rental.status}\n`;
                });
                fileName = translations[currentLanguage]['reportTypePendingRentals'].toLowerCase();
                break;
            case 'gameHistory':
                reportContent = 'ID,Usuário,Nome do Jogo,Hora de Entrada\n';
                db.historico_jogos.filter(entry => {
                    const entryTime = new Date(entry.hora_entrada);
                    return (!startDate || entryTime >= startDate) && (!endDate || entryTime <= endDate);
                }).forEach(entry => {
                    reportContent += `${entry.id},"${entry.usuario}","${entry.nome_jogo}",${formatDate(entry.hora_entrada)}\n`;
                });
                fileName = translations[currentLanguage]['reportTypeGameHistory'].toLowerCase();
                break;
            case 'custom':
                reportContent = customReportText.value;
                fileName = customReportFilename.value || 'relatorio_personalizado';
                break;
        }

        reportDataDisplay.textContent = reportContent;
        reportPreview.style.display = 'block';
        downloadReportBtn.style.display = 'block';
        downloadReportBtn.dataset.filename = `${fileName}_${new Date().toISOString().split('T')[0]}.csv`;
        downloadReportBtn.dataset.content = reportContent;
        logActivity(translations[currentLanguage]['logActivityGenerateReport'].replace('{type}', type));
    });

    downloadReportBtn.addEventListener('click', (e) => {
        const filename = e.target.dataset.filename;
        const content = e.target.dataset.content;
        const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        if (link.download !== undefined) { // Feature detection
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    });

    // --- HISTÓRICO DE JOGOS ---
    const gameHistoryTableBody = document.getElementById('game-history-table-body');
    const historySearchInput = document.getElementById('history-search');

    const renderGameHistoryTable = () => {
        const searchTerm = historySearchInput.value.toLowerCase();

        let filteredHistory = db.historico_jogos.filter(entry =>
            entry.usuario.toLowerCase().includes(searchTerm) || entry.nome_jogo.toLowerCase().includes(searchTerm)
        );

        gameHistoryTableBody.innerHTML = '';
        if (filteredHistory.length === 0) {
            gameHistoryTableBody.innerHTML = `<tr><td colspan="4" style="text-align: center;">${translations[currentLanguage]['noHistoryFound'] || 'Nenhum histórico encontrado.'}</td></tr>`;
            return;
        }

        filteredHistory.forEach(entry => {
            const row = gameHistoryTableBody.insertRow();
            row.insertCell(0).textContent = entry.id;
            row.insertCell(1).textContent = entry.usuario;
            row.insertCell(2).textContent = entry.nome_jogo;
            row.insertCell(3).textContent = new Date(entry.hora_entrada).toLocaleString(currentLanguage);
        });
    };

    historySearchInput.addEventListener('input', renderGameHistoryTable);


    // --- PERFIL DO USUÁRIO ATUAL ---
    const profileNameElement = document.getElementById('profile-name');
    const profileRoleElement = document.getElementById('profile-role');
    const profileAvatarSidebar = document.querySelector('.profile-avatar img');
    const userAvatarBtn = document.querySelector('.user-avatar-btn img');

    const profileNameInput = document.getElementById('profileNameInput');
    const profileEmailInput = document.getElementById('profileEmailInput');
    const profileRoleInput = document.getElementById('profileRoleInput');
    const profilePageAvatar = document.getElementById('profile-page-avatar');
    const profileForm = document.querySelector('.profile-form');
    const passwordForm = document.querySelector('.password-form');
    const changeAvatarBtn = document.querySelector('#profile-settings .btn-secondary');

    // Simular um usuário logado (pode ser um ADM ou Funcionário do seu DB simulado)
    let currentUser = JSON.parse(localStorage.getItem('currentUser')) || {
        id: 1, // ID do ADM 1 para testes
        name: 'Matheus Leal',
        email: 'matheus@admin.com',
        role: 'Administrador Geral',
        avatar: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQWJAMnWsbM3K9CRgWeqXXxUCCwNrA3ACj1GQ&s' // URL padrão
    };

    const loadProfileData = () => {
        profileNameElement.textContent = currentUser.name;
        profileRoleElement.textContent = currentUser.role;
        profileAvatarSidebar.src = currentUser.avatar;
        userAvatarBtn.src = currentUser.avatar;

        if (profileNameInput) profileNameInput.value = currentUser.name;
        if (profileEmailInput) profileEmailInput.value = currentUser.email;
        if (profileRoleInput) profileRoleInput.value = currentUser.role;
        if (profilePageAvatar) profilePageAvatar.src = currentUser.avatar;
    };

    if (profileForm) {
        profileForm.addEventListener('submit', (e) => {
            e.preventDefault();
            currentUser.name = profileNameInput.value;
            currentUser.email = profileEmailInput.value;
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            loadProfileData();
            logActivity(translations[currentLanguage]['logActivityUpdatedProfile']);
            alert('Perfil atualizado com sucesso!');
        });
    }

    if (passwordForm) {
        passwordForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const currentPass = document.getElementById('currentPassword').value;
            const newPass = document.getElementById('newPassword').value;
            const confirmNewPass = document.getElementById('confirmNewPassword').value;

            // Simulação de verificação de senha
            if (currentPass !== '12345678') { // A senha padrão do Matheus Leal no DB é '12345678'
                alert('Senha atual incorreta.');
                return;
            }
            if (newPass.length < 8) {
                alert('A nova senha deve ter pelo menos 8 caracteres.');
                return;
            }
            if (newPass !== confirmNewPass) {
                alert('A nova senha e a confirmação não coincidem.');
                return;
            }

            // Em um sistema real, você enviaria a nova senha para o backend para hashing
            // e atualização no banco. Aqui, apenas simulamos.
            currentUser.password = newPass; // Apenas para simulação no frontend
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            document.getElementById('currentPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmNewPassword').value = '';
            logActivity(translations[currentLanguage]['logActivityUpdatedPassword']);
            alert('Senha alterada com sucesso!');
        });
    }

    if (changeAvatarBtn) {
        changeAvatarBtn.addEventListener('click', () => {
            // Cria um input de arquivo temporário
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/png, image/jpeg, image/gif'; // Tipos de imagem aceitos

            fileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    alert(translations[currentLanguage]['selectAvatarFile']);
                    return;
                }

                const reader = new FileReader();
                reader.onload = (readerEvent) => {
                    currentUser.avatar = readerEvent.target.result; // Armazena a imagem como Data URL
                    localStorage.setItem('currentUser', JSON.stringify(currentUser));
                    loadProfileData(); // Atualiza a imagem na tela e na sidebar
                    logActivity(translations[currentLanguage]['logActivityUpdatedAvatar']);
                    alert('Avatar atualizado com sucesso!'); // Adicione tradução para isso
                };
                reader.readAsDataURL(file); // Lê o arquivo como Data URL
            });

            fileInput.click(); // Abre o seletor de arquivos
        });
    }

    // --- CONFIGURAÇÕES DO PAINEL ---
    const themeLightRadio = document.getElementById('theme-light');
    const themeDarkRadio = document.getElementById('theme-dark');
    const primaryColorPicker = document.getElementById('primary-color-picker');
    const languageSelectSettings = document.getElementById('language-select-settings');
    const clearCacheBtn = document.getElementById('clear-cache-btn');
    const exportSettingsBtn = document.getElementById('export-settings-btn');
    const importSettingsFile = document.getElementById('import-settings-file');
    const importFileNameSpan = document.getElementById('import-file-name');

    const loadSettings = () => {
        // Carrega o tema
        if (currentTheme === 'light') {
            themeLightRadio.checked = true;
        } else {
            themeDarkRadio.checked = true;
        }
        // Carrega a cor primária
        primaryColorPicker.value = currentPrimaryColor;
        // Carrega o idioma
        languageSelectSettings.value = currentLanguage;

        renderActivityLogs(); // Atualiza os logs de atividade
    };

    // Event Listeners para configurações
    if (themeLightRadio) {
        themeLightRadio.addEventListener('change', () => {
            currentTheme = 'light';
            document.body.setAttribute('data-theme', 'light');
            localStorage.setItem('adminTheme', 'light');
            logActivity(translations[currentLanguage]['logActivityThemeChanged'].replace('{theme}', 'Claro'));
            // Recriar gráficos para aplicar novas cores de texto
            renderUserProfileChart();
            renderGameReleaseChart();
        });
    }

    if (themeDarkRadio) {
        themeDarkRadio.addEventListener('change', () => {
            currentTheme = 'dark';
            document.body.setAttribute('data-theme', 'dark');
            localStorage.setItem('adminTheme', 'dark');
            logActivity(translations[currentLanguage]['logActivityThemeChanged'].replace('{theme}', 'Escuro'));
            // Recriar gráficos para aplicar novas cores de texto
            renderUserProfileChart();
            renderGameReleaseChart();
        });
    }

    if (primaryColorPicker) {
        primaryColorPicker.addEventListener('input', (e) => {
            currentPrimaryColor = e.target.value;
            document.documentElement.style.setProperty('--primary-color', currentPrimaryColor);
            localStorage.setItem('adminPrimaryColor', currentPrimaryColor);
            logActivity(translations[currentLanguage]['logActivityColorChanged'].replace('{color}', currentPrimaryColor));
            // Recriar gráficos para aplicar novas cores
            renderUserProfileChart();
            renderGameReleaseChart();
        });
    }

    if (languageSelectSettings) {
        languageSelectSettings.addEventListener('change', (e) => {
            currentLanguage = e.target.value;
            localStorage.setItem('adminLang', currentLanguage);
            applyTranslations();
            logActivity(translations[currentLanguage]['logActivityLanguageChanged'].replace('{lang}', currentLanguage));
            // Recriar gráficos para aplicar novas traduções
            renderUserProfileChart();
            renderGameReleaseChart();
        });
    }

    if (clearCacheBtn) {
        clearCacheBtn.addEventListener('click', () => {
            if (confirm(translations[currentLanguage]['confirmClearCache'] || 'Tem certeza que deseja limpar o cache local? Todos os dados simulados serão perdidos.')) {
                localStorage.clear();
                // Opcional: recarregar a página para redefinir o estado completamente
                location.reload();
                logActivity(translations[currentLanguage]['logActivityClearedCache']);
                alert('Cache local limpo. A página será recarregada.');
            }
        });
    }

    if (exportSettingsBtn) {
        exportSettingsBtn.addEventListener('click', () => {
            const settingsToExport = {
                adminLang: localStorage.getItem('adminLang'),
                adminTheme: localStorage.getItem('adminTheme'),
                adminPrimaryColor: localStorage.getItem('adminPrimaryColor'),
                currentUser: localStorage.getItem('currentUser'),
                activityLogs: localStorage.getItem('activityLogs'),
                // Dados simulados do DB
                db_cargos: localStorage.getItem('db_cargos'),
                db_usuarios: localStorage.getItem('db_usuarios'),
                db_adms: localStorage.getItem('db_adms'),
                db_funcionarios: localStorage.getItem('db_funcionarios'),
                db_codigo_games: localStorage.getItem('db_codigo_games'),
                db_jogos: localStorage.getItem('db_jogos'),
                db_biblioteca_usuarios: localStorage.getItem('db_biblioteca_usuarios'),
                db_compras: localStorage.getItem('db_compras'),
                db_locacoes_pendentes: localStorage.getItem('db_locacoes_pendentes'),
                db_publicadoras: localStorage.getItem('db_publicadoras'),
                db_generos: localStorage.getItem('db_generos'),
                db_temas: localStorage.getItem('db_temas'),
                db_estilos: localStorage.getItem('db_estilos'),
                db_plataformas: localStorage.getItem('db_plataformas'),
                db_idiomas: localStorage.getItem('db_idiomas'),
                db_modos: localStorage.getItem('db_modos'),
                db_jogo_genero: localStorage.getItem('db_jogo_genero'),
                db_jogo_tema: localStorage.getItem('db_jogo_tema'),
                db_jogo_estilo: localStorage.getItem('db_jogo_estilo'),
                db_jogo_plataforma: localStorage.getItem('db_jogo_plataforma'),
                db_jogo_idioma: localStorage.getItem('db_jogo_idioma'),
                db_jogo_modo: localStorage.getItem('db_jogo_modo'),
                db_amigos: localStorage.getItem('db_amigos'),
                db_mensagens: localStorage.getItem('db_mensagens'),
                db_pedidos_amizade: localStorage.getItem('db_pedidos_amizade'),
                db_historico_jogos: localStorage.getItem('db_historico_jogos')
            };
            const dataStr = JSON.stringify(settingsToExport, null, 2);
            const blob = new Blob([dataStr], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'admin_settings_and_data.json';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
            logActivity(translations[currentLanguage]['logActivityExportedSettings']);
        });
    }

    if (importSettingsFile) {
        importSettingsFile.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                importFileNameSpan.textContent = file.name;
                const reader = new FileReader();
                reader.onload = (event) => {
                    try {
                        const importedSettings = JSON.parse(event.target.result);
                        for (const key in importedSettings) {
                            if (importedSettings[key] !== null) {
                                localStorage.setItem(key, importedSettings[key]);
                            } else {
                                localStorage.removeItem(key); // Remove if null was explicitly exported
                            }
                        }
                        alert(translations[currentLanguage]['settingsImportedSuccessfully'] || 'Configurações e dados importados com sucesso! A página será recarregada.');
                        logActivity(translations[currentLanguage]['logActivityImportedSettings']);
                        location.reload();
                    } catch (error) {
                        alert(translations[currentLanguage]['invalidFileFormat'] || 'Erro: Formato de arquivo inválido. Certifique-se de importar um arquivo JSON válido.');
                        console.error('Error importing settings:', error);
                    }
                };
                reader.readAsText(file);
            } else {
                importFileNameSpan.textContent = '';
            }
        });
    }


    document.addEventListener('DOMContentLoaded', function() {
        const mainContent = document.getElementById('main-content');
        const menuLinks = document.querySelectorAll('.submenu a'); // Seleciona todos os links dentro dos submenus
    
        menuLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Impede o comportamento padrão do link (navegar para #)
    
                const filePath = this.getAttribute('data-file'); // Pega o caminho do arquivo PHP do atributo data-file
    
                if (filePath) {
                    fetch(filePath)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erro ao carregar a página: ' + response.statusText);
                            }
                            return response.text();
                        })
                        .then(html => {
                            mainContent.innerHTML = html; // Insere o HTML recebido dentro da div main-content
                        })
                        .catch(error => {
                            console.error('Houve um problema com a requisição Fetch:', error);
                            mainContent.innerHTML = '<p style="color: red;">Erro ao carregar o conteúdo. Por favor, tente novamente.</p>';
                        });
                } else {
                    console.warn('O link clicado não possui um atributo data-file.');
                }
            });
        });
    
        // Opcional: Adicionar funcionalidade para os links principais do menu (Usuários, Funcionários, etc.)
        const topMenuLinks = document.querySelectorAll('.menu > li > a[data-section]');
        topMenuLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                // Se você quiser que o clique nos itens de nível superior também carregue algo
                // Por exemplo, uma página de "Visão Geral de Usuários" ao clicar em "Usuários"
                // Você precisaria de um `data-file` também nesses links ou uma lógica diferente.
                // Por enquanto, apenas impede a navegação padrão se for um item pai com submenu
                if (this.nextElementSibling && this.nextElementSibling.classList.contains('submenu')) {
                     event.preventDefault();
                     // Adicione aqui lógica para mostrar/esconder o submenu, se ainda não tiver
                     // this.nextElementSibling.classList.toggle('active'); // Exemplo
                }
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const mainContent = document.getElementById('main-content'); // A área onde o conteúdo PHP será carregado

    // --- Gerenciamento de Submenus (Exibir/Ocultar) ---
    const mainMenuLinks = document.querySelectorAll('.main-menu-item');

    mainMenuLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Impede a navegação padrão do link

            const targetSubmenuId = this.getAttribute('data-target-submenu');
            const targetSubmenu = document.getElementById(targetSubmenuId);

            if (targetSubmenu) {
                // Fechar outros submenus abertos (opcional, mas recomendado para UX)
                document.querySelectorAll('.submenu').forEach(submenu => {
                    if (submenu !== targetSubmenu && submenu.classList.contains('active')) {
                        submenu.classList.remove('active');
                    }
                });

                // Alternar a classe 'active' para exibir/ocultar o submenu clicado
                targetSubmenu.classList.toggle('active');
            }
        });
    });

    // --- Carregamento de Conteúdo via AJAX ---
    const submenuItems = document.querySelectorAll('.submenu-item'); // Seleciona todos os links dos submenus

    submenuItems.forEach(item => {
        item.addEventListener('click', function(event) {
            event.preventDefault(); // Impede a navegação padrão do link (importante para AJAX)

            const filePath = this.getAttribute('data-file'); // Pega o caminho do arquivo PHP do atributo data-file

            if (filePath) {
                // Adicione uma classe para indicar carregamento (opcional, para feedback visual)
                mainContent.classList.add('loading');
                mainContent.innerHTML = '<p class="loading-message"><i class="fas fa-spinner fa-spin"></i> Carregando...</p>'; // Mensagem de carregamento

                fetch(filePath)
                    .then(response => {
                        // Verifica se a requisição foi bem-sucedida (status 200 OK)
                        if (!response.ok) {
                            // Lança um erro se a resposta não for OK
                            throw new Error(`Erro HTTP! Status: ${response.status} - ${response.statusText}`);
                        }
                        return response.text(); // Converte a resposta para texto (HTML)
                    })
                    .then(html => {
                        mainContent.innerHTML = html; // Insere o HTML recebido dentro da div main-content
                        // Remove a classe de carregamento
                        mainContent.classList.remove('loading');
                    })
                    .catch(error => {
                        console.error('Houve um problema com a requisição Fetch:', error);
                        mainContent.innerHTML = '<p style="color: red;">Erro ao carregar o conteúdo. Por favor, verifique o caminho do arquivo e a conexão.</p>';
                        mainContent.classList.remove('loading'); // Remove a classe de carregamento mesmo em caso de erro
                    });
            } else {
                console.warn('O link do submenu clicado não possui um atributo data-file.');
            }
        });
    });

    // Opcional: Carregar um conteúdo padrão quando a página é carregada pela primeira vez
    // Por exemplo, carregar a página de "Listar Usuários" ou uma "Bem-Vindo"
    // Pode-se simular um clique no item desejado ou carregar um arquivo específico.
    // Exemplo: Carregar "Listar Usuários" ao iniciar
    // const defaultLoadItem = document.querySelector('.submenu-item[data-section="list-user"]');
    // if (defaultLoadItem) {
    //     defaultLoadItem.click(); // Simula um clique
    // }
});

