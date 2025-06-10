-- DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do usuário.',
    `username` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Nome de usuário único para login.',
    `email` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Endereço de email único do usuário. Usado para comunicação e recuperação de conta.',
    `password_hash` VARCHAR(255) NOT NULL COMMENT 'Hash seguro da senha do usuário (nunca armazenar senha em texto puro!).',
    `role` ENUM('admin', 'editor', 'moderator', 'user', 'guest') DEFAULT 'user' COMMENT 'Papel do usuário para controle de acesso baseado em função (RBAC).',
    `status` ENUM('active', 'inactive', 'suspended', 'banned', 'pending_verification') DEFAULT 'pending_verification' COMMENT 'Status atual da conta do usuário.',
    `profile_picture_url` VARCHAR(2048) NULL COMMENT 'URL para a imagem de perfil do usuário.',
    `bio` TEXT NULL COMMENT 'Pequena biografia ou descrição pessoal do usuário.',
    `last_login_at` DATETIME NULL COMMENT 'Timestamp do último login bem-sucedido.',
    `last_login_ip` VARCHAR(45) NULL COMMENT 'Endereço IP do último login (suporta IPv4 e IPv6).',
    `email_verified_at` DATETIME NULL COMMENT 'Timestamp quando o email do usuário foi verificado.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do registro do usuário.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do registro do usuário.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabela principal para gerenciar usuários do sistema.';

-- Índices para otimização de consultas e unicidade
CREATE INDEX idx_users_email ON `users`(`email`);
CREATE INDEX idx_users_status ON `users`(`status`);
CREATE INDEX idx_users_role ON `users`(`role`);
CREATE INDEX idx_users_last_login_at ON `users`(`last_login_at`);


-- --------------------------------------------------------
-- Tabela: permissions
-- Descrição: Define permissões granulares que podem ser atribuídas a diferentes papéis.
-- Permite um controle de acesso mais flexível e escalável que apenas os papéis fixos.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único da permissão.',
    `name` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Nome único da permissão (ex: create_user, edit_product, view_finances).',
    `description` TEXT NULL COMMENT 'Descrição detalhada do que a permissão permite.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação da permissão.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Gerencia permissões granulares no sistema.';


-- --------------------------------------------------------
-- Tabela: role_permissions
-- Descrição: Tabela de junção (many-to-many) para definir quais permissões cada papel possui.
-- Implementa o conceito de RBAC (Role-Based Access Control).
-- --------------------------------------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
    `role` ENUM('admin', 'editor', 'moderator', 'user', 'guest') NOT NULL COMMENT 'Papel do usuário.',
    `permission_id` INT NOT NULL COMMENT 'ID da permissão associada.',
    PRIMARY KEY (`role`, `permission_id`) COMMENT 'Chave primária composta para garantir unicidade da associação.',
    FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE COMMENT 'Permissões são removidas se a permissão em si for excluída.',
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Mapeia permissões a papéis de usuário.';


-- --------------------------------------------------------
-- Tabela: sessions
-- Descrição: Armazena informações sobre as sessões ativas dos usuários.
-- Essencial para rastreamento de login, segurança (detecção de sessões ativas)
-- e funcionalidades como "lembrar-me".
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único da sessão.',
    `user_id` INT NOT NULL COMMENT 'ID do usuário associado à sessão.',
    `session_token` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Token de sessão único e seguro para autenticação de requisições.',
    `ip_address` VARCHAR(45) NULL COMMENT 'Endereço IP de onde a sessão foi iniciada.',
    `user_agent` VARCHAR(255) NULL COMMENT 'String User-Agent do navegador/dispositivo do usuário.',
    `expires_at` DATETIME NOT NULL COMMENT 'Timestamp de expiração da sessão. Sessões expiradas devem ser invalidadas.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação da sessão.',
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE COMMENT 'Sessão é automaticamente removida se o usuário for excluído.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Rastreia sessões ativas de usuários.';

-- Índices para buscas rápidas por user_id e para limpeza de sessões expiradas
CREATE INDEX idx_sessions_user_id ON `sessions`(`user_id`);
CREATE INDEX idx_sessions_expires_at ON `sessions`(`expires_at`);


-- --------------------------------------------------------
-- Tabela: products
-- Descrição: Gerencia os produtos e serviços disponíveis na loja da Cypher Corporation.
-- Inclui detalhes como preço, estoque e status de ativação.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do produto.',
    `name` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Nome único e descritivo do produto.',
    `sku` VARCHAR(100) NULL UNIQUE COMMENT 'Stock Keeping Unit: Código de identificação único do produto (opcional, mas bom para e-commerce).',
    `description` TEXT NULL COMMENT 'Descrição detalhada do produto, com informações relevantes.',
    `price` DECIMAL(10, 2) NOT NULL CHECK (`price` >= 0) COMMENT 'Preço de venda do produto. Deve ser não-negativo.',
    `cost_price` DECIMAL(10, 2) NULL CHECK (`cost_price` >= 0) COMMENT 'Custo de aquisição/produção do produto (para cálculo de margem).',
    `stock` INT NOT NULL DEFAULT 0 CHECK (`stock` >= 0) COMMENT 'Quantidade de estoque disponível. Não pode ser negativo.',
    `image_url` VARCHAR(2048) NULL COMMENT 'URL para a imagem principal do produto.',
    `category` VARCHAR(100) NULL COMMENT 'Categoria do produto (ex: Software, Hardware, Serviços, Jogos).',
    `brand` VARCHAR(100) NULL COMMENT 'Marca do produto, se aplicável.',
    `is_active` BOOLEAN DEFAULT TRUE COMMENT 'Indica se o produto está ativo e visível na loja.',
    `weight_kg` DECIMAL(8, 2) NULL CHECK (`weight_kg` >= 0) COMMENT 'Peso do produto em Kg (para cálculo de frete).',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do registro do produto.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do registro do produto.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Catálogo de produtos e serviços da loja.';

-- Índices para busca, filtragem e performance
CREATE INDEX idx_products_name ON `products`(`name`);
CREATE INDEX idx_products_category ON `products`(`category`);
CREATE INDEX idx_products_price ON `products`(`price`);
CREATE INDEX idx_products_is_active ON `products`(`is_active`);
CREATE INDEX idx_products_sku ON `products`(`sku`);


-- --------------------------------------------------------
-- Tabela: orders
-- Descrição: Armazena informações sobre os pedidos feitos pelos usuários.
-- Representa uma transação completa de compra.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do pedido.',
    `user_id` INT NOT NULL COMMENT 'ID do usuário que fez o pedido.',
    `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora em que o pedido foi feito.',
    `total_amount` DECIMAL(10, 2) NOT NULL CHECK (`total_amount` >= 0) COMMENT 'Valor total final do pedido, incluindo frete e impostos.',
    `status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded', 'on_hold') DEFAULT 'pending' COMMENT 'Status atual do pedido no processo de compra.',
    `shipping_address` VARCHAR(255) NULL COMMENT 'Endereço de entrega do pedido.',
    `billing_address` VARCHAR(255) NULL COMMENT 'Endereço de cobrança do pedido.',
    `payment_method` VARCHAR(100) NULL COMMENT 'Método de pagamento utilizado (ex: Credit Card, Bank Transfer, PayPal).',
    `tracking_number` VARCHAR(255) NULL UNIQUE COMMENT 'Número de rastreamento do envio, se aplicável.',
    `shipping_cost` DECIMAL(10, 2) DEFAULT 0.00 CHECK (`shipping_cost` >= 0) COMMENT 'Custo do frete para este pedido.',
    `discount_amount` DECIMAL(10, 2) DEFAULT 0.00 CHECK (`discount_amount` >= 0) COMMENT 'Valor total de descontos aplicados ao pedido.',
    `notes` TEXT NULL COMMENT 'Observações internas sobre o pedido.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do registro do pedido.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do registro do pedido.',
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE COMMENT 'Não permite deletar usuário que possui pedidos para manter histórico de transações.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Registra pedidos de produtos e serviços.';

-- Índices para otimização de consultas e relatórios
CREATE INDEX idx_orders_user_id ON `orders`(`user_id`);
CREATE INDEX idx_orders_order_date ON `orders`(`order_date`);
CREATE INDEX idx_orders_status ON `orders`(`status`);
CREATE INDEX idx_orders_tracking_number ON `orders`(`tracking_number`);


