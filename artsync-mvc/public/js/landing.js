document.addEventListener('DOMContentLoaded', function() {
    // Animação do SVG "cabo" conforme o scroll
    const path = document.querySelector('#cable-path');
    const glowPath = document.querySelector('#cable-glow');

    // Verifica se os elementos SVG existem antes de continuar
    if (!path || !glowPath) {
        console.warn("Elementos SVG #cable-path ou #cable-glow não encontrados.");
        return; 
    }

    const pathLength = path.getTotalLength();

    // Inicializa os paths como "não desenhados"
    path.style.strokeDasharray = pathLength;
    path.style.strokeDashoffset = pathLength;
    glowPath.style.strokeDasharray = pathLength;
    glowPath.style.strokeDashoffset = pathLength;

    const handleScroll = () => {
        // Calcula a porcentagem de scroll da página
        const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        const scrollHeight = (document.documentElement.scrollHeight || document.body.scrollHeight) - document.documentElement.clientHeight;
        
        // Evita divisão por zero se a página não tiver scroll
        const scrollPercentage = scrollHeight > 0 ? scrollTop / scrollHeight : 0;
        
        // Calcula quanto do path deve ser desenhado
        const drawLength = pathLength * scrollPercentage;

        // Atualiza o desenho (strokeDashoffset)
        // Usamos Math.max para garantir que o offset nunca seja negativo
        path.style.strokeDashoffset = Math.max(0, pathLength - drawLength);
        glowPath.style.strokeDashoffset = Math.max(0, pathLength - drawLength);
    };

    // Chama a função uma vez para definir o estado inicial
    handleScroll();
    
    // Adiciona o listener para o evento de scroll
    window.addEventListener('scroll', handleScroll, { passive: true }); // Otimização para performance
});