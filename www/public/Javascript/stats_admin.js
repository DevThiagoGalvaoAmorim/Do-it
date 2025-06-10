class StatsCounter {
    constructor(elementId, targetValue, duration = 2000) {
        this.element = document.getElementById(elementId);
        this.targetValue = targetValue;
        this.duration = duration;
        this.currentValue = 0;
    }

    animateCount() {
        const startTime = Date.now();
        const startValue = this.currentValue;
        const valueDifference = this.targetValue - startValue;

        const animate = () => {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / this.duration, 1);
            
            // Usar easing para uma animação mais suave
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            
            this.currentValue = Math.floor(startValue + (valueDifference * easeOutQuart));
            this.element.textContent = this.currentValue.toLocaleString('pt-BR');
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                this.currentValue = this.targetValue;
                this.element.textContent = this.targetValue.toLocaleString('pt-BR');
            }
        };

        requestAnimationFrame(animate);
    }

    updateValue(newValue, animate = true) {
        if (animate) {
            this.targetValue = newValue;
            this.animateCount();
        } else {
            this.currentValue = newValue;
            this.targetValue = newValue;
            this.element.textContent = newValue.toLocaleString('pt-BR');
        }
    }

    // Adicionar efeito de pulsação quando o valor muda
    pulseEffect() {
        this.element.style.transform = 'scale(1.1)';
        this.element.style.transition = 'transform 0.3s ease';
        
        setTimeout(() => {
            this.element.style.transform = 'scale(1)';
        }, 300);
    }
}

// Instâncias dos contadores
let userCounter = null;
let notesCounter = null;

// Função para carregar dados dos contadores
function loadStatsCounters() {
    fetch('/controllers/api_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            functions: ['getCountUsuarios', 'getCountNotas', 'getCountLembrete']
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Dados dos contadores:', data);
        
        // Extrair contadores dos dados retornados
        const usersCount = data.getCountUsuarios && data.getCountUsuarios[0] 
            ? parseInt(Object.values(data.getCountUsuarios[0])[0]) : 0;
        
        const notasCount = data.getCountNotas && data.getCountNotas[0] 
            ? parseInt(Object.values(data.getCountNotas[0])[0]) : 0;
        
        const lembretesCount = data.getCountLembrete && data.getCountLembrete[0] 
            ? parseInt(Object.values(data.getCountLembrete[0])[0]) : 0;
        
        const totalNotesLembretes = notasCount + lembretesCount;
        
        console.log('Usuários:', usersCount, 'Total Notas/Lembretes:', totalNotesLembretes);
        
        // Inicializar contadores na primeira carga
        if (!userCounter) {
            userCounter = new StatsCounter('userCount', usersCount);
            userCounter.animateCount();
        }
        
        if (!notesCounter) {
            notesCounter = new StatsCounter('notesCount', totalNotesLembretes);
            notesCounter.animateCount();
        }
    })
    .catch(error => {
        console.error('Erro ao carregar contadores:', error);
        
        // Valores de fallback em caso de erro
        if (!userCounter) {
            userCounter = new StatsCounter('userCount', 0);
            userCounter.updateValue(0, false);
        }
        
        if (!notesCounter) {
            notesCounter = new StatsCounter('notesCount', 0);
            notesCounter.updateValue(0, false);
        }
    });
}

// Inicializar quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    console.log('Stats Admin JS carregado');
    
    // Carregar dados apenas na inicialização
    loadStatsCounters();
});