-- --------------------------------------------------------
-- Tabela: order_items
-- Descrição: Detalha os produtos incluídos em cada pedido,
-- registrando a quantidade e o preço no momento da compra.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
    `order_id` INT NOT NULL COMMENT 'ID do pedido ao qual o item pertence.',
    `product_id` INT NOT NULL COMMENT 'ID do produto incluído no pedido.',
    `quantity` INT NOT NULL CHECK (`quantity` > 0) COMMENT 'Quantidade do produto no pedido. Deve ser no mínimo 1.',
    `price_at_purchase` DECIMAL(10, 2) NOT NULL CHECK (`price_at_purchase` >= 0) COMMENT 'Preço unitário do produto no momento da compra. Importante para rastreamento de preços históricos.',
    `item_total` DECIMAL(10, 2) AS (`quantity` * `price_at_purchase`) STORED COMMENT 'Total do item (quantidade * preço), calculado e armazenado automaticamente.',
    PRIMARY KEY (`order_id`, `product_id`) COMMENT 'Chave primária composta para identificar um item único dentro de um pedido.',
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE ON UPDATE CASCADE COMMENT 'Item do pedido é removido se o pedido for excluído.',
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE COMMENT 'Não permite deletar produto se houver itens de pedido associados.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Detalhes dos itens dentro de cada pedido.';

-- Índices para buscas rápidas por produto dentro de um pedido
CREATE INDEX idx_order_items_product_id ON `order_items`(`product_id`);


-- --------------------------------------------------------
-- Tabela: game_calendar
-- Descrição: Gerencia eventos e lançamentos de jogos ou atualizações
-- relevantes para a comunidade.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `game_calendar`;
CREATE TABLE `game_calendar` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do evento no calendário.',
    `title` VARCHAR(255) NOT NULL COMMENT 'Título do evento ou lançamento (ex: Lançamento do CypherQuest).',
    `description` TEXT NULL COMMENT 'Descrição detalhada do evento.',
    `event_date_time` DATETIME NOT NULL COMMENT 'Data e hora exatas do evento.',
    `event_type` ENUM('release', 'update', 'tournament', 'maintenance', 'livestream', 'announcement', 'other') DEFAULT 'other' COMMENT 'Tipo de evento.',
    `game_name` VARCHAR(255) NULL COMMENT 'Nome do jogo associado, se o evento for específico de um jogo.',
    `location` VARCHAR(255) NULL COMMENT 'Local físico ou URL para o evento online.',
    `created_by_user_id` INT NULL COMMENT 'ID do usuário (admin/editor) que criou o evento.',
    `is_public` BOOLEAN DEFAULT TRUE COMMENT 'Indica se o evento é visível para o público geral.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do registro do evento.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do registro do evento.',
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE COMMENT 'Define o usuário que criou o evento.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Calendário de eventos e lançamentos de jogos/serviços.';

-- Índices para buscas por data, tipo e jogo
CREATE INDEX idx_calendar_event_date_time ON `game_calendar`(`event_date_time`);
CREATE INDEX idx_calendar_event_type ON `game_calendar`(`event_type`);
CREATE INDEX idx_calendar_game_name ON `game_calendar`(`game_name`);


-- --------------------------------------------------------
-- Tabela: developers
-- Descrição: Informações sobre os desenvolvedores ou equipes internas
-- que trabalham nos projetos da Cypher Corporation.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `developers`;
CREATE TABLE `developers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do desenvolvedor/equipe.',
    `name` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Nome completo do desenvolvedor ou nome da equipe.',
    `email` VARCHAR(255) NULL UNIQUE COMMENT 'Email de contato principal do desenvolvedor/equipe.',
    `specialty` VARCHAR(255) NULL COMMENT 'Área de especialização (ex: Frontend, Backend, Game Design, QA).',
    `hire_date` DATE NULL COMMENT 'Data de contratação ou início da parceria.',
    `status` ENUM('active', 'inactive', 'on_leave', 'contractor') DEFAULT 'active' COMMENT 'Status atual do desenvolvedor.',
    `phone_number` VARCHAR(20) NULL COMMENT 'Número de telefone de contato.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do registro.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do registro.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Gerencia informações sobre desenvolvedores e equipes internas.';

-- Índices para otimização de buscas por especialidade e status
CREATE INDEX idx_developers_specialty ON `developers`(`specialty`);
CREATE INDEX idx_developers_status ON `developers`(`status`);


-- --------------------------------------------------------
-- Tabela: projects
-- Descrição: Rastreia os projetos de desenvolvimento de software e jogos
-- nos quais os desenvolvedores estão trabalhando.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do projeto.',
    `project_name` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Nome único do projeto.',
    `description` TEXT NULL COMMENT 'Descrição detalhada do projeto, objetivos e escopo.',
    `start_date` DATE NULL COMMENT 'Data de início do projeto.',
    `end_date` DATE NULL COMMENT 'Data prevista/real de término do projeto.',
    `status` ENUM('planning', 'in_progress', 'testing', 'completed', 'cancelled', 'on_hold') DEFAULT 'planning' COMMENT 'Status atual do projeto no ciclo de vida.',
    `budget` DECIMAL(15, 2) NULL CHECK (`budget` >= 0) COMMENT 'Orçamento total alocado para o projeto.',
    `lead_developer_id` INT NULL COMMENT 'ID do desenvolvedor líder do projeto.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do registro do projeto.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do registro do projeto.',
    FOREIGN KEY (`lead_developer_id`) REFERENCES `developers`(`id`) ON DELETE SET NULL ON UPDATE CASCADE COMMENT 'Define o desenvolvedor líder do projeto.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Rastreia projetos de desenvolvimento de software.';

-- Índices para buscas por status e líder
CREATE INDEX idx_projects_status ON `projects`(`status`);
CREATE INDEX idx_projects_lead_developer_id ON `projects`(`lead_developer_id`);


-- --------------------------------------------------------
-- Tabela: project_developers
-- Descrição: Tabela de junção (many-to-many) para associar múltiplos
-- desenvolvedores a múltiplos projetos.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `project_developers`;
CREATE TABLE `project_developers` (
    `project_id` INT NOT NULL COMMENT 'ID do projeto.',
    `developer_id` INT NOT NULL COMMENT 'ID do desenvolvedor.',
    `role_in_project` VARCHAR(100) NULL COMMENT 'Papel específico do desenvolvedor no projeto (ex: Backend Dev, QA Tester, UI Designer).',
    `assigned_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Data em que o desenvolvedor foi atribuído ao projeto.',
    PRIMARY KEY (`project_id`, `developer_id`) COMMENT 'Chave primária composta.',
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE ON UPDATE CASCADE) COMMENT 'Associação removida se o projeto for excluído.',
    FOREIGN KEY (`developer_id`) REFERENCES `developers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE COMMENT 'Associação removida se o desenvolvedor for excluído.',
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Mapeia desenvolvedores a projetos em que estão trabalhando.';


-- --------------------------------------------------------
-- Tabela: feedback
-- Descrição: Armazena o feedback geral dos usuários sobre produtos,
-- serviços ou o sistema como um todo.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do feedback.',
    `user_id` INT NULL COMMENT 'ID do usuário que enviou o feedback (NULL se o feedback for anônimo).',
    `feedback_type` ENUM('bug', 'suggestion', 'complaint', 'praise', 'question', 'other') DEFAULT 'suggestion' COMMENT 'Tipo de feedback.',
    `subject` VARCHAR(255) NOT NULL COMMENT 'Assunto resumido do feedback.',
    `message` TEXT NOT NULL COMMENT 'Conteúdo completo e detalhado do feedback.',
    `rating` INT NULL CHECK (`rating` >= 1 AND `rating` <= 5) COMMENT 'Classificação de 1 a 5 estrelas, se aplicável.',
    `status` ENUM('new', 'in_review', 'action_taken', 'resolved', 'archived') DEFAULT 'new' COMMENT 'Status de processamento do feedback pela equipe.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp do envio do feedback.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do status do feedback.',
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE COMMENT 'Se o usuário for deletado, o feedback permanece, mas é desassociado.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Armazena feedback geral de usuários.';

-- Índices para otimização de busca, filtragem e relatórios de feedback
CREATE INDEX idx_feedback_user_id ON `feedback`(`user_id`);
CREATE INDEX idx_feedback_type ON `feedback`(`feedback_type`);
CREATE INDEX idx_feedback_status ON `feedback`(`status`);
CREATE INDEX idx_feedback_created_at ON `feedback`(`created_at`);


-- --------------------------------------------------------
-- Tabela: support_tickets
-- Descrição: Gerencia tickets de suporte abertos por usuários para problemas específicos
-- que requerem assistência individualizada.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `support_tickets`;
CREATE TABLE `support_tickets` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do ticket de suporte.',
    `user_id` INT NOT NULL COMMENT 'ID do usuário que abriu o ticket.',
    `subject` VARCHAR(255) NOT NULL COMMENT 'Assunto principal do ticket.',
    `description` TEXT NOT NULL COMMENT 'Descrição detalhada do problema ou questão.',
    `priority` ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium' COMMENT 'Prioridade do ticket para a equipe de suporte.',
    `status` ENUM('open', 'in_progress', 'pending_user', 'resolved', 'closed', 'escalated') DEFAULT 'open' COMMENT 'Status atual do ticket no fluxo de suporte.',
    `assigned_to_user_id` INT NULL COMMENT 'ID do usuário (admin/editor/suporte) atribuído para resolver o ticket.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do ticket.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do ticket.',
    `resolved_at` DATETIME NULL COMMENT 'Timestamp quando o ticket foi marcado como resolvido.',
    `resolution_notes` TEXT NULL COMMENT 'Notas sobre a resolução do ticket.',
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE COMMENT 'Ticket é removido se o usuário for excluído.',
    FOREIGN KEY (`assigned_to_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE COMMENT 'Atribuição é desfeita se o usuário atribuído for deletado.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Sistema de tickets de suporte ao cliente.';

