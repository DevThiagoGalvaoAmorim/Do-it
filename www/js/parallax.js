document.addEventListener('DOMContentLoaded', function() {
    // Seleciona todos os elementos que terão efeito parallax
    const parallaxElements = document.querySelectorAll('.parallax');
    const parallaxBg = document.querySelector('.parallax-bg');
    
    let ticking = false;
    let lastScrollPosition = 0;
    
    // Função para aplicar o efeito parallax com melhor performance
    function applyParallax() {
        lastScrollPosition = window.pageYOffset;
        
        if (!ticking) {
            window.requestAnimationFrame(() => {
                parallaxElements.forEach(element => {
                    const speed = element.getAttribute('data-speed') || 0.5;
                    const yPos = -(lastScrollPosition * speed);
                    element.style.transform = `translateY(${yPos}px)`;
                });
                
                // Aplicar efeito ao fundo se existir
                if (parallaxBg) {
                    const speed = parallaxBg.getAttribute('data-speed') || 0.2;
                    const yPos = -(lastScrollPosition * speed);
                    parallaxBg.style.transform = `translateY(${yPos}px)`;
                }
                
                ticking = false;
            });
            
            ticking = true;
        }
    }
    
    // Adiciona o evento de scroll com throttling para melhor performance
    window.addEventListener('scroll', applyParallax, { passive: true });
    
    // Aplica o efeito ao carregar a página
    applyParallax();
    
    // Posiciona corretamente os elementos de parallax na tela de login
    positionLoginElements();
    
    // Anima os elementos de transição ao carregar a página
    const transitionElements = document.querySelectorAll('.transition-element');
    transitionElements.forEach((element, index) => {
        const delay = index * 0.1;
        element.style.transitionDelay = `${delay}s`;
    });
});

// Função para posicionar corretamente os elementos na tela de login
function positionLoginElements() {
    const stars = document.querySelector('.stars');
    const planet = document.querySelector('.planet');
    
    if (stars) {
        stars.style.position = 'fixed';
        stars.style.top = '0';
        stars.style.left = '0';
        stars.style.width = '100%';
        stars.style.height = '100%';
        stars.style.zIndex = '-2';
    }
    
    if (planet) {
        planet.style.position = 'fixed';
        planet.style.bottom = '-50px';
        planet.style.right = '50px';
        planet.style.zIndex = '-1';
    }
}

// Função para transições com efeito parallax
function parallaxTransition(targetPage) {
    // Adiciona classe para iniciar a animação
    document.body.classList.add('page-transition');
    
    // Seleciona elementos para animar durante a transição
    const elements = document.querySelectorAll('.transition-element');
    
    // Verifica se existem elementos para animar
    if (elements.length === 0) {
        // Se não houver elementos, apenas redireciona
        window.location.href = targetPage;
        return false;
    }
    
    // Anima cada elemento com velocidades diferentes
    elements.forEach((element, index) => {
        const delay = index * 0.1;
        element.style.transitionDelay = `${delay}s`;
        element.classList.add('animate-out');
    });
    
    // Redireciona após a animação terminar
    setTimeout(() => {
        window.location.href = targetPage;
    }, 800); // Tempo suficiente para a animação terminar
    
    // Previne o comportamento padrão do link
    return false;
}


function initParallax() {
    const parallaxElements = document.querySelectorAll('.parallax');
    
    document.addEventListener('mousemove', function(e) {
        const x = e.clientX;
        const y = e.clientY;
        
        parallaxElements.forEach(element => {
            const speed = element.getAttribute('data-speed');
            
            const xOffset = (window.innerWidth / 2 - x) * speed;
            const yOffset = (window.innerHeight / 2 - y) * speed;
            
            element.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
        });
    });
}

// Inicializar o efeito quando o documento estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    initParallax();
});