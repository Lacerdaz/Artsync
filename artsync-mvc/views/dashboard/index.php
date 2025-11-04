<?php require __DIR__ . '/../layouts/header.php'; // Carrega header e menu ?>

<header class="main-header">
    <h2>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['artist_name'] ?? 'Artista'); ?>!</h2>
    <p>Aqui está um resumo da sua carreira.</p>
</header>

<div class="card chart-container">
    <h3>Visão Geral de Streams (Últimos 30 dias)</h3>
    <canvas id="streamsChart"></canvas> 
</div>

<div class="widgets-grid">
    <a href="/schedule" class="widget-link">
        <div class="widget">
            <h3>Próximos Eventos</h3>
            <p>Acompanhe seus próximos shows e compromissos.</p>
        </div>
    </a>
    <a href="/connect-spotify" class="widget-link"> 
        <div class="widget">
            <h3>Métricas do Spotify</h3>
            <p>Integração pendente.</p>
        </div>
    </a>
    <a href="/ai" class="widget-link">
        <div class="widget">
            <h3>IA de Carreira</h3>
            <p>Receba dicas para sua carreira.</p>
        </div>
    </a>
</div>

<script>
    // Espera o HTML carregar completamente antes de tentar desenhar
    document.addEventListener('DOMContentLoaded', (event) => {
        // Encontra o elemento <canvas> pelo seu ID
        const streamsChartCanvas = document.getElementById('streamsChart');
        
        // Verifica se o canvas realmente existe nesta página
        if (streamsChartCanvas) {
            const ctx = streamsChartCanvas.getContext('2d');

            // DADOS DE EXEMPLO (Substituir por dados reais no futuro)
            const labels = ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'];
            const spotifyData = [1200, 1900, 3000, 5000];
            const appleMusicData = [800, 1200, 2500, 3200];
            const deezerData = [400, 500, 900, 1100];

            // Cria a nova instância do gráfico usando Chart.js
            new Chart(ctx, {
                type: 'line', // Tipo do gráfico (linha)
                data: {
                    labels: labels, // Rótulos do eixo X
                    datasets: [ // Conjuntos de dados (cada linha do gráfico)
                        {
                            label: 'Spotify Streams',
                            data: spotifyData,
                            borderColor: '#1DB954', // Cor verde do Spotify
                            backgroundColor: 'rgba(29, 185, 84, 0.1)', // Área preenchida
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4 // Curvatura da linha
                        }, 
                        {
                            label: 'Apple Music Streams',
                            data: appleMusicData,
                            borderColor: '#FC3C44', // Cor vermelha da Apple
                            backgroundColor: 'rgba(252, 60, 68, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }, 
                        {
                            label: 'Deezer Streams',
                            data: deezerData,
                            borderColor: '#AAAAAA', // Cor cinza para Deezer
                            backgroundColor: 'rgba(170, 170, 170, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true, // Faz o gráfico se adaptar ao tamanho do container
                    maintainAspectRatio: false, // Permite controlar a altura via CSS se necessário
                    plugins: {
                        legend: { 
                            labels: { 
                                color: 'rgba(255, 255, 255, 1)' // Cor do texto da legenda (branco)
                            } 
                        }
                    },
                    scales: { // Configuração dos eixos
                        y: { // Eixo Y (vertical)
                            beginAtZero: true, // Começa em zero
                            ticks: { color: 'rgba(255, 255, 255, 1)' }, // Cor dos números
                            grid: { color: 'rgba(255, 255, 255, 1)' } // Cor das linhas de grade
                        },
                        x: { // Eixo X (horizontal)
                            ticks: { color: 'rgba(255, 255, 255, 1)' }, // Cor dos labels
                            grid: { color: 'rgba(255, 255, 255, 1)' } 
                        }
                    }
                }
            });
        } else {
            console.error("Elemento <canvas id='streamsChart'> não encontrado.");
        }
    });
</script>



<?php require __DIR__ . '/../layouts/footer.php'; // Carrega o final do HTML ?>