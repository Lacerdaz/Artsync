<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Art Sync'); ?></title>
    <link rel="stylesheet" href="/css/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="background-waves"></div>
    <svg class="background-cable" preserveAspectRatio="none" viewBox="0 0 1920 2500">
        <path id="cable-glow" d="M 1920 -50 C 1000 800, 920 1700, 0 2500" fill="none" stroke-width="8" />
        <path id="cable-path" d="M 1920 -50 C 1000 800, 920 1700, 0 2500" fill="none" stroke="#fff" stroke-width="2" />
    </svg>

    <header>
        <div class="container header-content">
            <a href="" class="logo">
                <img src="/images/artsync.png" alt="Art Sync Logo"> </a>
            <nav>
                <div class="divider"></div>
                <ul>
                    <li><a href="#hero">Início</a></li>
                    <li><a href="#features">Funcionalidades</a></li>
                </ul>
            </nav>
            <div class="header-buttons">
                <div class="divider"></div>
                <a href="/login" class="btn-login">Acesso Restrito</a> <a href="/register" class="btn-register">Crie seu
                    acesso</a>
            </div>
        </div>
    </header>

    <main>
        <section id="hero">
            <div class="container hero-content">
                <h1>Sua música, sua visão, seus dados.</h1>
                <p>Transforme sua paixão em uma carreira profissional com a primeira plataforma de gestão gratuita que
                    une dados, IA e estratégia para artistas independentes.</p>
                <a href="/register" class="cta-button" id="cta-button">Comece Agora, de graça</a>
            </div>
        </section>

        <section id="features">
            <div class="container">
                <h2>O Dashboard que Impulsiona sua Arte</h2>
                <p class="section-subtitle">Visualize seu crescimento, entenda seu público e tome decisões estratégicas
                    com ferramentas desenhadas para o artista moderno.</p>

                <div class="features-grid">
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-chart-line"></i></div>
                        <h3>Dashboard Inteligente</h3>
                        <p>Acesse métricas de engajamento, integre seu Spotify e veja um resumo semanal da sua
                            performance. Seus números, decodificados.</p>
                    </div>
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-id-card"></i></div>
                        <h3>Portfólio Profissional</h3>
                        <p>Armazene e organize suas melhores fotos, vídeos e releases. Crie um EPK (Electronic Press
                            Kit) dinâmico e pronto para impressionar contratantes.</p>
                    </div>
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-robot"></i></div>
                        <h3>Estratégia com IA</h3>
                        <p>Receba insights e sugestões da nossa inteligência artificial para planejar seus próximos
                            passos, otimizar postagens e definir metas.</p>
                    </div>
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-share-nodes"></i></div>
                        <h3>Conexão e Engajamento</h3>
                        <p>Conecte suas redes sociais, crie enquetes para seus fãs e entenda o que seu público realmente
                            quer ouvir. Transforme seguidores em fãs.</p>
                    </div>
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                        <h3>Planejamento e Organização</h3>
                        <p>Nunca mais perca um prazo. Agende lembretes para suas atividades, planeje seus lançamentos e
                            mantenha sua carreira nos trilhos.</p>
                    </div>
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-book-open"></i></div>
                        <h3>Recursos Educacionais</h3>
                        <p>Acesse tutoriais e guias práticos, desde dicas de produção musical até estratégias de
                            marketing digital para alavancar sua carreira.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Art Sync. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="/js/landing.js"></script>

</body>

</html>