document.addEventListener('DOMContentLoaded', function() {
    // --- Elementos do DOM ---
    const menuLinks = document.querySelectorAll('.sidebar-menu a');
    const contentSections = document.querySelectorAll('.content-section');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const dropdownTriggers = document.querySelectorAll('.dropdown-trigger'); // Generalizado para todos os dropdowns

    // Top Bar
    const searchBar = document.querySelector('.search-bar input'); // Nova
    const notificationBtn = document.querySelector('.notification-btn');
    const notificationBadge = document.querySelector('.notification-badge');
    const notificationDropdown = document.querySelector('.notifications-dropdown');
    const profileTrigger = document.querySelector('.profile-info'); // Nova
    const profileDropdown = document.querySelector('.profile-dropdown'); // Nova
    const editProfileLink = document.getElementById('edit-profile-link'); // Nova
    const logoutBtnSidebar = document.getElementById('logout-btn');
    const logoutBtnTopbar = document.getElementById('logout-btn-topbar');
    const profileInfoTopbar = document.querySelector('.profile-info-topbar'); // Adicionado para o dropdown da topbar

    // Dashboard
    const applyFiltersBtn = document.querySelector('.apply-filters');
    const timePeriodSelect = document.getElementById('time-period');
    const gameFilterSelect = document.getElementById('game-filter');
    const totalRevenueEl = document.getElementById('total-revenue');
    const totalUsersEl = document.getElementById('total-users');
    const gamesLaunchedEl = document.getElementById('games-launched');
    const bugsReportedEl = document.getElementById('bugs-reported');

    // Chart instances
    let userGrowthChartInstance;
    let salesByGameChartInstance;
    let revenueOverTimeChartInstance;
    let expensesOverTimeChartInstance;

    // Modals
    const modal = document.getElementById('modal');
    const closeModalBtns = document.querySelectorAll('.close-modal-btn');

    // User Management elements
    const addUserBtn = document.getElementById('add-user-btn');
    const userFormModal = document.getElementById('user-form-modal');
    const userFormTitle = document.getElementById('user-form-title');
    const userForm = document.getElementById('user-form');
    const usersTableBody = document.getElementById('users-table-body');
    const prevUsersBtn = document.getElementById('prev-users-btn');
    const nextUsersBtn = document.getElementById('next-users-btn');
    const currentPageUsersSpan = document.getElementById('current-page-users');
    const totalPagesUsersSpan = document.getElementById('total-pages-users');
    let currentPageUsers = 1;
    const usersPerPage = 10;

    // Permission Management elements
    const addPermissionBtn = document.getElementById('add-permission-btn');
    const permissionFormModal = document.getElementById('permission-form-modal');
    const permissionFormTitle = document.getElementById('permission-form-title');
    const permissionForm = document.getElementById('permission-form');
    const permissionsTableBody = document.getElementById('permissions-table-body');
    const prevPermissionsBtn = document.getElementById('prev-permissions-btn');
    const nextPermissionsBtn = document.getElementById('next-permissions-btn');
    const currentPagePermissionsSpan = document.getElementById('current-page-permissions');
    const totalPagesPermissionsSpan = document.getElementById('total-pages-permissions');
    let currentPagePermissions = 1;
    const permissionsPerPage = 10;

    // Session Management elements
    const sessionsTableBody = document.getElementById('sessions-table-body');
    const prevSessionsBtn = document.getElementById('prev-sessions-btn');
    const nextSessionsBtn = document.getElementById('next-sessions-btn');
    const currentPageSessionsSpan = document.getElementById('current-page-sessions');
    const totalPagesSessionsSpan = document.getElementById('total-pages-sessions');
    let currentPageSessions = 1;
    const sessionsPerPage = 10;

    // Game Releases elements
    const gameReleaseCalendar = document.getElementById('game-release-calendar');
    const currentMonthYearHeader = document.getElementById('current-month-year');
    const prevMonthBtn = document.getElementById('prev-month-btn');
    const nextMonthBtn = document.getElementById('next-month-btn');
    const addGameReleaseBtn = document.getElementById('add-game-release-btn'); // Novo botão
    const gameReleaseFormModal = document.getElementById('game-release-form-modal'); // Novo modal
    const gameReleaseFormTitle = document.getElementById('game-release-form-title');
    const gameReleaseForm = document.getElementById('game-release-form');

    let currentCalendarDate = new Date(); // Para o calendário

    // Finance elements
    const financeTotalRevenueEl = document.getElementById('finance-total-revenue');
    const financeTotalExpensesEl = document.getElementById('finance-total-expenses');
    const financeNetProfitEl = document.getElementById('finance-net-profit');

    // Developer Management elements
    const addDeveloperBtn = document.getElementById('add-developer-btn');
    const developerFormModal = document.getElementById('developer-form-modal');
    const developerFormTitle = document.getElementById('developer-form-title');
    const developerForm = document.getElementById('developer-form');
    const developersTableBody = document.getElementById('developers-table-body');

    // Feedback Management elements
    const feedbackTableBody = document.getElementById('feedback-table-body');

    // Support Tickets elements
    const ticketsTableBody = document.getElementById('tickets-table-body');

    // Bug Reports elements
    const bugReportsTableBody = document.getElementById('bug-reports-table-body');

    // Shop Products elements
    const addProductBtn = document.getElementById('add-product-btn');
    const productFormModal = document.getElementById('product-form-modal');
    const productFormTitle = document.getElementById('product-form-title');
    const productForm = document.getElementById('product-form');
    const productsTableBody = document.getElementById('products-table-body');

    // Community Management elements
    const addBanBtn = document.getElementById('add-ban-btn');
    const banFormModal = document.getElementById('ban-form-modal');
    const banFormTitle = document.getElementById('ban-form-title');
    const banForm = document.getElementById('ban-form');
    const bannedUsersTableBody = document.getElementById('banned-users-table-body');

    const addGroupToolBtn = document.getElementById('add-group-tool-btn');
    const groupToolFormModal = document.getElementById('group-tool-form-modal');
    const groupToolFormTitle = document.getElementById('group-tool-form-title');
    const groupToolForm = document.getElementById('group-tool-form');
    const groupToolsTableBody = document.getElementById('group-tools-table-body');

    // Security Logs elements
    const exportLogsBtn = document.getElementById('export-logs-btn');
    const logsTableBody = document.getElementById('logs-table-body');

    // Settings elements
    const saveGeneralSettingsBtn = document.getElementById('save-general-settings-btn');
    const saveNotificationSettingsBtn = document.getElementById('save-notification-settings-btn');
    const changePasswordBtn = document.getElementById('change-password-btn');
    const logoutAllDevicesBtn = document.getElementById('logout-all-devices-btn');
    const saveAppearanceSettingsBtn = document.getElementById('save-appearance-settings-btn');
    const manageIntegrationsBtn = document.getElementById('manage-integrations-btn');


    // --- Funções Auxiliares ---

    // Função para mostrar modal
    function showModal(modalElement) {
        modal.style.display = 'flex';
        modalElement.style.display = 'block';
        setTimeout(() => {
            modal.classList.add('show');
            modalElement.classList.add('show');
        }, 10); // Pequeno delay para a transição
    }

    // Função para esconder modal
    function hideModal() {
        modal.classList.remove('show');
        document.querySelectorAll('.modal-content').forEach(content => {
            content.classList.remove('show');
        });
        setTimeout(() => {
            modal.style.display = 'none';
            document.querySelectorAll('.modal-content').forEach(content => {
                content.style.display = 'none';
            });
        }, 300); // Espera a transição terminar
    }

    // Fecha modais ao clicar fora
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            hideModal();
        }
    });

    // Fecha modais ao clicar nos botões de fechar
    closeModalBtns.forEach(button => {
        button.addEventListener('click', hideModal);
    });

    // Função para alternar visibilidade de dropdowns
    function toggleDropdown(dropdownElement) {
        dropdownElement.classList.toggle('show');
    }

    // Função para esconder todos os dropdowns
    function hideAllDropdowns() {
        document.querySelectorAll('.profile-dropdown, .notifications-dropdown').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }

    // Esconder dropdowns ao clicar fora
    document.addEventListener('click', function(event) {
        if (!notificationBtn.contains(event.target) && !notificationDropdown.contains(event.target)) {
            notificationDropdown.classList.remove('show');
        }
        if (!profileTrigger.contains(event.target) && !profileDropdown.contains(event.target) &&
            !profileInfoTopbar.contains(event.target) && !profileDropdown.contains(event.target)) {
            profileDropdown.classList.remove('show');
        }
    });

    // Ativar seção de conteúdo
    function activateSection(sectionId) {
        contentSections.forEach(section => {
            section.classList.remove('active');
        });
        document.getElementById(sectionId).classList.add('active');

        menuLinks.forEach(link => {
            link.classList.remove('active');
            if (link.dataset.section === sectionId) {
                link.classList.add('active');
            }
        });

        // Atualiza o estado da sidebar no mobile se estiver aberta
        if (window.innerWidth <= 768 && sidebar.classList.contains('sidebar-open')) {
            sidebar.classList.remove('sidebar-open');
            document.querySelector('.admin-container').classList.remove('sidebar-open');
        }

        // Re-renderiza o calendário quando a seção de lançamento de jogos é ativada
        if (sectionId === 'game-releases') {
            renderCalendar();
        }
        // Re-renderiza os gráficos financeiros se a seção for ativada (apenas se já existirem os canvases)
        if (sectionId === 'finance') {
            if (document.getElementById('revenueOverTimeChart') && document.getElementById('expensesOverTimeChart')) {
                createFinanceCharts();
            }
        }
    }

    // --- Lógica da Sidebar e Top Bar ---

    // Toggle Sidebar
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        document.querySelector('.admin-container').classList.toggle('sidebar-collapsed');
    });

    // Toggle Sidebar para mobile (botão na topbar)
    document.querySelector('.sidebar-toggle-mobile').addEventListener('click', function() {
        sidebar.classList.toggle('sidebar-open');
        document.querySelector('.admin-container').classList.toggle('sidebar-open');
    });

    // Menu Navigation
    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.dataset.section;
            activateSection(sectionId);
        });
    });

    // Dropdown de Notificações
    notificationBtn.addEventListener('click', function() {
        toggleDropdown(notificationDropdown);
        profileDropdown.classList.remove('show'); // Fecha outros dropdowns
    });

    // Dropdown de Perfil (Sidebar)
    profileTrigger.addEventListener('click', function() {
        toggleDropdown(profileDropdown);
        notificationDropdown.classList.remove('show'); // Fecha outros dropdowns
    });

    // Dropdown de Perfil (Topbar)
    profileInfoTopbar.addEventListener('click', function() {
        toggleDropdown(profileDropdown);
        notificationDropdown.classList.remove('show'); // Fecha outros dropdowns
    });


    // Links de Logout
    logoutBtnSidebar.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '../auth/logout.php'; // Redireciona para o script de logout
    });

    logoutBtnTopbar.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '../auth/logout.php'; // Redireciona para o script de logout
    });

    // Links de Editar Perfil (apenas um exemplo, não funcional)
    editProfileLink.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Funcionalidade de Editar Perfil será implementada.');
        hideAllDropdowns();
    });
    document.getElementById('edit-profile-link-topbar').addEventListener('click', function(e) {
        e.preventDefault();
        alert('Funcionalidade de Editar Perfil será implementada.');
        hideAllDropdowns();
    });


    // --- Simulação de Dados (Em um ambiente real, viriam de uma API/BD) ---
    let users = [
        { id: 1, username: 'admin', email: 'admin@example.com', role: 'Administrador', status: 'Ativo' },
        { id: 2, username: 'joao.s', email: 'joao.s@example.com', role: 'Editor', status: 'Ativo' },
        { id: 3, username: 'maria.p', email: 'maria.p@example.com', role: 'Visualizador', status: 'Inativo' },
        { id: 4, username: 'carlos.a', email: 'carlos.a@example.com', role: 'Administrador', status: 'Ativo' },
        { id: 5, username: 'ana.l', email: 'ana.l@example.com', role: 'Editor', status: 'Ativo' },
        { id: 6, username: 'pedro.r', email: 'pedro.r@example.com', role: 'Visualizador', status: 'Ativo' },
        { id: 7, username: 'sofia.m', email: 'sofia.m@example.com', role: 'Editor', status: 'Inativo' },
        { id: 8, username: 'miguel.c', email: 'miguel.c@example.com', role: 'Administrador', status: 'Ativo' },
        { id: 9, username: 'laura.f', email: 'laura.f@example.com', role: 'Visualizador', status: 'Ativo' },
        { id: 10, username: 'bruno.g', email: 'bruno.g@example.com', role: 'Editor', status: 'Ativo' },
        { id: 11, username: 'fernanda.h', email: 'fernanda.h@example.com', role: 'Visualizador', status: 'Inativo' },
        { id: 12, username: 'rafael.i', email: 'rafael.i@example.com', role: 'Administrador', status: 'Ativo' },
    ];

    let permissions = [
        { id: 1, name: 'Gerenciar Usuários', description: 'Permite adicionar, editar e remover usuários.' },
        { id: 2, name: 'Editar Conteúdo', description: 'Permite criar e modificar artigos e posts.' },
        { id: 3, name: 'Aprovar Comentários', description: 'Permite moderar comentários no site.' },
        { id: 4, name: 'Visualizar Relatórios', description: 'Permite acessar relatórios de desempenho.' },
    ];

    let sessions = [
        { id: 101, user: 'admin', ip: '192.168.1.1', last_access: '2025-06-07 10:30', device: 'Desktop (Chrome)' },
        { id: 102, user: 'joao.s', ip: '10.0.0.5', last_access: '2025-06-07 09:15', device: 'Mobile (Safari)' },
        { id: 103, user: 'maria.p', ip: '172.16.0.10', last_access: '2025-06-06 18:00', device: 'Desktop (Firefox)' },
        { id: 104, user: 'admin', ip: '192.168.1.2', last_access: '2025-06-07 15:00', device: 'Desktop (Edge)' },
        { id: 105, user: 'pedro.r', ip: '10.0.0.8', last_access: '2025-06-07 11:45', device: 'Tablet (Android)' },
    ];

    let gameReleases = [
        { id: 1, name: 'Cyberpunk 2077: Phantom Liberty', date: '2025-09-26', platform: 'PC, PS5, Xbox Series X/S' },
        { id: 2, name: 'The Witcher 4', date: '2026-03-10', platform: 'PC, PS5, Xbox Series X/S' },
        { id: 3, name: 'Starfield Expansion', date: '2025-11-15', platform: 'PC, Xbox Series X/S' },
        { id: 4, name: 'Elden Ring: Shadow of the Erdtree', date: '2025-06-21', platform: 'PC, PS, Xbox' },
        { id: 5, name: 'Avowed', date: '2025-02-01', platform: 'PC, Xbox Series X/S' },
    ].map(release => ({...release, date: new Date(release.date + 'T00:00:00') })); // Garante que as datas sejam objetos Date

    let developers = [
        { id: 1, name: 'Alice Smith', team: 'Frontend', role: 'Desenvolvedora Sênior', email: 'alice@example.com' },
        { id: 2, name: 'Bob Johnson', team: 'Backend', role: 'Engenheiro de Software', email: 'bob@example.com' },
        { id: 3, name: 'Charlie Brown', team: 'Mobile', role: 'Desenvolvedor iOS', email: 'charlie@example.com' },
    ];

    let products = [
        { id: 1, name: 'Cypher Mug', price: 25.00, stock: 150, status: 'Disponível' },
        { id: 2, name: 'T-Shirt CypherCorp', price: 45.00, stock: 200, status: 'Disponível' },
        { id: 3, name: 'Poster Exclusivo', price: 15.00, stock: 50, status: 'Fora de Estoque' },
    ];

    let bannedUsers = [
        { id: 1, username: 'spam_bot_01', reason: 'Spam excessivo', expiration_date: '2025-12-31' },
        { id: 2, username: 'cheater_pro', reason: 'Uso de hacks em jogos', expiration_date: 'Nunca' },
    ];

    let groupTools = [
        { id: 1, name: 'Moderação de Chat', description: 'Ferramenta para moderar o chat da comunidade.', status: 'Ativo' },
        { id: 2, name: 'Sistema de Denúncias', description: 'Permite aos usuários reportar comportamento inadequado.', status: 'Ativo' },
    ];

    let themes = [
        { id: 1, name: 'Tema Padrão Cypher', author: 'CypherCorp', version: '1.0.0', status: 'Ativo' },
        { id: 2, name: 'Tema Noturno Elegante', author: 'DesignLab', version: '1.2.0', status: 'Inativo' },
    ];

    let banners = [
        { id: 1, name: 'Promoção de Verão', imageUrl: 'https://via.placeholder.com/300x100/7B1FA2/FFFFFF?text=Banner+Verao', isActive: true },
        { id: 2, name: 'Novo Jogo Lançamento', imageUrl: 'https://via.placeholder.com/300x100/1976D2/FFFFFF?text=Banner+Jogo', isActive: false },
    ];

    let logs = [
        { id: 1, datetime: '2025-06-07 14:00:00', level: 'INFO', event: 'Login bem-sucedido', user: 'admin', ip: '192.168.1.1' },
        { id: 2, datetime: '2025-06-07 14:01:30', level: 'WARNING', event: 'Tentativa de acesso negada', user: 'guest', ip: '203.0.113.45' },
        { id: 3, datetime: '2025-06-07 14:05:00', level: 'ERROR', event: 'Falha na conexão com DB', user: 'N/A', ip: 'N/A' },
        { id: 4, datetime: '2025-06-07 14:10:00', level: 'INFO', event: 'Usuário joao.s atualizou perfil', user: 'joao.s', ip: '10.0.0.5' },
    ];


    // --- Funções de Renderização de Tabelas ---

    function renderUsersTable(page) {
        const start = (page - 1) * usersPerPage;
        const end = start + usersPerPage;
        const paginatedUsers = users.slice(start, end);

        usersTableBody.innerHTML = '';
        paginatedUsers.forEach(user => {
            const row = usersTableBody.insertRow();
            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.username}</td>
                <td>${user.email}</td>
                <td>${user.role}</td>
                <td><span class="status-badge status-${user.status.toLowerCase()}">${user.status}</span></td>
                <td class="actions">
                    <button class="btn-icon edit-user-btn" data-id="${user.id}"><i class="fas fa-edit"></i></button>
                    <button class="btn-icon delete-user-btn" data-id="${user.id}"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;
        });
        updatePagination(currentPageUsers, users.length, usersPerPage, currentPageUsersSpan, totalPagesUsersSpan);
        attachActionListeners('user');
    }

    function renderPermissionsTable(page) {
        const start = (page - 1) * permissionsPerPage;
        const end = start + permissionsPerPage;
        const paginatedPermissions = permissions.slice(start, end);

        permissionsTableBody.innerHTML = '';
        paginatedPermissions.forEach(permission => {
            const row = permissionsTableBody.insertRow();
            row.innerHTML = `
                <td>${permission.id}</td>
                <td>${permission.name}</td>
                <td>${permission.description}</td>
                <td class="actions">
                    <button class="btn-icon edit-permission-btn" data-id="${permission.id}"><i class="fas fa-edit"></i></button>
                    <button class="btn-icon delete-permission-btn" data-id="${permission.id}"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;
        });
        updatePagination(currentPagePermissions, permissions.length, permissionsPerPage, currentPagePermissionsSpan, totalPagesPermissionsSpan);
        attachActionListeners('permission');
    }

    function renderSessionsTable(page) {
        const start = (page - 1) * sessionsPerPage;
        const end = start + sessionsPerPage;
        const paginatedSessions = sessions.slice(start, end);

        sessionsTableBody.innerHTML = '';
        paginatedSessions.forEach(session => {
            const row = sessionsTableBody.insertRow();
            row.innerHTML = `
                <td>${session.id}</td>
                <td>${session.user}</td>
                <td>${session.ip}</td>
                <td>${session.last_access}</td>
                <td>${session.device}</td>
                <td class="actions">
                    <button class="btn-icon terminate-session-btn" data-id="${session.id}" title="Encerrar Sessão"><i class="fas fa-times-circle"></i></button>
                </td>
            `;
        });
        updatePagination(currentPageSessions, sessions.length, sessionsPerPage, currentPageSessionsSpan, totalPagesSessionsSpan);
        attachActionListeners('session');
    }

    function renderDevelopersTable() {
        developersTableBody.innerHTML = '';
        developers.forEach(dev => {
            const row = developersTableBody.insertRow();
            row.innerHTML = `
                <td>${dev.id}</td>
                <td>${dev.name}</td>
                <td>${dev.team}</td>
                <td>${dev.role}</td>
                <td>${dev.email}</td>
                <td class="actions">
                    <button class="btn-icon edit-developer-btn" data-id="${dev.id}"><i class="fas fa-edit"></i></button>
                    <button class="btn-icon delete-developer-btn" data-id="${dev.id}"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;
        });
        attachActionListeners('developer');
    }

    function renderFeedbackTable() {
        feedbackTableBody.innerHTML = '';
        const feedbackData = [
            { id: 1, user: 'joao.s', type: 'Sugestão', subject: 'Melhoria na UI', date: '2025-06-01', status: 'Pendente' },
            { id: 2, user: 'ana.l', type: 'Elogio', subject: 'Ótimo Suporte', date: '2025-05-28', status: 'Resolvido' },
            { id: 3, user: 'carlos.a', type: 'Crítica', subject: 'Erro ao Logar', date: '2025-06-05', status: 'Em Análise' },
        ];
        feedbackData.forEach(item => {
            const row = feedbackTableBody.insertRow();
            row.innerHTML = `
                <td>${item.id}</td>
                <td>${item.user}</td>
                <td>${item.type}</td>
                <td>${item.subject}</td>
                <td>${item.date}</td>
                <td><span class="status-badge status-${item.status.toLowerCase().replace(' ', '-')}">${item.status}</span></td>
                <td class="actions">
                    <button class="btn-icon view-feedback-btn" data-id="${item.id}"><i class="fas fa-eye"></i></button>
                    <button class="btn-icon delete-feedback-btn" data-id="${item.id}"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;
        });
        attachActionListeners('feedback');
    }

    function renderTicketsTable() {
        ticketsTableBody.innerHTML = '';
        const ticketsData = [
            { id: 1, user: 'maria.p', subject: 'Problema com Pagamento', status: 'Aberto', priority: 'Alta', last_update: '2025-06-07 10:00' },
            { id: 2, user: 'pedro.r', subject: 'Dúvida sobre Recurso X', status: 'Fechado', priority: 'Baixa', last_update: '2025-06-06 14:30' },
            { id: 3, user: 'sofia.m', subject: 'Solicitação de Nova Feature', status: 'Em Progresso', priority: 'Média', last_update: '2025-06-07 09:00' },
        ];
        ticketsData.forEach(item => {
            const row = ticketsTableBody.insertRow();
            row.innerHTML = `
                <td>${item.id}</td>
                <td>${item.user}</td>
                <td>${item.subject}</td>
                <td><span class="status-badge status-${item.status.toLowerCase().replace(' ', '-')}">${item.status}</span></td>
                <td><span class="priority-badge priority-${item.priority.toLowerCase()}">${item.priority}</span></td>
                <td>${item.last_update}</td>
                <td class="actions">
                    <button class="btn-icon view-ticket-btn" data-id="${item.id}"><i class="fas fa-eye"></i></button>
                    <button class="btn-icon close-ticket-btn" data-id="${item.id}"><i class="fas fa-check-circle"></i></button>
                </td>
            `;
        });
        attachActionListeners('ticket');
    }

    function renderBugReportsTable() {
        bugReportsTableBody.innerHTML = '';
        const bugReportsData = [
            { id: 1, reported_by: 'bruno.g', title: 'Bug ao carregar imagem de perfil', status: 'Aberto', priority: 'Alta', date: '2025-06-06' },
            { id: 2, reported_by: 'fernanda.h', title: 'Erro de formatação em tabelas', status: 'Resolvido', priority: 'Média', date: '2025-06-01' },
        ];
        bugReportsData.forEach(item => {
            const row = bugReportsTableBody.insertRow();
            row.innerHTML = `
                <td>${item.id}</td>
                <td>${item.reported_by}</td>
                <td>${item.title}</td>
                <td><span class="status-badge status-${item.status.toLowerCase().replace(' ', '-')}">${item.status}</span></td>
                <td><span class="priority-badge priority-${item.priority.toLowerCase()}">${item.priority}</span></td>
                <td>${item.date}</td>
                <td class="actions">
                    <button class="btn-icon view-bug-btn" data-id="${item.id}"><i class="fas fa-eye"></i></button>
                    <button class="btn-icon resolve-bug-btn" data-id="${item.id}"><i class="fas fa-check-circle"></i></button>
                </td>
            `;
        });
        attachActionListeners('bug');
    }

    function renderProductsTable() {
        productsTableBody.innerHTML = '';
        products.forEach(product => {
            const row = productsTableBody.insertRow();
            row.innerHTML = `
                <td>${product.id}</td>
                <td>${product.name}</td>
                <td>R$ ${product.price.toFixed(2)}</td>
                <td>${product.stock}</td>
                <td><span class="status-badge status-${product.status.toLowerCase().replace(' ', '-')}">${product.status}</span></td>
                <td class="actions">
                    <button class="btn-icon edit-product-btn" data-id="${product.id}"><i class="fas fa-edit"></i></button>
                    <button class="btn-icon delete-product-btn" data-id="${product.id}"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;
        });
        attachActionListeners('product');
    }

    function renderBannedUsersTable() {
        bannedUsersTableBody.innerHTML = '';
        bannedUsers.forEach(user => {
            const row = bannedUsersTableBody.insertRow();
            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.username}</td>
                <td>${user.reason}</td>
                <td>${user.expiration_date}</td>
                <td class="actions">
                    <button class="btn-icon unban-user-btn" data-id="${user.id}"><i class="fas fa-undo-alt"></i></button>
                    <button class="btn-icon edit-ban-btn" data-id="${user.id}"><i class="fas fa-edit"></i></button>
                </td>
            `;
        });
        attachActionListeners('bannedUser');
    }

    function renderGroupToolsTable() {
        groupToolsTableBody.innerHTML = '';
        groupTools.forEach(tool => {
            const row = groupToolsTableBody.insertRow();
            row.innerHTML = `
                <td>${tool.id}</td>
                <td>${tool.name}</td>
                <td>${tool.description}</td>
                <td><span class="status-badge status-${tool.status.toLowerCase()}">${tool.status}</span></td>
                <td class="actions">
                    <button class="btn-icon edit-group-tool-btn" data-id="${tool.id}"><i class="fas fa-edit"></i></button>
                    <button class="btn-icon delete-group-tool-btn" data-id="${tool.id}"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;
        });
        attachActionListeners('groupTool');
    }

    function renderThemeManagementTable() {
        themesTableBody.innerHTML = '';
        themes.forEach(theme => {
            const row = themesTableBody.insertRow();
            row.innerHTML = `
                <td>${theme.id}</td>
                <td>${theme.name}</td>
                <td>${theme.author}</td>
                <td>${theme.version}</td>
                <td><span class="status-badge status-${theme.status.toLowerCase()}">${theme.status}</span></td>
                <td class="actions">
                    <button class="btn-icon edit-theme-btn" data-id="${theme.id}"><i class="fas fa-edit"></i></button>
                    <button class="btn-icon delete-theme-btn" data-id="${theme.id}"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;
        });
        attachActionListeners('theme');
    }

    function renderBannerManagementTable() {
        bannersTableBody.innerHTML = '';
        banners.forEach(banner => {
            const row = bannersTableBody.insertRow();
            row.innerHTML = `
                <td>${banner.id}</td>
                <td>${banner.name}</td>
                <td><img src="${banner.imageUrl}" alt="${banner.name}" style="width: 100px; height: auto;"></td>
                <td><span class="status-badge status-${banner.isActive ? 'active' : 'inactive'}">${banner.isActive ? 'Ativo' : 'Inativo'}</span></td>
                <td class="actions">
                    <button class="btn-icon edit-banner-btn" data-id="${banner.id}"><i class="fas fa-edit"></i></button>
                    <button class="btn-icon delete-banner-btn" data-id="${banner.id}"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;
        });
        attachActionListeners('banner');
    }

    function renderLogsTable() {
        logsTableBody.innerHTML = '';
        logs.forEach(log => {
            const row = logsTableBody.insertRow();
            row.innerHTML = `
                <td>${log.id}</td>
                <td>${log.datetime}</td>
                <td><span class="status-badge status-${log.level.toLowerCase()}">${log.level}</span></td>
                <td>${log.event}</td>
                <td>${log.user}</td>
                <td>${log.ip}</td>
            `;
        });
    }

    // --- Funções de Paginação ---
    function updatePagination(currentPage, totalItems, itemsPerPage, currentPageSpan, totalPagesSpan) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        currentPageSpan.textContent = currentPage;
        totalPagesSpan.textContent = totalPages;

        if (currentPage === 1) {
            prevUsersBtn.disabled = true;
        } else {
            prevUsersBtn.disabled = false;
        }

        if (currentPage === totalPages) {
            nextUsersBtn.disabled = true;
        } else {
            nextUsersBtn.disabled = false;
        }
    }

    prevUsersBtn.addEventListener('click', () => {
        if (currentPageUsers > 1) {
            currentPageUsers--;
            renderUsersTable(currentPageUsers);
        }
    });

    nextUsersBtn.addEventListener('click', () => {
        const totalPages = Math.ceil(users.length / usersPerPage);
        if (currentPageUsers < totalPages) {
            currentPageUsers++;
            renderUsersTable(currentPageUsers);
        }
    });

    prevPermissionsBtn.addEventListener('click', () => {
        if (currentPagePermissions > 1) {
            currentPagePermissions--;
            renderPermissionsTable(currentPagePermissions);
        }
    });

    nextPermissionsBtn.addEventListener('click', () => {
        const totalPages = Math.ceil(permissions.length / permissionsPerPage);
        if (currentPagePermissions < totalPages) {
            currentPagePermissions++;
            renderPermissionsTable(currentPagePermissions);
        }
    });

    prevSessionsBtn.addEventListener('click', () => {
        if (currentPageSessions > 1) {
            currentPageSessions--;
            renderSessionsTable(currentPageSessions);
        }
    });

    nextSessionsBtn.addEventListener('click', () => {
        const totalPages = Math.ceil(sessions.length / sessionsPerPage);
        if (currentPageSessions < totalPages) {
            currentPageSessions++;
            renderSessionsTable(currentPageSessions);
        }
    });

    // --- Ações de Tabela (Editar/Deletar/Adicionar) ---
    function attachActionListeners(type) {
        // Remove listeners antigos para evitar duplicação
        document.querySelectorAll(`.${type}-table-body .edit-${type}-btn`).forEach(btn => {
            btn.removeEventListener('click', handleEdit);
        });
        document.querySelectorAll(`.${type}-table-body .delete-${type}-btn`).forEach(btn => {
            btn.removeEventListener('click', handleDelete);
        });
        // Remove listeners específicos de cada tipo
        if (type === 'session') {
            document.querySelectorAll(`.terminate-session-btn`).forEach(btn => {
                btn.removeEventListener('click', handleTerminateSession);
            });
        }
        if (type === 'feedback') {
            document.querySelectorAll(`.view-feedback-btn`).forEach(btn => {
                btn.removeEventListener('click', handleViewFeedback);
            });
        }
        if (type === 'ticket') {
            document.querySelectorAll(`.view-ticket-btn`).forEach(btn => {
                btn.removeEventListener('click', handleViewTicket);
            });
            document.querySelectorAll(`.close-ticket-btn`).forEach(btn => {
                btn.removeEventListener('click', handleCloseTicket);
            });
        }
        if (type === 'bug') {
            document.querySelectorAll(`.view-bug-btn`).forEach(btn => {
                btn.removeEventListener('click', handleViewBug);
            });
            document.querySelectorAll(`.resolve-bug-btn`).forEach(btn => {
                btn.removeEventListener('click', handleResolveBug);
            });
        }
        if (type === 'bannedUser') {
            document.querySelectorAll(`.unban-user-btn`).forEach(btn => {
                btn.removeEventListener('click', handleUnbanUser);
            });
        }
        if (type === 'banner') {
            document.querySelectorAll(`.banners-table-body .edit-banner-btn`).forEach(btn => {
                btn.removeEventListener('click', handleEditBanner);
            });
            document.querySelectorAll(`.banners-table-body .delete-banner-btn`).forEach(btn => {
                btn.removeEventListener('click', handleDeleteBanner);
            });
        }
        if (type === 'theme') {
            document.querySelectorAll(`.themes-table-body .edit-theme-btn`).forEach(btn => {
                btn.removeEventListener('click', handleEditTheme);
            });
            document.querySelectorAll(`.themes-table-body .delete-theme-btn`).forEach(btn => {
                btn.removeEventListener('click', handleDeleteTheme);
            });
        }
        if (type === 'groupTool') {
            document.querySelectorAll(`.group-tools-table-body .edit-group-tool-btn`).forEach(btn => {
                btn.removeEventListener('click', handleEditGroupTool);
            });
            document.querySelectorAll(`.group-tools-table-body .delete-group-tool-btn`).forEach(btn => {
                btn.removeEventListener('click', handleDeleteGroupTool);
            });
        }


        // Adiciona novos listeners
        document.querySelectorAll(`.${type}-table-body .edit-${type}-btn`).forEach(btn => {
            btn.addEventListener('click', (e) => handleEdit(e, type));
        });
        document.querySelectorAll(`.${type}-table-body .delete-${type}-btn`).forEach(btn => {
            btn.addEventListener('click', (e) => handleDelete(e, type));
        });
        // Adiciona listeners específicos de cada tipo
        if (type === 'session') {
            document.querySelectorAll(`.terminate-session-btn`).forEach(btn => {
                btn.addEventListener('click', handleTerminateSession);
            });
        }
        if (type === 'feedback') {
            document.querySelectorAll(`.view-feedback-btn`).forEach(btn => {
                btn.addEventListener('click', handleViewFeedback);
            });
        }
        if (type === 'ticket') {
            document.querySelectorAll(`.view-ticket-btn`).forEach(btn => {
                btn.addEventListener('click', handleViewTicket);
            });
            document.querySelectorAll(`.close-ticket-btn`).forEach(btn => {
                btn.addEventListener('click', handleCloseTicket);
            });
        }
        if (type === 'bug') {
            document.querySelectorAll(`.view-bug-btn`).forEach(btn => {
                btn.addEventListener('click', handleViewBug);
            });
            document.querySelectorAll(`.resolve-bug-btn`).forEach(btn => {
                btn.addEventListener('click', handleResolveBug);
            });
        }
        if (type === 'bannedUser') {
            document.querySelectorAll(`.unban-user-btn`).forEach(btn => {
                btn.addEventListener('click', handleUnbanUser);
            });
        }
        if (type === 'groupTool') {
            document.querySelectorAll(`.group-tools-table-body .edit-group-tool-btn`).forEach(btn => {
                btn.addEventListener('click', (e) => handleEditGroupTool(e));
            });
            document.querySelectorAll(`.group-tools-table-body .delete-group-tool-btn`).forEach(btn => {
                btn.addEventListener('click', (e) => handleDeleteGroupTool(e));
            });
        }

    }

    function handleEdit(e, type) {
        const id = parseInt(e.currentTarget.dataset.id);
        let item;
        let formModalElement;
        let formTitleElement;
        let formElement;

        switch (type) {
            case 'user':
                item = users.find(u => u.id === id);
                formModalElement = userFormModal;
                formTitleElement = userFormTitle;
                formElement = userForm;
                userFormTitle.textContent = 'Editar Usuário';
                document.getElementById('user-id').value = item.id;
                document.getElementById('username').value = item.username;
                document.getElementById('user-email').value = item.email;
                document.getElementById('user-role').value = item.role;
                document.getElementById('user-status').value = item.status;
                break;
            case 'permission':
                item = permissions.find(p => p.id === id);
                formModalElement = permissionFormModal;
                formTitleElement = permissionFormTitle;
                formElement = permissionForm;
                permissionFormTitle.textContent = 'Editar Permissão';
                document.getElementById('permission-id').value = item.id;
                document.getElementById('permission-name').value = item.name;
                document.getElementById('permission-description').value = item.description;
                break;
            case 'developer':
                item = developers.find(d => d.id === id);
                formModalElement = developerFormModal;
                formTitleElement = developerFormTitle;
                formElement = developerForm;
                developerFormTitle.textContent = 'Editar Desenvolvedor';
                document.getElementById('developer-id').value = item.id;
                document.getElementById('developer-name').value = item.name;
                document.getElementById('developer-team').value = item.team;
                document.getElementById('developer-role').value = item.role;
                document.getElementById('developer-email').value = item.email;
                break;
            case 'product':
                item = products.find(p => p.id === id);
                formModalElement = productFormModal;
                formTitleElement = productFormTitle;
                formElement = productForm;
                productFormTitle.textContent = 'Editar Produto';
                document.getElementById('product-id').value = item.id;
                document.getElementById('product-name').value = item.name;
                document.getElementById('product-price').value = item.price;
                document.getElementById('product-stock').value = item.stock;
                document.getElementById('product-status').value = item.status;
                break;
            default:
                console.warn('Tipo de edição não reconhecido:', type);
                return;
        }
        showModal(formModalElement);
    }

    function handleDelete(e, type) {
        const id = parseInt(e.currentTarget.dataset.id);
        if (!confirm(`Tem certeza que deseja deletar este ${type} (ID: ${id})?`)) {
            return;
        }

        switch (type) {
            case 'user':
                users = users.filter(u => u.id !== id);
                renderUsersTable(currentPageUsers);
                break;
            case 'permission':
                permissions = permissions.filter(p => p.id !== id);
                renderPermissionsTable(currentPagePermissions);
                break;
            case 'developer':
                developers = developers.filter(d => d.id !== id);
                renderDevelopersTable();
                break;
            case 'product':
                products = products.filter(p => p.id !== id);
                renderProductsTable();
                break;
            default:
                console.warn('Tipo de exclusão não reconhecido:', type);
        }
        alert(`${type.charAt(0).toUpperCase() + type.slice(1)} com ID ${id} deletado com sucesso!`);
    }

    function handleTerminateSession(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        if (confirm(`Tem certeza que deseja encerrar a sessão (ID: ${id})?`)) {
            sessions = sessions.filter(s => s.id !== id);
            renderSessionsTable(currentPageSessions);
            alert(`Sessão ${id} encerrada.`);
        }
    }

    function handleViewFeedback(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        alert(`Visualizando feedback com ID: ${id}. (Funcionalidade completa em breve)`);
    }

    function handleViewTicket(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        alert(`Visualizando ticket com ID: ${id}. (Funcionalidade completa em breve)`);
    }

    function handleCloseTicket(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        if (confirm(`Marcar ticket ${id} como resolvido?`)) {
            // Lógica para atualizar o status do ticket
            alert(`Ticket ${id} marcado como resolvido.`);
            renderTicketsTable(); // Re-renderiza para atualizar o status visualmente
        }
    }

    function handleViewBug(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        alert(`Visualizando relatório de bug com ID: ${id}. (Funcionalidade completa em breve)`);
    }

    function handleResolveBug(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        if (confirm(`Marcar bug ${id} como resolvido?`)) {
            // Lógica para atualizar o status do bug
            alert(`Bug ${id} marcado como resolvido.`);
            renderBugReportsTable(); // Re-renderiza para atualizar o status visualmente
        }
    }

    function handleUnbanUser(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        if (confirm(`Tem certeza que deseja desbanir o usuário (ID: ${id})?`)) {
            bannedUsers = bannedUsers.filter(user => user.id !== id);
            renderBannedUsersTable();
            alert(`Usuário com ID ${id} desbanido.`);
        }
    }

    function handleEditBan(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        const ban = bannedUsers.find(b => b.id === id);
        if (ban) {
            banFormTitle.textContent = 'Editar Banimento';
            document.getElementById('ban-id').value = ban.id;
            document.getElementById('banned-username').value = ban.username;
            document.getElementById('ban-reason').value = ban.reason;
            document.getElementById('ban-expiration-date').value = ban.expiration_date === 'Nunca' ? '' : ban.expiration_date;
            showModal(banFormModal);
        }
    }

    function handleDeleteBan(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        if (confirm(`Tem certeza que deseja remover este banimento (ID: ${id})?`)) {
            bannedUsers = bannedUsers.filter(b => b.id !== id);
            renderBannedUsersTable();
            alert(`Banimento com ID ${id} removido.`);
        }
    }

    function handleEditGroupTool(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        const tool = groupTools.find(t => t.id === id);
        if (tool) {
            groupToolFormTitle.textContent = 'Editar Ferramenta de Grupo';
            document.getElementById('group-tool-id').value = tool.id;
            document.getElementById('group-tool-name').value = tool.name;
            document.getElementById('group-tool-description').value = tool.description;
            document.getElementById('group-tool-status').value = tool.status;
            showModal(groupToolFormModal);
        }
    }

    function handleDeleteGroupTool(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        if (confirm(`Tem certeza que deseja remover esta ferramenta de grupo (ID: ${id})?`)) {
            groupTools = groupTools.filter(t => t.id !== id);
            renderGroupToolsTable();
            alert(`Ferramenta de grupo com ID ${id} removida.`);
        }
    }

    function handleEditTheme(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        const theme = themes.find(t => t.id === id);
        if (theme) {
            themeFormTitle.textContent = 'Editar Tema';
            document.getElementById('theme-id').value = theme.id;
            document.getElementById('theme-name').value = theme.name;
            document.getElementById('theme-author').value = theme.author;
            document.getElementById('theme-version').value = theme.version;
            document.getElementById('theme-status').value = theme.status;
            showModal(themeFormModal);
        }
    }

    function handleDeleteTheme(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        if (confirm(`Tem certeza que deseja remover este tema (ID: ${id})?`)) {
            themes = themes.filter(t => t.id !== id);
            renderThemeManagementTable();
            alert(`Tema com ID ${id} removido.`);
        }
    }

    function handleEditBanner(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        const banner = banners.find(b => b.id === id);
        if (banner) {
            bannerFormTitle.textContent = 'Editar Banner';
            document.getElementById('banner-id').value = banner.id;
            document.getElementById('banner-name').value = banner.name;
            document.getElementById('banner-image-url').value = banner.imageUrl;
            document.getElementById('banner-is-active').checked = banner.isActive;
            showModal(bannerFormModal);
        }
    }

    function handleDeleteBanner(e) {
        const id = parseInt(e.currentTarget.dataset.id);
        if (confirm(`Tem certeza que deseja remover este banner (ID: ${id})?`)) {
            banners = banners.filter(b => b.id !== id);
            renderBannerManagementTable();
            alert(`Banner com ID ${id} removido.`);
        }
    }

    // --- Lógica de Formulários (Adicionar/Editar) ---

    // User Form
    addUserBtn.addEventListener('click', () => {
        userFormTitle.textContent = 'Adicionar Novo Usuário';
        userForm.reset(); // Limpa o formulário
        document.getElementById('user-id').value = ''; // Garante que o ID esteja vazio para nova adição
        showModal(userFormModal);
    });

    userForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('user-id').value;
        const username = document.getElementById('username').value;
        const email = document.getElementById('user-email').value;
        const role = document.getElementById('user-role').value;
        const status = document.getElementById('user-status').value;

        if (id) {
            // Edição
            const userIndex = users.findIndex(u => u.id === parseInt(id));
            if (userIndex !== -1) {
                users[userIndex] = { ...users[userIndex], username, email, role, status };
                alert('Usuário atualizado com sucesso!');
            }
        } else {
            // Adição
            const newId = users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1;
            users.push({ id: newId, username, email, role, status });
            alert('Usuário adicionado com sucesso!');
        }
        renderUsersTable(currentPageUsers);
        hideModal();
    });

    // Permission Form
    addPermissionBtn.addEventListener('click', () => {
        permissionFormTitle.textContent = 'Adicionar Nova Permissão';
        permissionForm.reset();
        document.getElementById('permission-id').value = '';
        showModal(permissionFormModal);
    });

    permissionForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('permission-id').value;
        const name = document.getElementById('permission-name').value;
        const description = document.getElementById('permission-description').value;

        if (id) {
            const permIndex = permissions.findIndex(p => p.id === parseInt(id));
            if (permIndex !== -1) {
                permissions[permIndex] = { ...permissions[permIndex], name, description };
                alert('Permissão atualizada com sucesso!');
            }
        } else {
            const newId = permissions.length > 0 ? Math.max(...permissions.map(p => p.id)) + 1 : 1;
            permissions.push({ id: newId, name, description });
            alert('Permissão adicionada com sucesso!');
        }
        renderPermissionsTable(currentPagePermissions);
        hideModal();
    });

    // Game Release Form
    addGameReleaseBtn.addEventListener('click', () => {
        gameReleaseFormTitle.textContent = 'Adicionar Novo Lançamento de Jogo';
        gameReleaseForm.reset();
        document.getElementById('game-release-id').value = '';
        showModal(gameReleaseFormModal);
    });

    gameReleaseForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('game-release-id').value;
        const name = document.getElementById('game-name').value;
        const dateString = document.getElementById('release-date').value;
        const platform = document.getElementById('game-platform').value;

        const date = new Date(dateString + 'T00:00:00'); // Garante que a data seja um objeto Date

        if (id) {
            const releaseIndex = gameReleases.findIndex(r => r.id === parseInt(id));
            if (releaseIndex !== -1) {
                gameReleases[releaseIndex] = { ...gameReleases[releaseIndex], name, date, platform };
                alert('Lançamento de jogo atualizado com sucesso!');
            }
        } else {
            const newId = gameReleases.length > 0 ? Math.max(...gameReleases.map(r => r.id)) + 1 : 1;
            gameReleases.push({ id: newId, name, date, platform });
            alert('Lançamento de jogo adicionado com sucesso!');
        }
        renderCalendar();
        hideModal();
    });

    // Developer Form
    addDeveloperBtn.addEventListener('click', () => {
        developerFormTitle.textContent = 'Adicionar Novo Desenvolvedor';
        developerForm.reset();
        document.getElementById('developer-id').value = '';
        showModal(developerFormModal);
    });

    developerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('developer-id').value;
        const name = document.getElementById('developer-name').value;
        const team = document.getElementById('developer-team').value;
        const role = document.getElementById('developer-role').value;
        const email = document.getElementById('developer-email').value;

        if (id) {
            const devIndex = developers.findIndex(d => d.id === parseInt(id));
            if (devIndex !== -1) {
                developers[devIndex] = { ...developers[devIndex], name, team, role, email };
                alert('Desenvolvedor atualizado com sucesso!');
            }
        } else {
            const newId = developers.length > 0 ? Math.max(...developers.map(d => d.id)) + 1 : 1;
            developers.push({ id: newId, name, team, role, email });
            alert('Desenvolvedor adicionado com sucesso!');
        }
        renderDevelopersTable();
        hideModal();
    });

    // Product Form
    addProductBtn.addEventListener('click', () => {
        productFormTitle.textContent = 'Adicionar Novo Produto';
        productForm.reset();
        document.getElementById('product-id').value = '';
        showModal(productFormModal);
    });

    productForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('product-id').value;
        const name = document.getElementById('product-name').value;
        const price = parseFloat(document.getElementById('product-price').value);
        const stock = parseInt(document.getElementById('product-stock').value);
        const status = document.getElementById('product-status').value;

        if (id) {
            const productIndex = products.findIndex(p => p.id === parseInt(id));
            if (productIndex !== -1) {
                products[productIndex] = { ...products[productIndex], name, price, stock, status };
                alert('Produto atualizado com sucesso!');
            }
        } else {
            const newId = products.length > 0 ? Math.max(...products.map(p => p.id)) + 1 : 1;
            products.push({ id: newId, name, price, stock, status });
            alert('Produto adicionado com sucesso!');
        }
        renderProductsTable();
        hideModal();
    });

    // Ban Form
    addBanBtn.addEventListener('click', () => {
        banFormTitle.textContent = 'Adicionar Novo Banimento';
        banForm.reset();
        document.getElementById('ban-id').value = '';
        showModal(banFormModal);
    });

    banForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('ban-id').value;
        const username = document.getElementById('banned-username').value;
        const reason = document.getElementById('ban-reason').value;
        const expirationDate = document.getElementById('ban-expiration-date').value || 'Nunca';

        if (id) {
            const banIndex = bannedUsers.findIndex(b => b.id === parseInt(id));
            if (banIndex !== -1) {
                bannedUsers[banIndex] = { ...bannedUsers[banIndex], username, reason, expiration_date: expirationDate };
                alert('Banimento atualizado com sucesso!');
            }
        } else {
            const newId = bannedUsers.length > 0 ? Math.max(...bannedUsers.map(b => b.id)) + 1 : 1;
            bannedUsers.push({ id: newId, username, reason, expiration_date: expirationDate });
            alert('Banimento adicionado com sucesso!');
        }
        renderBannedUsersTable();
        hideModal();
    });

    // Group Tool Form
    addGroupToolBtn.addEventListener('click', () => {
        groupToolFormTitle.textContent = 'Adicionar Nova Ferramenta de Grupo';
        groupToolForm.reset();
        document.getElementById('group-tool-id').value = '';
        showModal(groupToolFormModal);
    });

    groupToolForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('group-tool-id').value;
        const name = document.getElementById('group-tool-name').value;
        const description = document.getElementById('group-tool-description').value;
        const status = document.getElementById('group-tool-status').value;

        if (id) {
            const toolIndex = groupTools.findIndex(t => t.id === parseInt(id));
            if (toolIndex !== -1) {
                groupTools[toolIndex] = { ...groupTools[toolIndex], name, description, status };
                alert('Ferramenta de grupo atualizada com sucesso!');
            }
        } else {
            const newId = groupTools.length > 0 ? Math.max(...groupTools.map(t => t.id)) + 1 : 1;
            groupTools.push({ id: newId, name, description, status });
            alert('Ferramenta de grupo adicionada com sucesso!');
        }
        renderGroupToolsTable();
        hideModal();
    });

    // Theme Form
    addThemeBtn.addEventListener('click', () => {
        themeFormTitle.textContent = 'Adicionar Novo Tema';
        themeForm.reset();
        document.getElementById('theme-id').value = '';
        showModal(themeFormModal);
    });

    themeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('theme-id').value;
        const name = document.getElementById('theme-name').value;
        const author = document.getElementById('theme-author').value;
        const version = document.getElementById('theme-version').value;
        const status = document.getElementById('theme-status').value;

        if (id) {
            const themeIndex = themes.findIndex(t => t.id === parseInt(id));
            if (themeIndex !== -1) {
                themes[themeIndex] = { ...themes[themeIndex], name, author, version, status };
                alert('Tema atualizado com sucesso!');
            }
        } else {
            const newId = themes.length > 0 ? Math.max(...themes.map(t => t.id)) + 1 : 1;
            themes.push({ id: newId, name, author, version, status });
            alert('Tema adicionado com sucesso!');
        }
        renderThemeManagementTable();
        hideModal();
    });

    // Banner Form
    addBannerBtn.addEventListener('click', () => {
        bannerFormTitle.textContent = 'Adicionar Novo Banner';
        bannerForm.reset();
        document.getElementById('banner-id').value = '';
        document.getElementById('banner-is-active').checked = false; // Default para falso
        showModal(bannerFormModal);
    });

    bannerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('banner-id').value;
        const name = document.getElementById('banner-name').value;
        const imageUrl = document.getElementById('banner-image-url').value;
        const isActive = document.getElementById('banner-is-active').checked;

        if (id) {
            const bannerIndex = banners.findIndex(b => b.id === parseInt(id));
            if (bannerIndex !== -1) {
                banners[bannerIndex] = { ...banners[bannerIndex], name, imageUrl, isActive };
                alert('Banner atualizado com sucesso!');
            }
        } else {
            const newId = banners.length > 0 ? Math.max(...banners.map(b => b.id)) + 1 : 1;
            banners.push({ id: newId, name, imageUrl, isActive });
            alert('Banner adicionado com sucesso!');
        }
        renderBannerManagementTable();
        hideModal();
    });


    // --- Lógica do Calendário de Lançamento de Jogos ---
    function renderCalendar() {
        const year = currentCalendarDate.getFullYear();
        const month = currentCalendarDate.getMonth(); // 0-indexed

        currentMonthYearHeader.textContent = currentCalendarDate.toLocaleString('pt-BR', { month: 'long', year: 'numeric' });

        gameReleaseCalendar.innerHTML = '';

        // Add day names
        const dayNames = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        dayNames.forEach(dayName => {
            const div = document.createElement('div');
            div.classList.add('day-name');
            div.textContent = dayName;
            gameReleaseCalendar.appendChild(div);
        });

        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);
        const daysInMonth = lastDayOfMonth.getDate();
        const startDay = firstDayOfMonth.getDay(); // 0 for Sunday, 1 for Monday, etc.

        // Fill leading empty days
        for (let i = 0; i < startDay; i++) {
            const div = document.createElement('div');
            div.classList.add('calendar-day', 'empty');
            gameReleaseCalendar.appendChild(div);
        }

        // Fill days of the month
        for (let i = 1; i <= daysInMonth; i++) {
            const div = document.createElement('div');
            div.classList.add('calendar-day');
            div.dataset.date = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`; // YYYY-MM-DD
            if (new Date(year, month, i).toDateString() === new Date().toDateString()) {
                div.classList.add('current-day'); // Marca o dia atual
            }

            const dayNumber = document.createElement('span');
            dayNumber.classList.add('day-number');
            dayNumber.textContent = i;
            div.appendChild(dayNumber);

            // Add game releases for this day
            const releasesOnThisDay = gameReleases.filter(release =>
                release.date.getFullYear() === year &&
                release.date.getMonth() === month &&
                release.date.getDate() === i
            );

            releasesOnThisDay.forEach(release => {
                const releaseItem = document.createElement('div');
                releaseItem.classList.add('release-item');
                releaseItem.textContent = release.name;
                releaseItem.title = `${release.name} (${release.platform})`;
                div.appendChild(releaseItem);
            });

            // Event listener to add new game release on clicking a day
            div.addEventListener('click', (e) => {
                if (!e.target.classList.contains('release-item') && !e.target.classList.contains('day-number')) { // Evita abrir o modal se clicar no item de lançamento
                    const clickedDate = div.dataset.date;
                    gameReleaseFormTitle.textContent = `Adicionar Lançamento para ${clickedDate}`;
                    gameReleaseForm.reset();
                    document.getElementById('game-release-id').value = '';
                    document.getElementById('release-date').value = clickedDate; // Preenche a data clicada
                    showModal(gameReleaseFormModal);
                }
            });

            gameReleaseCalendar.appendChild(div);
        }
    }

    prevMonthBtn.addEventListener('click', () => {
        currentCalendarDate.setMonth(currentCalendarDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentCalendarDate.setMonth(currentCalendarDate.getMonth() + 1);
        renderCalendar();
    });


    // --- Lógica do Dashboard ---
    function updateDashboardMetrics() {
        totalUsersEl.textContent = users.length;
        // Simulação de receita e despesas
        const totalRevenue = 150000 + Math.floor(Math.random() * 20000) - 10000;
        const totalExpenses = 50000 + Math.floor(Math.random() * 10000) - 5000;
        totalRevenueEl.textContent = `R$ ${totalRevenue.toLocaleString('pt-BR')}`;
        financeTotalRevenueEl.textContent = `R$ ${totalRevenue.toLocaleString('pt-BR')}`;
        financeTotalExpensesEl.textContent = `R$ ${totalExpenses.toLocaleString('pt-BR')}`;
        financeNetProfitEl.textContent = `R$ ${(totalRevenue - totalExpenses).toLocaleString('pt-BR')}`;
        gamesLaunchedEl.textContent = gameReleases.length;
        bugsReportedEl.textContent = bugReportsTableBody.rows.length; // Conta bugs reportados
    }

    function createDashboardCharts() {
        // Dados de exemplo para os gráficos (em um cenário real, viriam de uma API)
        const userGrowthData = {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Novos Usuários',
                data: [100, 150, 120, 180, 200, 250],
                backgroundColor: 'rgba(123, 31, 162, 0.6)',
                borderColor: 'rgba(123, 31, 162, 1)',
                borderWidth: 1,
                fill: true,
                tension: 0.3
            }]
        };

        const salesByGameData = {
            labels: ['Jogo A', 'Jogo B', 'Jogo C', 'Jogo D'],
            datasets: [{
                label: 'Vendas',
                data: [5000, 7500, 3000, 6000],
                backgroundColor: ['#1976D2', '#7B1FA2', '#4CAF50', '#FFC107'],
                borderColor: ['#1976D2', '#7B1FA2', '#4CAF50', '#FFC107'],
                borderWidth: 1
            }]
        };

        const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
        if (userGrowthChartInstance) {
            userGrowthChartInstance.destroy();
        }
        userGrowthChartInstance = new Chart(userGrowthCtx, {
            type: 'line',
            data: userGrowthData,
            options: {
                responsive: true,
                maintainAspectRatio: false, // Permitir que o gráfico se ajuste à altura do container
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });

        const salesByGameCtx = document.getElementById('salesByGameChart').getContext('2d');
        if (salesByGameChartInstance) {
            salesByGameChartInstance.destroy();
        }
        salesByGameChartInstance = new Chart(salesByGameCtx, {
            type: 'bar',
            data: salesByGameData,
            options: {
                responsive: true,
                maintainAspectRatio: false, // Permitir que o gráfico se ajuste à altura do container
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });
    }

    // Função para atualizar os dados do gráfico de acordo com os filtros
    function updateDashboardCharts() {
        const timePeriod = timePeriodSelect.value;
        const gameFilter = gameFilterSelect.value;

        // Simulação de dados filtrados (em um cenário real, você faria uma requisição AJAX)
        let newLabels, newUserData, newSalesData;

        if (timePeriod === 'day') {
            newLabels = ['01/Jun', '02/Jun', '03/Jun', '04/Jun', '05/Jun', '06/Jun', '07/Jun'];
            newUserData = [20, 30, 25, 35, 40, 45, 50].map(d => d + Math.floor(Math.random() * 10));
            newSalesData = [1000, 1200, 900, 1500, 1800, 1600, 2000].map(d => d * (gameFilter === 'all' ? 1 : 0.8) + Math.floor(Math.random() * 200));
        } else if (timePeriod === 'year') {
            newLabels = ['2022', '2023', '2024', '2025'];
            newUserData = [1000, 1500, 1800, 2200].map(d => d + Math.floor(Math.random() * 200));
            newSalesData = [50000, 75000, 90000, 110000].map(d => d * (gameFilter === 'all' ? 1 : 0.8) + Math.floor(Math.random() * 5000));
        } else { // month (default)
            newLabels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'];
            newUserData = [100, 150, 120, 180, 200, 250].map(d => d + Math.floor(Math.random() * 50));
            newSalesData = [5000, 7500, 3000, 6000, 8000, 9500].map(d => d * (gameFilter === 'all' ? 1 : 0.8) + Math.floor(Math.random() * 1000));
        }

        userGrowthChartInstance.data.labels = newLabels;
        userGrowthChartInstance.data.datasets[0].data = newUserData;
        userGrowthChartInstance.update();

        salesByGameChartInstance.data.datasets[0].data = newSalesData;
        // Se o filtro de jogo não for "todos", ajuste os labels do sales by game
        if (gameFilter !== 'all') {
            salesByGameChartInstance.data.labels = [gameFilter.replace('game-', 'Jogo ').toUpperCase()];
        } else {
            salesByGameChartInstance.data.labels = ['Jogo A', 'Jogo B', 'Jogo C', 'Jogo D'];
        }
        salesByGameChartInstance.update();

        alert(`Filtros aplicados: Período: ${timePeriod}, Jogo: ${gameFilter}`);
    }

    applyFiltersBtn.addEventListener('click', updateDashboardCharts);

    // --- Lógica de Gráficos Financeiros ---
    function createFinanceCharts() {
        const revenueData = {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Receita',
                data: [30000, 35000, 28000, 40000, 45000, 50000],
                backgroundColor: 'rgba(76, 175, 80, 0.6)', // Green
                borderColor: 'rgba(76, 175, 80, 1)',
                borderWidth: 1,
                fill: true,
                tension: 0.3
            }]
        };

        const expensesData = {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Despesas',
                data: [10000, 12000, 9000, 15000, 13000, 16000],
                backgroundColor: 'rgba(244, 67, 54, 0.6)', // Red
                borderColor: 'rgba(244, 67, 54, 1)',
                borderWidth: 1,
                fill: true,
                tension: 0.3
            }]
        };

        const revenueCtx = document.getElementById('revenueOverTimeChart').getContext('2d');
        if (revenueOverTimeChartInstance) {
            revenueOverTimeChartInstance.destroy();
        }
        revenueOverTimeChartInstance = new Chart(revenueCtx, {
            type: 'line',
            data: revenueData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });

        const expensesCtx = document.getElementById('expensesOverTimeChart').getContext('2d');
        if (expensesOverTimeChartInstance) {
            expensesOverTimeChartInstance.destroy();
        }
        expensesOverTimeChartInstance = new Chart(expensesCtx, {
            type: 'line',
            data: expensesData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });
    }

    // --- Lógica de Configurações ---
    function initGeneralSettings() {
        // Recuperar e preencher valores (simulado)
        document.getElementById('site-name').value = 'Cypher Corporation';
        document.getElementById('admin-email').value = 'admin@cyphercorp.com';
        document.getElementById('timezone').value = 'America/Sao_Paulo';

        saveGeneralSettingsBtn.addEventListener('click', () => {
            const siteName = document.getElementById('site-name').value;
            const adminEmail = document.getElementById('admin-email').value;
            const timezone = document.getElementById('timezone').value;
            alert(`Configurações Gerais Salvas:\nNome do Site: ${siteName}\nEmail: ${adminEmail}\nFuso Horário: ${timezone}`);
        });
    }

    function initNotificationSettings() {
        // Recuperar e preencher valores (simulado)
        document.getElementById('email-notifications').checked = true;
        document.getElementById('sms-notifications').checked = false;

        saveNotificationSettingsBtn.addEventListener('click', () => {
            const emailNotif = document.getElementById('email-notifications').checked;
            const smsNotif = document.getElementById('sms-notifications').checked;
            alert(`Configurações de Notificação Salvas:\nEmail: ${emailNotif ? 'Ativado' : 'Desativado'}\nSMS: ${smsNotif ? 'Ativado' : 'Desativado'}`);
        });
    }

    function initSecuritySettings() {
        // Recuperar e preencher valores (simulado)
        document.getElementById('two-factor-auth').checked = false;

        changePasswordBtn.addEventListener('click', () => {
            alert('Funcionalidade de Alterar Senha será implementada.');
        });

        logoutAllDevicesBtn.addEventListener('click', () => {
            if (confirm('Tem certeza que deseja sair de todos os dispositivos? Isso encerrará todas as sessões ativas.')) {
                alert('Saindo de todos os dispositivos...');
                // Implementar lógica de logout forçado de todas as sessões
            }
        });
    }

    function initAppearanceSettings() {
        // Recuperar e preencher valores (simulado)
        document.getElementById('theme-selector').value = 'dark';

        saveAppearanceSettingsBtn.addEventListener('click', () => {
            const theme = document.getElementById('theme-selector').value;
            alert(`Configurações de Aparência Salvas:\nTema: ${theme}`);
            // Lógica para aplicar o tema (ex: adicionar classe ao body)
            document.body.className = theme + '-theme'; // Exemplo
        });
    }

    function initIntegrationsSettings() {
        manageIntegrationsBtn.addEventListener('click', () => {
            alert('Funcionalidade de Gerenciar Integrações será implementada.');
        });
    }


    // --- Lógica de Segurança e Logs ---
    exportLogsBtn.addEventListener('click', () => {
        // Simulação de exportação de logs
        const logContent = logs.map(log =>
            `ID: ${log.id}, Data: ${log.datetime}, Nível: ${log.level}, Evento: ${log.event}, Usuário: ${log.user}, IP: ${log.ip}`
        ).join('\n');

        const blob = new Blob([logContent], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `cypher_corp_logs_${new Date().toISOString().slice(0, 10)}.txt`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        alert('Logs exportados com sucesso!');
    });


    // --- Inicialização ---
    (function() {
        // Define a seção Dashboard como ativa por padrão
        activateSection('dashboard');
        updateDashboardMetrics();
        createDashboardCharts(); // Cria os gráficos inicialmente

        // Inicializa as configurações
        initGeneralSettings();
        initNotificationSettings();
        initSecuritySettings();
        initAppearanceSettings(); // Nova função para aparêcia
        initIntegrationsSettings(); // Nova função para integração


        // Renderizar todas as tabelas e elementos que precisam ser populados inicialmente
        // As funções de renderização já chamam as funções attachActionListeners
        // e são chamadas novamente pela activateSection quando a aba é selecionada.
        // Chamadas iniciais para garantir que os dados estejam visíveis na primeira carga.
        renderUsersTable(currentPageUsers);
        renderPermissionsTable(currentPagePermissions);
        renderSessionsTable(currentPageSessions);
        renderCalendar(); // Games calendar
        createFinanceCharts(); // Finance charts
        renderDevelopersTable(); // Developers table
        renderFeedbackTable(); // Feedback table
        renderTicketsTable(); // Support tickets
        renderBugReportsTable(); // Bug reports
        renderProductsTable(); // Shop products
        renderBannedUsersTable(); // Community banned users
        renderGroupToolsTable(); // Community group tools
        renderThemeManagementTable(); // Customization themes
        renderBannerManagementTable(); // Customization banners
        renderLogsTable(); // Security logs

        // Initial update of top bar notification badge
        updateNotificationBadge();
    })();
});

// Mock function for notifications (real-world would fetch from server)
function updateNotificationBadge() {
    const notificationBadge = document.querySelector('.notification-badge');
    // Simulate fetching notifications
    const newNotifications = Math.floor(Math.random() * 5); // 0-4 new notifications
    notificationBadge.textContent = newNotifications;
    if (newNotifications > 0) {
        notificationBadge.style.display = 'block';
    } else {
        notificationBadge.style.display = 'none';
    }
}