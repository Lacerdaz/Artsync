# ArtSync

## Descrição

ArtSync é uma plataforma web desenvolvida como projeto acadêmico, com o objetivo de auxiliar artistas iniciantes e independentes na gestão de suas carreiras de forma centralizada e gratuita. O sistema permite organizar portfólios (galerias com múltiplas imagens, descrição e valor), agendar eventos, obter insights de carreira através de IA (com suporte a anexos de mídia) e visualizar um dashboard com gráfico de desempenho (dados de exemplo). O projeto foi construído seguindo os princípios de Orientação a Objetos, o padrão arquitetural MVC e o Padrão Repository.

## Integrantes

-   Cauã Lacerda - 22301429
-   Davi Torquato - 22300333
-   Igor Ceolin - 22300139
-   Gabriel Alves - 22301577 (Líder)
-   Ravi Braga - 22300198

## Estrutura de Diretórios

    artsync-mvc/
    ├── app/               # Código-fonte principal (Controllers, Models, Repositories, Services)
    │   ├── Controllers/
    │   ├── Models/
    │   ├── Repositories/
    │   └── Services/
    ├── bdd/               # Script SQL do banco de dados
    │   └── database.sql
    ├── config/            # Arquivos de configuração (ex: Database.php)
    │   └── Database.php
    ├── public/            # Raiz pública do site (DocumentRoot)
    │   ├── css/           # Arquivos CSS
    │   ├── images/        # Imagens estáticas (logo, etc.)
    │   ├── js/            # Arquivos JavaScript
    │   ├── uploads/       # Pasta para uploads de mídia do portfólio
    │   ├── .htaccess      # Regras de reescrita do Apache
    │   └── index.php      # Ponto de entrada único (Front Controller/Roteador)
    ├── vendor/            # Dependências gerenciadas pelo Composer (ex: PHPMailer)
    ├── views/             # Arquivos de template HTML/PHP (Views)
    │   ├── auth/
    │   ├── dashboard/
    │   ├── landing/
    │   ├── layouts/
    │   ├── portfolio/
    │   ├── schedule/
    │   ├── ai/
    │   └── admin/
    ├── composer.json      # Definição de dependências e autoloading (Composer)
    ├── composer.lock      # Arquivo de lock do Composer
    ├── connect_spotify.php # Script legado (temporário) para iniciar conexão Spotify
    ├── spotify_callback.php # Script legado (temporário) para receber callback Spotify
    └── README.md          # Este arquivo

## Como Executar o Projeto

### 1. Pré-requisitos

-   **Linguagem/Versão:** PHP >= 8.1 (com extensões `pdo_mysql` e `curl` habilitadas)
-   **Servidor Web:** Apache 2.4+ (com módulo `mod_rewrite` habilitado)
-   **Banco de dados:** MySQL 5.7+ ou MariaDB compatível
-   **Dependências:** Composer instalado globalmente.

### 2. Instalação

``` bash
# 1. Clone o repositório (ou coloque os arquivos na pasta 'www' do WAMP/XAMPP)
# git clone [https://github.com/usuario/repositorio.git](https://github.com/usuario/repositorio.git) artsync-mvc 

# 2. Acesse a pasta do projeto
cd artsync-mvc

# 3. Instale as dependências PHP (PHPMailer e gera o autoloader)
composer install

# 4. Configure o Banco de Dados:
#    - Crie um banco chamado 'artsync_db' (use utf8mb4_general_ci).
#    - Importe o arquivo 'bdd/database.sql' para criar as tabelas.
#    - Edite 'config/Database.php' se suas credenciais MySQL forem diferentes (usuário/senha).

# 5. Configure o Virtual Host no Apache (RECOMENDADO):
#    - Edite o httpd-vhosts.conf do Apache e adicione:
#      <VirtualHost *:80>
#          ServerName artsync.local
#          DocumentRoot "C:/caminho/completo/para/artsync-mvc/public"
#          <Directory "C:/caminho/completo/para/artsync-mvc/public">
#              Options Indexes FollowSymLinks
#              AllowOverride All
#              Require all granted
#          </Directory>
#      </VirtualHost>
#    - Edite o arquivo 'hosts' do seu sistema e adicione: 127.0.0.1 artsync.local
#    - Reinicie o Apache e os Serviços DNS (no WAMP).

# 6. (Opcional) Chave de API da IA:
#    - Obtenha uma chave no Google AI Studio.
#    - Cole a chave na variável $api_key em 'app/Services/AiService.php'.