-- Índices para otimização de consultas e relatórios de suporte
CREATE INDEX idx_tickets_user_id ON `support_tickets`(`user_id`);
CREATE INDEX idx_tickets_priority ON `support_tickets`(`priority`);
CREATE INDEX idx_tickets_status ON `support_tickets`(`status`);
CREATE INDEX idx_tickets_assigned_to ON `support_tickets`(`assigned_to_user_id`);
CREATE INDEX idx_tickets_created_at ON `support_tickets`(`created_at`);


-- --------------------------------------------------------
-- Tabela: bug_reports
-- Descrição: Armazena relatórios de bugs submetidos por usuários ou testadores.
-- Crucial para o processo de garantia de qualidade (QA).
-- --------------------------------------------------------
DROP TABLE IF EXISTS `bug_reports`;
CREATE TABLE `bug_reports` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do relatório de bug.',
    `user_id` INT NULL COMMENT 'ID do usuário que reportou o bug (NULL se for anônimo).',
    `title` VARCHAR(255) NOT NULL COMMENT 'Título resumido e descritivo do bug.',
    `description` TEXT NOT NULL COMMENT 'Descrição detalhada do bug, incluindo passos para reproduzir e comportamento esperado/observado.',
    `severity` ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium' COMMENT 'Severidade do bug (impacto no sistema).',
    `status` ENUM('new', 'triaged', 'in_progress', 'resolved', 'reopened', 'closed', 'wont_fix') DEFAULT 'new' COMMENT 'Status de resolução do bug no ciclo de desenvolvimento.',
    `component` VARCHAR(100) NULL COMMENT 'Componente ou módulo do sistema afetado pelo bug (ex: UI, Backend, Database, Payment Gateway).',
    `version_affected` VARCHAR(50) NULL COMMENT 'Versão do software onde o bug foi encontrado.',
    `assigned_to_user_id` INT NULL COMMENT 'ID do desenvolvedor/usuário atribuído para corrigir o bug.',
    `screenshot_url` VARCHAR(2048) NULL COMMENT 'URL para um print da tela ou vídeo que demonstre o bug.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp do relatório do bug.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do bug.',
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE COMMENT 'Se o usuário for deletado, o relatório permanece, mas é desassociado.',
    FOREIGN KEY (`assigned_to_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE COMMENT 'Atribuição é desfeita se o usuário atribuído for deletado.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Registra e gerencia relatórios de bugs.';

-- Índices para otimização de consultas e gerenciamento de bugs
CREATE INDEX idx_bugs_user_id ON `bug_reports`(`user_id`);
CREATE INDEX idx_bugs_severity ON `bug_reports`(`severity`);
CREATE INDEX idx_bugs_status ON `bug_reports`(`status`);
CREATE INDEX idx_bugs_component ON `bug_reports`(`component`);
CREATE INDEX idx_bugs_assigned_to ON `bug_reports`(`assigned_to_user_id`);


-- --------------------------------------------------------
-- Tabela: news_articles
-- Descrição: Gerencia artigos de notícias, comunicados e anúncios
-- publicados pela Cypher Corporation em seu site ou blog.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `news_articles`;
CREATE TABLE `news_articles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do artigo de notícia.',
    `title` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Título único e chamativo do artigo.',
    `slug` VARCHAR(255) NOT NULL UNIQUE COMMENT 'URL amigável (slug) para o artigo, otimizado para SEO.',
    `content` LONGTEXT NOT NULL COMMENT 'Conteúdo completo do artigo, pode ser HTML ou Markdown.',
    `author_user_id` INT NOT NULL COMMENT 'ID do usuário (admin/editor) que escreveu/publicou o artigo.',
    `category` VARCHAR(100) NULL COMMENT 'Categoria da notícia (ex: Empresa, Produtos, Eventos, Tecnologia).',
    `tags` VARCHAR(255) NULL COMMENT 'Tags relacionadas ao artigo, separadas por vírgula (para busca e categorização).',
    `thumbnail_url` VARCHAR(2048) NULL COMMENT 'URL para a imagem de miniatura ou destaque do artigo.',
    `is_published` BOOLEAN DEFAULT FALSE COMMENT 'Indica se o artigo está publicado e visível publicamente.',
    `published_at` DATETIME NULL COMMENT 'Timestamp da publicação do artigo. Pode ser agendado para o futuro.',
    `views_count` INT DEFAULT 0 CHECK (`views_count` >= 0) COMMENT 'Número de visualizações do artigo.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do rascunho do artigo.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do artigo.',
    FOREIGN KEY (`author_user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE COMMENT 'Não permite deletar autor com artigos publicados.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Gerencia artigos de notícias e anúncios corporativos.';

-- Índices para otimização de exibição de notícias
CREATE INDEX idx_news_category ON `news_articles`(`category`);
CREATE INDEX idx_news_published ON `news_articles`(`is_published`);
CREATE INDEX idx_news_published_at ON `news_articles`(`published_at`);
CREATE INDEX idx_news_author_user_id ON `news_articles`(`author_user_id`);


-- --------------------------------------------------------
-- Tabela: financial_transactions
-- Descrição: Rastreia todas as transações financeiras do sistema,
-- distinguindo entre receitas e despesas.
-- Essencial para relatórios financeiros e contabilidade.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `financial_transactions`;
CREATE TABLE `financial_transactions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único da transação financeira.',
    `transaction_type` ENUM('revenue', 'expense') NOT NULL COMMENT 'Tipo da transação: receita (entrada de dinheiro) ou despesa (saída de dinheiro).',
    `amount` DECIMAL(10, 2) NOT NULL COMMENT 'Valor monetário da transação. Pode ser positivo ou negativo dependendo do tipo.',
    `currency` VARCHAR(3) DEFAULT 'BRL' NOT NULL COMMENT 'Moeda da transação (ex: BRL, USD, EUR).',
    `description` VARCHAR(255) NULL COMMENT 'Descrição breve da transação para identificação rápida.',
    `transaction_date` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora exatas da transação.',
    `related_order_id` INT NULL COMMENT 'ID do pedido relacionado, se for uma receita de venda. Permite rastrear receita por pedido.',
    `category` VARCHAR(100) NULL COMMENT 'Categoria financeira da transação (ex: Vendas, Salários, Marketing, Aluguel, Software Licenses).',
    `recorded_by_user_id` INT NULL COMMENT 'ID do usuário (admin/finanças) que registrou a transação (se manual).',
    `notes` TEXT NULL COMMENT 'Notas adicionais sobre a transação.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do registro.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do registro.',
    FOREIGN KEY (`related_order_id`) REFERENCES `orders`(`id`) ON DELETE SET NULL ON UPDATE CASCADE COMMENT 'Se o pedido for deletado, a transação permanece, mas é desassociada.',
    FOREIGN KEY (`recorded_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE COMMENT 'Se o usuário que registrou for deletado, a transação permanece.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Registra todas as transações financeiras do sistema.';

-- Índices para otimização de relatórios financeiros
CREATE INDEX idx_finance_type ON `financial_transactions`(`transaction_type`);
CREATE INDEX idx_finance_date ON `financial_transactions`(`transaction_date`);
CREATE INDEX idx_finance_category ON `financial_transactions`(`category`);
CREATE INDEX idx_finance_related_order_id ON `financial_transactions`(`related_order_id`);


-- --------------------------------------------------------
-- Tabela: banned_users
-- Descrição: Rastreia usuários, IPs ou endereços de e-mail que foram banidos
-- do sistema devido a violações das regras ou atividades maliciosas.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `banned_users`;
CREATE TABLE `banned_users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do registro de banimento.',
    `user_id` INT NULL UNIQUE COMMENT 'ID do usuário banido (NULL se for banimento por IP/Email, mas não para um usuário específico).',
    `ip_address` VARCHAR(45) NULL COMMENT 'Endereço IP banido (para banimentos de IP).',
    `email_address` VARCHAR(255) NULL COMMENT 'Endereço de email banido (para banimentos de email).',
    `reason` TEXT NOT NULL COMMENT 'Razão detalhada do banimento.',
    `banned_by_user_id` INT NOT NULL COMMENT 'ID do usuário (admin/moderador) que aplicou o banimento.',
    `ban_start_date` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora do início do banimento.',
    `ban_end_date` DATETIME NULL COMMENT 'Data e hora do fim do banimento (NULL para banimento permanente).',
    `is_permanent` BOOLEAN DEFAULT FALSE COMMENT 'Indica se o banimento é permanente (TRUE) ou temporário (FALSE).',
    `notes` TEXT NULL COMMENT 'Notas internas sobre o banimento.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do registro de banimento.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do registro de banimento.',
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE COMMENT 'Se o usuário banido for deletado, o registro de banimento permanece, mas o user_id fica NULL.',
    FOREIGN KEY (`banned_by_user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE COMMENT 'Não permite deletar o usuário que aplicou o banimento.'
    -- Restrição CHECK para garantir que pelo menos um identificador de banimento esteja presente.
    -- (user_id IS NOT NULL OR ip_address IS NOT NULL OR email_address IS NOT NULL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Registra usuários, IPs e emails banidos do sistema.';

-- Índices para buscas rápidas por user_id, IP e email
CREATE INDEX idx_banned_user_id ON `banned_users`(`user_id`);
CREATE INDEX idx_banned_ip_address ON `banned_users`(`ip_address`);
CREATE INDEX idx_banned_email_address ON `banned_users`(`email_address`);
CREATE INDEX idx_banned_by ON `banned_users`(`banned_by_user_id`);
CREATE INDEX idx_banned_permanent ON `banned_users`(`is_permanent`);
CREATE INDEX idx_banned_end_date ON `banned_users`(`ban_end_date`);


-- --------------------------------------------------------
-- Tabela: group_tools
-- Descrição: Gerencia grupos, clãs ou guildas dentro dos jogos ou da comunidade.
-- Permite aos usuários organizarem-se e interagirem.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `group_tools`;
CREATE TABLE `group_tools` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do grupo.',
    `group_name` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Nome único do grupo/guilda.',
    `description` TEXT NULL COMMENT 'Descrição detalhada do grupo e seus objetivos.',
    `owner_user_id` INT NOT NULL COMMENT 'ID do usuário proprietário ou fundador do grupo.',
    `member_count` INT DEFAULT 0 CHECK (`member_count` >= 0) COMMENT 'Número atual de membros no grupo.',
    `is_public` BOOLEAN DEFAULT TRUE COMMENT 'Indica se o grupo é público (qualquer um pode ver/entrar) ou privado (requer convite).',
    `max_members` INT NULL CHECK (`max_members` >= 0) COMMENT 'Número máximo de membros permitido no grupo.',
    `logo_url` VARCHAR(2048) NULL COMMENT 'URL para o logotipo ou ícone do grupo.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do grupo.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do grupo.',
    FOREIGN KEY (`owner_user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE COMMENT 'Não permite deletar o proprietário de um grupo.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Gerencia grupos e guildas na comunidade.';

-- Índices para otimização de buscas por proprietário e visibilidade
CREATE INDEX idx_groups_owner ON `group_tools`(`owner_user_id`);
CREATE INDEX idx_groups_public ON `group_tools`(`is_public`);


-- --------------------------------------------------------
-- Tabela: group_members
-- Descrição: Tabela de junção (many-to-many) para gerenciar os membros
-- de cada grupo, incluindo a data de entrada e o papel no grupo.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `group_members`;
CREATE TABLE `group_members` (
    `group_id` INT NOT NULL COMMENT 'ID do grupo.',
    `user_id` INT NOT NULL COMMENT 'ID do usuário membro.',
    `joined_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Data em que o usuário se juntou ao grupo.',
    `role_in_group` VARCHAR(100) DEFAULT 'member' COMMENT 'Papel do usuário dentro do grupo (ex: leader, officer, recruit, veteran).',
    PRIMARY KEY (`group_id`, `user_id`) COMMENT 'Chave primária composta.',
    FOREIGN KEY (`group_id`) REFERENCES `group_tools`(`id`) ON DELETE CASCADE ON UPDATE CASCADE COMMENT 'Membro é removido do grupo se o grupo for excluído.',
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE COMMENT 'Membro é removido do grupo se o usuário for excluído.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Membros e seus papéis dentro de grupos.';

-- Índices para buscas rápidas de membros por grupo ou de grupos por membro
CREATE INDEX idx_group_members_user_id ON `group_members`(`user_id`);


-- --------------------------------------------------------
-- Tabela: themes
-- Descrição: Armazena temas visuais ou configurações de personalização
-- para a interface do usuário do sistema.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `themes`;
CREATE TABLE `themes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do tema.',
    `theme_name` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Nome único e descritivo do tema.',
    `description` TEXT NULL COMMENT 'Breve descrição do tema.',
    `css_file_url` VARCHAR(2048) NULL COMMENT 'URL para o arquivo CSS principal do tema.',
    `preview_image_url` VARCHAR(2048) NULL COMMENT 'URL para uma imagem de pré-visualização do tema.',
    `is_active` BOOLEAN DEFAULT TRUE COMMENT 'Indica se o tema está ativo e disponível para uso pelos usuários.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do registro do tema.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do registro do tema.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Gerencia temas visuais e configurações de personalização.';

-- Índice para facilitar a busca por temas ativos
CREATE INDEX idx_themes_active ON `themes`(`is_active`);


-- --------------------------------------------------------
-- Tabela: banners
-- Descrição: Gerencia banners promocionais, anúncios ou carrosséis
-- exibidos no site ou aplicativo da Cypher Corporation.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do banner.',
    `name` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Nome interno do banner para fácil identificação na administração.',
    `image_url` VARCHAR(2048) NOT NULL COMMENT 'URL da imagem principal do banner.',
    `target_url` VARCHAR(2048) NULL COMMENT 'URL para onde o banner redireciona ao ser clicado.',
    `alt_text` VARCHAR(255) NULL COMMENT 'Texto alternativo para a imagem (acessibilidade e SEO).',
    `start_date` DATETIME NULL COMMENT 'Data e hora de início de exibição do banner.',
    `end_date` DATETIME NULL COMMENT 'Data e hora de término de exibição do banner.',
    `display_order` INT DEFAULT 0 COMMENT 'Ordem de exibição do banner em um carrossel ou lista.',
    `is_active` BOOLEAN DEFAULT TRUE COMMENT 'Indica se o banner está ativo e sendo exibido.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp da criação do registro do banner.',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do registro do banner.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Gerencia banners promocionais e visuais do site.';

-- Índices para otimização de exibição de banners
CREATE INDEX idx_banners_active ON `banners`(`is_active`);
CREATE INDEX idx_banners_dates ON `banners`(`start_date`, `end_date`);
CREATE INDEX idx_banners_display_order ON `banners`(`display_order`);


-- --------------------------------------------------------
-- Tabela: security_logs
-- Descrição: Registra eventos de segurança importantes para auditoria,
-- monitoramento e detecção de atividades suspeitas.
-- --------------------------------------------------------
DROP TABLE IF EXISTS `security_logs`;
CREATE TABLE `security_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do log de segurança.',
    `event_type` VARCHAR(100) NOT NULL COMMENT 'Tipo do evento de segurança (ex: login_success, login_failure, password_change, data_access, permission_change).',
    `user_id` INT NULL COMMENT 'ID do usuário envolvido no evento (NULL se não aplicável ou antes do login).',
    `ip_address` VARCHAR(45) NULL COMMENT 'Endereço IP de origem do evento.',
    `description` TEXT NOT NULL COMMENT 'Descrição detalhada do evento de segurança, incluindo o que aconteceu.',
    `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp exato do ocorrido.',
    `affected_resource` VARCHAR(255) NULL COMMENT 'Recurso ou objeto afetado pelo evento (ex: /users/1, /products/delete, user_role_assignment).',
    `is_critical` BOOLEAN DEFAULT FALSE COMMENT 'Indica se o evento é considerado crítico para revisão imediata (TRUE) ou informativo (FALSE).',
    `metadata` JSON NULL COMMENT 'Dados adicionais em formato JSON, como detalhes do erro, parâmetros da requisição, etc.',
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE COMMENT 'Se o usuário envolvido for deletado, o log permanece, mas é desassociado.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Registra eventos de segurança para auditoria.';

-- Índices para otimização de consultas em logs de segurança
CREATE INDEX idx_logs_event_type ON `security_logs`(`event_type`);
CREATE INDEX idx_logs_user_id ON `security_logs`(`user_id`);
CREATE INDEX idx_logs_timestamp ON `security_logs`(`timestamp`);
CREATE INDEX idx_logs_critical ON `security_logs`(`is_critical`);


-- # 3. POPULAÇÃO DE DADOS INICIAIS

-- Usuários de exemplo
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `status`, `profile_picture_url`, `bio`, `last_login_at`, `email_verified_at`) VALUES
('admin_master', 'admin@cypher.com', '$2y$10$abcdefghijklmnopqrstuvwxyza.abcdefghijklmnopqrstuvwxyza', 'admin', 'active', 'https://i.imgur.com/example_admin.jpg', 'Administrador principal do sistema Cypher Corp.', NOW(), NOW()), -- Senha: 'Admin@123' (hash simulado)
('editor_content', 'editor@cypher.com', '$2y$10$abcdefghijklmnopqrstuvwxyza.abcdefghijklmnopqrstuvwxyza', 'editor', 'active', 'https://i.imgur.com/example_editor.jpg', 'Gerente de conteúdo e publicações.', DATE_SUB(NOW(), INTERVAL 1 DAY), NOW()), -- Senha: 'Editor@123'
('moderator_forum', 'moderator@cypher.com', '$2y$10$abcdefghijklmnopqrstuvwxyza.abcdefghijklmnopqrstuvwxyza', 'moderator', 'active', NULL, 'Moderador da comunidade e fóruns.', DATE_SUB(NOW(), INTERVAL 2 DAY), NOW()), -- Senha: 'Moderator@123'
('john_doe', 'john.doe@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyza.abcdefghijklmnopqrstuvwxyza', 'user', 'active', 'https://i.imgur.com/example_john.jpg', 'Usuário ativo da comunidade Cypher.', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()), -- Senha: 'User@123'
('jane_smith', 'jane.smith@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyza.abcdefghijklmnopqrstuvwxyza', 'user', 'pending_verification', NULL, 'Novo usuário aguardando verificação de email.', NULL, NULL),
('suspended_user', 'suspended@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyza.abcdefghijklmnopqrstuvwxyza', 'user', 'suspended', NULL, 'Usuário temporariamente suspenso.', DATE_SUB(NOW(), INTERVAL 10 DAY), NOW()),
('banned_user', 'banned@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyza.abcdefghijklmnopqrstuvwxyza', 'user', 'banned', NULL, 'Usuário banido permanentemente.', DATE_SUB(NOW(), INTERVAL 20 DAY), NOW());

-- Permissões de exemplo
INSERT INTO `permissions` (`name`, `description`) VALUES
('manage_users', 'Permite criar, visualizar, editar e excluir usuários.'),
('view_reports', 'Permite visualizar todos os relatórios analíticos e financeiros.'),
('edit_products', 'Permite adicionar, modificar e remover produtos do catálogo.'),
('publish_news', 'Permite criar, editar e publicar artigos de notícias.'),
('manage_banners', 'Permite adicionar, editar e excluir banners promocionais.'),
('resolve_tickets', 'Permite responder e resolver tickets de suporte.'),
('manage_bugs', 'Permite triar, atribuir e fechar relatórios de bugs.'),
('manage_groups', 'Permite criar, editar e moderar grupos de usuários.');

-- Associação de Papéis e Permissões de exemplo
INSERT INTO `role_permissions` (`role`, `permission_id`) VALUES
('admin', (SELECT id FROM permissions WHERE name = 'manage_users')),
('admin', (SELECT id FROM permissions WHERE name = 'view_reports')),
('admin', (SELECT id FROM permissions WHERE name = 'edit_products')),
('admin', (SELECT id FROM permissions WHERE name = 'publish_news')),
('admin', (SELECT id FROM permissions WHERE name = 'manage_banners')),
('admin', (SELECT id FROM permissions WHERE name = 'resolve_tickets')),
('admin', (SELECT id FROM permissions WHERE name = 'manage_bugs')),
('admin', (SELECT id FROM permissions WHERE name = 'manage_groups')),
('editor', (SELECT id FROM permissions WHERE name = 'edit_products')),
('editor', (SELECT id FROM permissions WHERE name = 'publish_news')),
('editor', (SELECT id FROM permissions WHERE name = 'manage_banners')),
('moderator', (SELECT id FROM permissions WHERE name = 'resolve_tickets')),
('moderator', (SELECT id FROM permissions WHERE name = 'manage_groups'));

-- Sessões de exemplo
INSERT INTO `sessions` (`user_id`, `session_token`, `ip_address`, `user_agent`, `expires_at`) VALUES
(1, 'adm_token_2025_abcde', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Safari/537.36', DATE_ADD(NOW(), INTERVAL 2 HOUR)),
(4, 'user_token_2025_fghij', '203.0.113.5', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Safari/605.1.15', DATE_ADD(NOW(), INTERVAL 1 HOUR));

-- Produtos de exemplo
INSERT INTO `products` (`name`, `sku`, `description`, `price`, `cost_price`, `stock`, `image_url`, `category`, `brand`, `is_active`, `weight_kg`) VALUES
('CypherGuard Pro Ultimate', 'CGP-ULT-001', 'Software de segurança avançado com IA e proteção em tempo real.', 199.99, 50.00, 150, 'https://i.imgur.com/cypherguard_pro.jpg', 'Software', 'CypherSec', TRUE, 0.1),
('Quantum Processor X1', 'QP-X1-HW', 'Processador de última geração para computação quântica e alto desempenho.', 2500.00, 1200.00, 25, 'https://i.imgur.com/quantum_processor.jpg', 'Hardware', 'CypherTech', TRUE, 0.5),
('NeuroLink Interface V2', 'NL-V2-HW', 'Dispositivo de interface neural direta para realidade aumentada imersiva.', 1200.00, 600.00, 10, 'https://i.imgur.com/neurolink_v2.jpg', 'Hardware', 'BioCypher', FALSE, 0.3), -- Inativo para testes
('Data Encryption Service - 1 Year', 'DES-1Y-SVC', 'Serviço de criptografia de dados em nuvem com conformidade GDPR por 1 ano.', 49.99, 15.00, 9999, 'https://i.imgur.com/encryption_service.jpg', 'Serviço', 'CypherCloud', TRUE, 0.0),
('CypherQuest Online - Edição Lendária', 'CQO-LEG-ED', 'MMORPG de ficção científica com acesso a todos os DLCs e bônus exclusivos.', 69.99, 20.00, 500, 'https://i.imgur.com/cypherquest_game.jpg', 'Jogos', 'CypherGames', TRUE, 0.0);

-- Pedidos de exemplo
INSERT INTO `orders` (`user_id`, `total_amount`, `status`, `shipping_address`, `billing_address`, `payment_method`, `tracking_number`, `shipping_cost`, `discount_amount`) VALUES
(4, 199.99, 'delivered', 'Rua Alfa, 123, Bairro Central, Cidade, Estado, CEP 12345-678', 'Rua Alfa, 123, Bairro Central, Cidade, Estado, CEP 12345-678', 'Credit Card', 'TRACK123456', 0.00, 0.00),
(1, 2550.00, 'processing', 'Av. Beta, 456, Distrito Industrial, Outra Cidade, Outro Estado, CEP 98765-432', 'Av. Beta, 456, Distrito Industrial, Outra Cidade, Outro Estado, CEP 98765-432', 'Bank Transfer', NULL, 50.00, 0.00),
(4, 49.99, 'pending', 'Rua Gama, 789, Centro, Cidade, Estado, CEP 11223-345', 'Rua Gama, 789, Centro, Cidade, Estado, CEP 11223-345', 'PayPal', NULL, 0.00, 0.00);

-- Itens dos pedidos de exemplo
INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price_at_purchase`) VALUES
(1, 1, 1, 199.99), -- CypherGuard Pro Ultimate
(2, 2, 1, 2500.00), -- Quantum Processor X1
(2, 4, 1, 49.99), -- Data Encryption Service - 1 Year
(3, 4, 1, 49.99); -- Data Encryption Service - 1 Year

-- Calendário de jogos/eventos de exemplo
INSERT INTO `game_calendar` (`title`, `description`, `event_date_time`, `event_type`, `game_name`, `location`, `created_by_user_id`, `is_public`) VALUES
('Lançamento CypherQuest Online Mundial', 'A tão esperada estreia global do nosso MMORPG imersivo.', '2025-07-15 10:00:00', 'release', 'CypherQuest Online', 'Online', 1, TRUE),
('Manutenção Programada do Servidor Principal', 'Servidores offline para otimização de desempenho e aplicação de patches de segurança.', '2025-06-20 03:00:00', 'maintenance', NULL, 'Data Center CypherCorp', 1, TRUE),
('Torneio Mundial de CyberArena 2025', 'O maior torneio eSports do ano! Grandes prêmios e glória esperam os campeões.', '2025-08-01 19:00:00', 'tournament', 'CyberArena', 'https://twitch.tv/cypherarena', 2, TRUE),
('Perguntas e Respostas com a Equipe Dev de CypherQuest', 'Participe de um bate-papo ao vivo com os desenvolvedores de CypherQuest e faça suas perguntas!', '2025-07-05 16:00:00', 'livestream', 'CypherQuest Online', 'YouTube Live', 2, TRUE);

-- Desenvolvedores de exemplo
INSERT INTO `developers` (`name`, `email`, `specialty`, `hire_date`, `status`, `phone_number`) VALUES
('Alice Wonderland', 'alice.w@cypher.com', 'Frontend Development', '2022-01-15', 'active', '+5511987654321'),
('Bob The Builder', 'bob.b@cypher.com', 'Backend Engineering', '2021-05-20', 'active', '+5521998765432'),
('Charlie Chaplin', 'charlie.c@cypher.com', 'Game Design', '2023-03-10', 'active', NULL),
('Diana Prince', 'diana.p@cypher.com', 'Quality Assurance', '2024-02-01', 'active', '+5541912345678');

-- Projetos de exemplo
INSERT INTO `projects` (`project_name`, `description`, `start_date`, `status`, `budget`, `lead_developer_id`) VALUES
('Project Nova OS', 'Desenvolvimento de um novo sistema operacional focado em segurança e privacidade.', '2024-01-01', 'in_progress', 1500000.00, 2),
('Game Fusion VR', 'Criação de um jogo de realidade virtual de ficção científica com elementos de RPG.', '2023-08-01', 'testing', 750000.00, 3),
('Cypher Analytics Platform', 'Desenvolvimento de uma plataforma interna para análise de dados de usuários e produtos.', '2024-05-01', 'planning', 300000.00, 2);

-- Associação de Projetos e Desenvolvedores de exemplo
INSERT INTO `project_developers` (`project_id`, `developer_id`, `role_in_project`) VALUES
(1, 1, 'Frontend Lead'),
(1, 2, 'Backend Architect'),
(2, 3, 'Lead Game Designer'),
(2, 4, 'QA Lead'),
(3, 2, 'Project Lead'),
(3, 1, 'UI/UX Designer');

-- Feedback de usuários de exemplo
INSERT INTO `feedback` (`user_id`, `feedback_type`, `subject`, `message`, `rating`, `status`) VALUES
(4, 'suggestion', 'Melhoria na interface do usuário do Painel', 'A navegação poderia ser mais intuitiva nas seções de relatórios. Considerar um menu de filtro fixo.', 4, 'new'),
(NULL, 'bug', 'Erro ao processar pagamento no checkout', 'Não consegui finalizar a compra do CypherGuard Pro, recebi um erro 500 após inserir os dados do cartão. Usuário não logado.', NULL, 'new'),
(1, 'praise', 'Excelente suporte ao cliente!', 'O atendimento sobre meu ticket de senha foi rápido, educado e resolveu meu problema em minutos. Parabéns à equipe!', 5, 'action_taken'),
(4, 'question', 'Dúvida sobre requisitos do Quantum Processor X1', 'Gostaria de saber quais são os requisitos mínimos de energia e refrigeração para o Quantum Processor X1.', NULL, 'new');

-- Tickets de suporte de exemplo
INSERT INTO `support_tickets` (`user_id`, `subject`, `description`, `priority`, `status`, `assigned_to_user_id`) VALUES
(4, 'Problema com acesso à conta', 'Não consigo redefinir minha senha. O email de recuperação não chega.', 'high', 'open', NULL),
(1, 'Dúvida sobre API de integração de produtos', 'Preciso de ajuda para entender como integrar a API de produtos ao meu sistema externo.', 'medium', 'in_progress', 1), -- Admin atribuído a si mesmo
(6, 'Conta suspensa indevidamente', 'Minha conta foi suspensa sem motivo aparente. Por favor, revisem isso. Sou um usuário ativo.', 'critical', 'open', NULL);

-- Relatórios de bug de exemplo
INSERT INTO `bug_reports` (`user_id`, `title`, `description`, `severity`, `status`, `component`, `version_affected`, `assigned_to_user_id`, `screenshot_url`) VALUES
(4, 'Botão de login não responsivo em mobile', 'Ao acessar a página de login pelo celular, o botão "Entrar" não responde ao clique em alguns casos.', 'high', 'new', 'UI - Frontend (Mobile)', '1.2.0', 1, 'https://i.imgur.com/login_bug_screenshot.png'),
(NULL, 'Dados inconsistentes no relatório de vendas diárias', 'O total de vendas exibido no dashboard não bate com a soma detalhada dos itens vendidos para o dia 05/06/2025.', 'critical', 'new', 'Backend - Relatórios Financeiros', '2.1.5', 2, NULL),
(1, 'Erro de texto em modal de perfil', 'No modal de edição de perfil, o texto "Save Changes" aparece em inglês, deveria ser "Salvar Alterações".', 'low', 'in_progress', 'UI - Painel Admin', '3.0.0', 1, NULL);

-- Artigos de notícias de exemplo
INSERT INTO `news_articles` (`title`, `slug`, `content`, `author_user_id`, `category`, `tags`, `thumbnail_url`, `is_published`, `published_at`) VALUES
('Cypher Corp Lança Nova Plataforma de IA "Cognito"', 'cypher-corp-lanca-plataforma-cognito', 'Estamos entusiasmados em anunciar o lançamento da nossa revolucionária plataforma de inteligência artificial, Cognito, que promete transformar a interação digital.', 1, 'Empresa', 'IA, tecnologia, inovação', 'https://i.imgur.com/news_ia_cognito.jpg', TRUE, '2025-06-01 09:00:00'),
('Atualização de Segurança Crítica para CypherGuard Pro Liberada', 'atualizacao-seguranca-cypherguard-pro', 'Uma atualização de segurança urgente foi liberada para todos os usuários do CypherGuard Pro. Recomendamos a instalação imediata para proteger seus dados.', 2, 'Produtos', 'segurança, software, atualização', 'https://i.imgur.com/news_cypherguard_update.jpg', TRUE, '2025-06-05 14:30:00'),
('Conheça o Futuro da Computação: Quantum Processor X1', 'futuro-computacao-quantum-processor-x1', 'Nossa equipe de engenheiros por trás do Quantum Processor X1 compartilha insights sobre o desenvolvimento e o impacto desta tecnologia inovadora.', 1, 'Tecnologia', 'hardware, quantum, processadores', 'https://i.imgur.com/news_quantum_processor.jpg', TRUE, '2025-05-20 10:00:00');

-- Transações financeiras de exemplo
INSERT INTO `financial_transactions` (`transaction_type`, `amount`, `currency`, `description`, `transaction_date`, `related_order_id`, `category`, `recorded_by_user_id`) VALUES
('revenue', 199.99, 'BRL', 'Venda de software CypherGuard Pro Ultimate', '2025-06-01 10:30:00', 1, 'Software Sales', 1),
('revenue', 2550.00, 'BRL', 'Venda de hardware Quantum Processor X1 e serviço de criptografia', '2025-06-02 11:00:00', 2, 'Hardware Sales', 1),
('expense', 15000.00, 'BRL', 'Salários da equipe de desenvolvimento - Junho/2025', '2025-06-01 09:00:00', NULL, 'Salaries', 1),
('expense', 1200.00, 'BRL', 'Aluguel do escritório principal - Junho/2025', '2025-06-01 09:15:00', NULL, 'Rent', 1),
('revenue', 49.99, 'BRL', 'Venda de serviço Data Encryption Service', '2025-06-03 14:00:00', 3, 'Service Sales', 1);

-- Usuários banidos de exemplo
INSERT INTO `banned_users` (`user_id`, `ip_address`, `email_address`, `reason`, `banned_by_user_id`, `ban_start_date`, `ban_end_date`, `is_permanent`) VALUES
(7, NULL, NULL, 'Violação grave das regras da comunidade: spam excessivo e comportamento abusivo.', 1, NOW(), NULL, TRUE), -- Usuário 7 (banned_user) banido permanentemente
(NULL, '198.51.100.10', NULL, 'Atividade de bot maliciosa, tentativas de injeção SQL.', 1, NOW(), NULL, TRUE), -- Banimento por IP
(NULL, NULL, 'spammer@malicious.com', 'Envio de emails de phishing e spam.', 1, NOW(), NULL, TRUE), -- Banimento por Email
(6, NULL, NULL, 'Comportamento tóxico em jogos online e assédio a outros jogadores.', 3, NOW(), '2025-08-01 00:00:00', FALSE); -- Usuário 6 (suspended_user) banido temporariamente

-- Ferramentas de grupo de exemplo
INSERT INTO `group_tools` (`group_name`, `description`, `owner_user_id`, `member_count`, `is_public`, `max_members`, `logo_url`) VALUES
('Cyber Knights Elite', 'Um grupo de elite focado em missões de alto risco e competições eSports.', 1, 15, TRUE, 20, 'https://i.imgur.com/group_cyberknights.jpg'),
('Data Wizards Hub', 'Comunidade dedicada à exploração de dados, hacking ético e segurança cibernética.', 1, 30, TRUE, 50, 'https://i.imgur.com/group_datawizards.jpg'),
('The Glitch Gang', 'Um grupo de amigos que adora encontrar e explorar bugs em jogos para reportá-los de forma responsável.', 4, 8, FALSE, 10, NULL);

-- Membros de grupo de exemplo
INSERT INTO `group_members` (`group_id`, `user_id`, `joined_at`, `role_in_group`) VALUES
(1, 1, DATE_SUB(NOW(), INTERVAL 30 DAY), 'leader'),
(1, 4, DATE_SUB(NOW(), INTERVAL 25 DAY), 'member'),
(2, 1, DATE_SUB(NOW(), INTERVAL 40 DAY), 'leader'),
(2, 2, DATE_SUB(NOW(), INTERVAL 35 DAY), 'officer'),
(3, 4, DATE_SUB(NOW(), INTERVAL 15 DAY), 'leader'),
(3, 3, DATE_SUB(NOW(), INTERVAL 10 DAY), 'member');

-- Temas de personalização de exemplo
INSERT INTO `themes` (`theme_name`, `description`, `css_file_url`, `preview_image_url`, `is_active`) VALUES
('Default Dark Mode', 'Tema escuro padrão da Cypher Corporation, otimizado para longas sessões de uso.', '/css/default-dark.css', 'https://i.imgur.com/theme_dark_preview.jpg', TRUE),
('Cypher Light Core', 'Tema claro com cores vibrantes e design minimalista, para uma experiência arejada.', '/css/cypher-light.css', 'https://i.imgur.com/theme_light_preview.jpg', TRUE),
('Matrix Green Code', 'Um tema futurista inspirado na Matrix, com tons de verde neon e elementos de código.', '/css/matrix-green.css', 'https://i.imgur.com/theme_matrix_preview.jpg', FALSE);

-- Banners promocionais de exemplo
INSERT INTO `banners` (`name`, `image_url`, `target_url`, `alt_text`, `start_date`, `end_date`, `display_order`, `is_active`) VALUES
('Promoção Lançamento Cognito AI', 'https://i.imgur.com/banner_cognito_launch.jpg', 'https://cypher.com/cognito', 'Banner promocional da nova plataforma Cognito AI', '2025-06-01 00:00:00', '2025-06-30 23:59:59', 1, TRUE),
('Venda de Verão - Produtos com até 50% Off', 'https://i.imgur.com/banner_summer_sale.jpg', 'https://cypher.com/sales/summer', 'Banner da venda de verão com descontos em produtos Cypher', '2025-07-01 00:00:00', '2025-07-31 23:59:59', 2, FALSE), -- Desativado
('Recrute um Amigo e Ganhe Bônus', 'https://i.imgur.com/banner_referral_program.jpg', 'https://cypher.com/referral', 'Banner do programa de indicação de amigos', NULL, NULL, 3, TRUE); -- Ativo sem data de fim

-- Logs de segurança de exemplo
INSERT INTO `security_logs` (`event_type`, `user_id`, `ip_address`, `description`, `affected_resource`, `is_critical`, `metadata`) VALUES
('login_success', 1, '192.168.1.100', 'Login bem-sucedido do usuário admin_master.', '/auth/login', FALSE, '{"device": "Desktop", "browser": "Chrome"}'),
('login_failure', NULL, '203.0.113.10', 'Tentativa de login falha para o usuário "hackerman". Senha incorreta.', '/auth/login', TRUE, '{"attempted_username": "hackerman", "reason": "wrong_password"}'),
('password_change', 4, '192.168.1.101', 'Senha do usuário john_doe alterada com sucesso.', '/users/profile/password', FALSE, NULL),
('data_access', 1, '192.168.1.100', 'Acesso e exportação de dados de todos os usuários por admin_master.', '/admin/users/export', TRUE, '{"exported_format": "CSV", "rows_exported": 7}'),
('permission_change', 1, '192.168.1.100', 'Permissão "edit_products" concedida ao papel "editor".', 'role_permissions', FALSE, '{"role": "editor", "permission": "edit_products", "action": "grant"}'),
('system_alert', NULL, '10.0.0.5', 'Alto uso de CPU no servidor de banco de dados.', 'database_server', TRUE, '{"cpu_usage": "95%", "server_id": "db_01"}');


-- Confirma todas as operações da transação se não houver erros.
COMMIT;

-- Reativa as verificações de chave estrangeira.
SET FOREIGN_KEY_CHECKS = 1;

-- ###############################################################
-- # 4. Dicionário de Dados Simplificado (Para o Professor) #
-- ###############################################################

-- Esta seção é uma documentação concisa do esquema do banco de dados,
-- ideal para apresentar ao professor e demonstrar o entendimento da estrutura.

/*
--------------------------------------------------------------------------------
Tabela: `users`
Descrição: Gerencia todos os usuários do sistema, incluindo autenticação e dados de perfil.
Colunas Chave:
  - `id`: Chave primária.
  - `username`, `email`: Únicos para identificação e login.
Relacionamentos:
  - `sessions` (1:N): Um usuário pode ter múltiplas sessões.
  - `orders` (1:N): Um usuário pode fazer múltiplos pedidos.
  - `game_calendar` (1:N): Usuários podem criar eventos.
  - `feedback` (1:N): Usuários podem enviar feedback.
  - `support_tickets` (1:N): Usuários abrem tickets.
  - `bug_reports` (1:N): Usuários reportam bugs.
  - `news_articles` (1:N): Usuários (autores) publicam artigos.
  - `financial_transactions` (1:N): Usuários podem registrar transações.
  - `banned_users` (1:N): Usuários podem ser banidos, e admins banem.
  - `group_tools` (1:N): Usuários podem ser proprietários de grupos.
  - `group_members` (N:M via tabela de junção): Usuários são membros de grupos.
  - `permissions`, `role_permissions`: Usuários têm papéis que definem permissões.
Considerações: `password_hash` para segurança de senhas. `role` e `status` para controle de acesso.

--------------------------------------------------------------------------------
Tabela: `permissions`
Descrição: Define permissões granulares que podem ser atribuídas a diferentes papéis de usuário.
Colunas Chave:
  - `id`: Chave primária.
  - `name`: Nome único da permissão.
Relacionamentos:
  - `role_permissions` (1:N): Uma permissão pode ser associada a múltiplos papéis.
Considerações: Permite um controle de acesso baseado em função (RBAC) flexível.

--------------------------------------------------------------------------------
Tabela: `role_permissions` (Tabela de Junção)
Descrição: Mapeia quais `permissions` são concedidas a cada `role`.
Colunas Chave:
  - `role`, `permission_id`: Chave primária composta.
Relacionamentos:
  - `permissions` (N:1): Múltiplos papéis podem ter a mesma permissão.
Considerações: Essencial para o sistema de RBAC.

--------------------------------------------------------------------------------
Tabela: `sessions`
Descrição: Rastreia sessões de login ativas para segurança e funcionalidades.
Colunas Chave:
  - `id`: Chave primária.
  - `session_token`: Token único para a sessão.
Relacionamentos:
  - `users` (N:1): Cada sessão pertence a um único usuário.
Considerações: Inclui IP e User-Agent para detecção de atividades anormais.

--------------------------------------------------------------------------------
Tabela: `products`
Descrição: Catálogo de todos os produtos e serviços disponíveis para venda.
Colunas Chave:
  - `id`: Chave primária.
  - `name`, `sku`: Únicos para identificação do produto.
Relacionamentos:
  - `order_items` (1:N): Um produto pode estar em múltiplos itens de pedido.
Considerações: `CHECK` constraints para `price`, `cost_price` e `stock` garantem dados válidos.

--------------------------------------------------------------------------------
Tabela: `orders`
Descrição: Armazena informações sobre os pedidos feitos pelos clientes.
Colunas Chave:
  - `id`: Chave primária.
Relacionamentos:
  - `users` (N:1): Cada pedido pertence a um usuário.
  - `order_items` (1:N): Um pedido pode ter múltiplos itens.
  - `financial_transactions` (1:N): Um pedido pode gerar uma transação financeira.
Considerações: `status` rastreia o ciclo de vida do pedido.

--------------------------------------------------------------------------------
Tabela: `order_items` (Tabela de Junção)
Descrição: Detalha os produtos incluídos em cada pedido.
Colunas Chave:
  - `order_id`, `product_id`: Chave primária composta.
Relacionamentos:
  - `orders` (N:1): Múltiplos itens podem pertencer ao mesmo pedido.
  - `products` (N:1): Múltiplos itens podem referenciar o mesmo produto.
Considerações: `price_at_purchase` registra o preço no momento da venda, crucial para relatórios.

--------------------------------------------------------------------------------
Tabela: `game_calendar`
Descrição: Gerencia eventos e lançamentos importantes relacionados a jogos e serviços.
Colunas Chave:
  - `id`: Chave primária.
Relacionamentos:
  - `users` (N:1): Eventos podem ser criados por usuários específicos (admins/editors).
Considerações: `event_type` categoriza diferentes tipos de eventos.

--------------------------------------------------------------------------------
Tabela: `developers`
Descrição: Informações sobre os desenvolvedores e equipes internas da Cypher Corp.
Colunas Chave:
  - `id`: Chave primária.
  - `name`, `email`: Únicos para identificação.
Relacionamentos:
  - `projects` (1:N): Um desenvolvedor pode ser o líder de múltiplos projetos.
  - `project_developers` (N:M via tabela de junção): Desenvolvedores participam de projetos.
Considerações: `specialty` para filtragem de habilidades.

--------------------------------------------------------------------------------
Tabela: `projects`
Descrição: Rastreia os projetos de desenvolvimento em andamento.
Colunas Chave:
  - `id`: Chave primária.
  - `project_name`: Nome único do projeto.
Relacionamentos:
  - `developers` (N:1): Cada projeto tem um desenvolvedor líder.
  - `project_developers` (N:M via tabela de junção): Projetos têm múltiplos desenvolvedores.
Considerações: `status` para acompanhamento do progresso.

--------------------------------------------------------------------------------
Tabela: `project_developers` (Tabela de Junção)
Descrição: Associa desenvolvedores a projetos.
Colunas Chave:
  - `project_id`, `developer_id`: Chave primária composta.
Relacionamentos:
  - `projects` (N:1): Múltiplos desenvolvedores podem trabalhar no mesmo projeto.
  - `developers` (N:1): Um desenvolvedor pode trabalhar em múltiplos projetos.
Considerações: `role_in_project` define a função específica do desenvolvedor naquele projeto.

--------------------------------------------------------------------------------
Tabela: `feedback`
Descrição: Armazena o feedback geral enviado pelos usuários.
Colunas Chave:
  - `id`: Chave primária.
Relacionamentos:
  - `users` (N:1): Feedback pode ser associado a um usuário ou ser anônimo.
Considerações: `feedback_type` e `rating` para categorização e análise de sentimento.

--------------------------------------------------------------------------------
Tabela: `support_tickets`
Descrição: Gerencia tickets de suporte ao cliente.
Colunas Chave:
  - `id`: Chave primária.
Relacionamentos:
  - `users` (N:1): Tickets são abertos por usuários e atribuídos a usuários (suporte/admin).
Considerações: `priority` e `status` para fluxo de trabalho de suporte.

--------------------------------------------------------------------------------
Tabela: `bug_reports`
Descrição: Gerencia relatórios de bugs para o controle de qualidade.
Colunas Chave:
  - `id`: Chave primária.
Relacionamentos:
  - `users` (N:1): Bugs reportados por usuários e atribuídos a desenvolvedores/admins.
Considerações: `severity` e `status` para priorização e acompanhamento da correção.

--------------------------------------------------------------------------------
Tabela: `news_articles`
Descrição: Armazena artigos de notícias e comunicados para o público.
Colunas Chave:
  - `id`: Chave primária.
  - `title`, `slug`: Únicos para identificação e URLs amigáveis.
Relacionamentos:
  - `users` (N:1): Artigos são escritos por autores (usuários).
Considerações: `is_published` e `published_at` para controle de publicação.

--------------------------------------------------------------------------------
Tabela: `financial_transactions`
Descrição: Rastreia todas as transações financeiras (receitas e despesas).
Colunas Chave:
  - `id`: Chave primária.
Relacionamentos:
  - `orders` (N:1): Transações de receita podem ser ligadas a pedidos.
  - `users` (N:1): Usuários podem registrar transações.
Considerações: `transaction_type` e `category` para relatórios financeiros detalhados.

--------------------------------------------------------------------------------
Tabela: `banned_users`
Descrição: Lista de usuários, IPs ou emails banidos para manter a integridade da comunidade.
Colunas Chave:
  - `id`: Chave primária.
Relacionamentos:
  - `users` (N:1): Banimentos podem ser associados a um usuário ou apenas a IP/email.
Considerações: Suporta banimentos temporários ou permanentes. Restrição para garantir que haja um identificador de banimento.

--------------------------------------------------------------------------------
Tabela: `group_tools`
Descrição: Ferramentas para gerenciamento de grupos de usuários (clãs, guildas).
Colunas Chave:
  - `id`: Chave primária.
  - `group_name`: Nome único do grupo.
Relacionamentos:
  - `users` (N:1): Cada grupo tem um proprietário.
  - `group_members` (1:N): Um grupo pode ter múltiplos membros.
Considerações: `is_public` para visibilidade do grupo.

--------------------------------------------------------------------------------
Tabela: `group_members` (Tabela de Junção)
Descrição: Associa usuários a grupos.
Colunas Chave:
  - `group_id`, `user_id`: Chave primária composta.
Relacionamentos:
  - `group_tools` (N:1): Múltiplos membros podem estar no mesmo grupo.
  - `users` (N:1): Um usuário pode ser membro de múltiplos grupos.
Considerações: `role_in_group` define a função do membro dentro do grupo.

--------------------------------------------------------------------------------
Tabela: `themes`
Descrição: Gerencia temas visuais e opções de personalização da interface.
Colunas Chave:
  - `id`: Chave primária.
  - `theme_name`: Nome único do tema.
Relacionamentos: Nenhum relacionamento direto com outras tabelas no nível do DB (usado por aplicação).
Considerações: `is_active` para controlar a disponibilidade dos temas.

--------------------------------------------------------------------------------
Tabela: `banners`
Descrição: Gerencia banners promocionais e de anúncios exibidos no site.
Colunas Chave:
  - `id`: Chave primária.
  - `name`: Nome interno único para o banner.
Relacionamentos: Nenhum relacionamento direto com outras tabelas no nível do DB.
Considerações: `start_date`, `end_date`, `is_active` para controle de exibição agendada.

--------------------------------------------------------------------------------
Tabela: `security_logs`
Descrição: Registra eventos de segurança importantes para auditoria e monitoramento.
Colunas Chave:
  - `id`: Chave primária.
Relacionamentos:
  - `users` (N:1): Logs podem estar associados a um usuário ou serem de sistema.
Considerações: `is_critical` para priorizar a revisão de logs. `metadata` para detalhes JSON.
--------------------------------------------------------------------------------
*/

-- ###############################################################
-- # FIM DO SCRIPT #
-- ###############################################################
