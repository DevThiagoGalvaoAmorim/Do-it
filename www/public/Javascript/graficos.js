class PieChartSVG {
    constructor(containerId, data, colors, labels, title = '') {
        this.container = document.getElementById(containerId);
        this.data = data;
        this.colors = colors;
        this.labels = labels;
        this.title = title;
        this.radius = 120;
        this.centerX = 200;
        this.centerY = 200;
        this.animationDuration = 1000;
        this.init();
    }

    init() {
        this.container.innerHTML = '';
        this.createChart();
        this.createLegend();
        this.animateChart();
    }

    polarToCartesian(centerX, centerY, radius, angleInDegrees) {
        const angleInRadians = (angleInDegrees - 90) * Math.PI / 180.0;
        return {
            x: centerX + (radius * Math.cos(angleInRadians)),
            y: centerY + (radius * Math.sin(angleInRadians))
        };
    }

    describeArc(x, y, radius, startAngle, endAngle) {
        const start = this.polarToCartesian(x, y, radius, endAngle);
        const end = this.polarToCartesian(x, y, radius, startAngle);
        const largeArcFlag = endAngle - startAngle <= 180 ? "0" : "1";
        
        return [
            "M", x, y,
            "L", start.x, start.y,
            "A", radius, radius, 0, largeArcFlag, 0, end.x, end.y,
            "Z"
        ].join(" ");
    }

    createChart() {
        const total = this.data.reduce((sum, value) => sum + value, 0);
        let currentAngle = 0;

        this.data.forEach((value, index) => {
            const percentage = (value / total) * 100;
            const angle = (value / total) * 360;
            
            if (angle > 0) {
                const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                const pathData = this.describeArc(this.centerX, this.centerY, this.radius, currentAngle, currentAngle + angle);
                
                path.setAttribute("d", pathData);
                path.setAttribute("fill", this.colors[index]);
                path.setAttribute("stroke", "#ffffff");
                path.setAttribute("stroke-width", "2");
                path.setAttribute("class", "pie-slice");
                path.setAttribute("data-index", index);
                path.setAttribute("data-label", this.labels[index]);
                path.setAttribute("data-value", value);
                path.setAttribute("data-percentage", percentage.toFixed(1));
                
                // Adicionar animação inicial
                path.style.transformOrigin = `${this.centerX}px ${this.centerY}px`;
                path.style.transform = "scale(0)";
                
                // Event listeners
                path.addEventListener('mouseenter', (e) => this.onSliceHover(e, true));
                path.addEventListener('mouseleave', (e) => this.onSliceHover(e, false));
                path.addEventListener('click', (e) => this.onSliceClick(e));
                
                this.container.appendChild(path);
                currentAngle += angle;
            }
        });

        // Adicionar título no centro
        if (this.title) {
            const titleText = document.createElementNS("http://www.w3.org/2000/svg", "text");
            titleText.setAttribute("x", this.centerX);
            titleText.setAttribute("y", this.centerY - 10);
            titleText.setAttribute("text-anchor", "middle");
            titleText.setAttribute("dominant-baseline", "middle");
            titleText.setAttribute("font-size", "16");
            titleText.setAttribute("font-weight", "bold");
            titleText.setAttribute("fill", "#333");
            titleText.textContent = this.title;
            this.container.appendChild(titleText);
        }
    }

    createLegend() {
        const legendContainer = document.getElementById('chartLegend');
        legendContainer.innerHTML = '';
        
        this.labels.forEach((label, index) => {
            const legendItem = document.createElement('div');
            legendItem.className = 'legend-item';
            legendItem.setAttribute('data-index', index);
            
            const colorBox = document.createElement('div');
            colorBox.className = 'legend-color';
            colorBox.style.backgroundColor = this.colors[index];
            
            const labelText = document.createElement('span');
            labelText.className = 'legend-label';
            labelText.textContent = `${label}: ${this.data[index]}`;
            
            legendItem.appendChild(colorBox);
            legendItem.appendChild(labelText);
            
            // Event listeners para destacar fatia correspondente
            legendItem.addEventListener('mouseenter', () => this.highlightSlice(index, true));
            legendItem.addEventListener('mouseleave', () => this.highlightSlice(index, false));
            
            legendContainer.appendChild(legendItem);
        });
    }

    animateChart() {
        const slices = this.container.querySelectorAll('.pie-slice');
        slices.forEach((slice, index) => {
            setTimeout(() => {
                slice.style.transition = `transform ${this.animationDuration / slices.length}ms ease-out`;
                slice.style.transform = "scale(1)";
            }, index * (this.animationDuration / slices.length));
        });
    }

    onSliceHover(event, isEntering) {
        const slice = event.target;
        const index = parseInt(slice.getAttribute('data-index'));
        
        if (isEntering) {
            slice.style.transform = "scale(1.05)";
            slice.style.filter = "brightness(1.1)";
            this.showTooltip(event, slice);
            this.highlightLegendItem(index, true);
        } else {
            slice.style.transform = "scale(1)";
            slice.style.filter = "brightness(1)";
            this.hideTooltip();
            this.highlightLegendItem(index, false);
        }
    }

    onSliceClick(event) {
        const slice = event.target;
        const label = slice.getAttribute('data-label');
        const value = slice.getAttribute('data-value');
        const percentage = slice.getAttribute('data-percentage');
        
        alert(`Você clicou em: ${label}\nQuantidade: ${value}\nPercentual do total: ${percentage}%`);
    }

    showTooltip(event, slice) {
        const tooltip = this.getOrCreateTooltip();
        const label = slice.getAttribute('data-label');
        const value = slice.getAttribute('data-value');
        const percentage = slice.getAttribute('data-percentage');
        
        tooltip.innerHTML = `
            <strong>${label}</strong><br>
            Quantidade: ${value}<br>
            ${percentage}% do total
        `;
        
        tooltip.style.display = 'block';
        tooltip.style.left = event.pageX + 10 + 'px';
        tooltip.style.top = event.pageY - 10 + 'px';
    }

    hideTooltip() {
        const tooltip = document.getElementById('chart-tooltip');
        if (tooltip) {
            tooltip.style.display = 'none';
        }
    }

    getOrCreateTooltip() {
        let tooltip = document.getElementById('chart-tooltip');
        if (!tooltip) {
            tooltip = document.createElement('div');
            tooltip.id = 'chart-tooltip';
            tooltip.className = 'chart-tooltip';
            document.body.appendChild(tooltip);
        }
        return tooltip;
    }

    highlightSlice(index, highlight) {
        const slice = this.container.querySelector(`[data-index="${index}"]`);
        if (slice) {
            if (highlight) {
                slice.style.transform = "scale(1.05)";
                slice.style.filter = "brightness(1.1)";
            } else {
                slice.style.transform = "scale(1)";
                slice.style.filter = "brightness(1)";
            }
        }
    }

    highlightLegendItem(index, highlight) {
        const legendItem = document.querySelector(`#chartLegend .legend-item[data-index="${index}"]`);
        if (legendItem) {
            if (highlight) {
                legendItem.style.backgroundColor = '#f0f0f0';
            } else {
                legendItem.style.backgroundColor = 'transparent';
            }
        }
    }

    // Método para atualizar dados do gráfico
    updateData(data, labels, title = '') {
        this.data = data;
        this.labels = labels;
        this.title = title;
        this.init();
    }
}

// Variável global para armazenar a instância do gráfico
let pieChartInstance = null;

// Função para carregar dados do banco e atualizar o gráfico
function loadDatabaseData() {
    fetch('/controllers/api_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            functions: ['getCountNotas', 'getCountLembrete']
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Dados recebidos:', data);
        
        // Extrair contadores dos dados retornados
        const notasCount = data.getCountNotas && data.getCountNotas[0] ? parseInt(Object.values(data.getCountNotas[0])[0]) : 0;
        const lembretesCount = data.getCountLembrete && data.getCountLembrete[0] ? parseInt(Object.values(data.getCountLembrete[0])[0]) : 0;
        
        console.log('Notas:', notasCount, 'Lembretes:', lembretesCount);
        
        // Se ambos forem zero, mostrar dados de exemplo
        if (notasCount === 0 && lembretesCount === 0) {
            const exampleData = [1, 1]; // Dados de exemplo
            const exampleLabels = ['Notas', 'Lembretes'];
            const colors = ['#36A2EB', '#FF6384'];
            
            if (pieChartInstance) {
                pieChartInstance.updateData(exampleData, exampleLabels, 'Dados do Sistema (Exemplo)');
            } else {
                pieChartInstance = new PieChartSVG(
                    'pieChart',
                    exampleData,
                    colors,
                    exampleLabels,
                    'Dados do Sistema (Exemplo)'
                );
            }
        } else {
            // Usar dados reais do banco
            const realData = [notasCount, lembretesCount];
            const realLabels = ['Notas', 'Lembretes'];
            const colors = ['#36A2EB', '#FF6384'];
            
            if (pieChartInstance) {
                pieChartInstance.updateData(realData, realLabels, 'Dados do Sistema');
            } else {
                pieChartInstance = new PieChartSVG(
                    'pieChart',
                    realData,
                    colors,
                    realLabels
                );
            }
        }
    })
    .catch(error => {
        console.error('Erro ao carregar dados:', error);
        
        // Em caso de erro, usar dados de exemplo
        const fallbackData = [1, 1];
        const fallbackLabels = ['Notas', 'Lembretes'];
        const colors = ['#36A2EB', '#FF6384'];
        
        if (pieChartInstance) {
            pieChartInstance.updateData(fallbackData, fallbackLabels, 'Dados do Sistema (Erro)');
        } else {
            pieChartInstance = new PieChartSVG(
                'pieChart',
                fallbackData,
                colors,
                fallbackLabels,
                'Dados do Sistema (Erro)'
            );
        }
    });
}

class LineChartSVG {
    constructor(containerId, data, labels, title = '', color = '#36A2EB') {
        this.containerId = containerId;
        this.data = data;
        this.labels = labels;
        this.title = title;
        this.color = color;
        this.width = 800;
        this.height = 400;
        this.padding = { top: 40, right: 40, bottom: 60, left: 60 };
        this.chartWidth = this.width - this.padding.left - this.padding.right;
        this.chartHeight = this.height - this.padding.top - this.padding.bottom;
        
        this.init();
    }

    init() {
        this.container = document.getElementById(this.containerId);
        if (!this.container) {
            console.error(`Container ${this.containerId} não encontrado`);
            return;
        }
        this.container.innerHTML = '';
        this.createChart();
    }

    createChart() {
        const maxValue = Math.max(...this.data);
        const minValue = Math.min(...this.data);
        const valueRange = maxValue - minValue || 1;
        
        // Criar pontos da linha
        const points = this.data.map((value, index) => {
            const x = this.padding.left + (index * this.chartWidth / (this.data.length - 1));
            const y = this.padding.top + this.chartHeight - ((value - minValue) / valueRange * this.chartHeight);
            return { x, y, value, label: this.labels[index] };
        });

        // Criar linha
        const pathData = points.map((point, index) => {
            return `${index === 0 ? 'M' : 'L'} ${point.x} ${point.y}`;
        }).join(' ');

        const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
        path.setAttribute("d", pathData);
        path.setAttribute("stroke", this.color);
        path.setAttribute("stroke-width", "3");
        path.setAttribute("fill", "none");
        path.setAttribute("stroke-linecap", "round");
        path.setAttribute("stroke-linejoin", "round");
        this.container.appendChild(path);

        // Criar área sob a linha (gradiente)
        const areaPath = pathData + ` L ${points[points.length - 1].x} ${this.padding.top + this.chartHeight} L ${points[0].x} ${this.padding.top + this.chartHeight} Z`;
        const area = document.createElementNS("http://www.w3.org/2000/svg", "path");
        area.setAttribute("d", areaPath);
        area.setAttribute("fill", this.color);
        area.setAttribute("fill-opacity", "0.1");
        this.container.appendChild(area);

        // Criar pontos
        points.forEach((point, index) => {
            const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
            circle.setAttribute("cx", point.x);
            circle.setAttribute("cy", point.y);
            circle.setAttribute("r", "6");
            circle.setAttribute("fill", this.color);
            circle.setAttribute("stroke", "white");
            circle.setAttribute("stroke-width", "2");
            circle.style.cursor = "pointer";
            
            // Event listeners para pontos
            circle.addEventListener('mouseenter', (e) => this.showLineTooltip(e, point));
            circle.addEventListener('mouseleave', () => this.hideLineTooltip());
            
            this.container.appendChild(circle);
        });

        // Criar eixos
        this.createAxes(points, minValue, maxValue);

        // Criar título
        if (this.title) {
            const titleText = document.createElementNS("http://www.w3.org/2000/svg", "text");
            titleText.setAttribute("x", this.width / 2);
            titleText.setAttribute("y", 25);
            titleText.setAttribute("text-anchor", "middle");
            titleText.setAttribute("font-size", "18");
            titleText.setAttribute("font-weight", "bold");
            titleText.setAttribute("fill", "#333");
            titleText.textContent = this.title;
            this.container.appendChild(titleText);
        }
    }

    createAxes(points, minValue, maxValue) {
        // Eixo X
        const xAxis = document.createElementNS("http://www.w3.org/2000/svg", "line");
        xAxis.setAttribute("x1", this.padding.left);
        xAxis.setAttribute("y1", this.padding.top + this.chartHeight);
        xAxis.setAttribute("x2", this.padding.left + this.chartWidth);
        xAxis.setAttribute("y2", this.padding.top + this.chartHeight);
        xAxis.setAttribute("stroke", "#ddd");
        xAxis.setAttribute("stroke-width", "1");
        this.container.appendChild(xAxis);

        // Eixo Y
        const yAxis = document.createElementNS("http://www.w3.org/2000/svg", "line");
        yAxis.setAttribute("x1", this.padding.left);
        yAxis.setAttribute("y1", this.padding.top);
        yAxis.setAttribute("x2", this.padding.left);
        yAxis.setAttribute("y2", this.padding.top + this.chartHeight);
        yAxis.setAttribute("stroke", "#ddd");
        yAxis.setAttribute("stroke-width", "1");
        this.container.appendChild(yAxis);

        // Labels do eixo X
        points.forEach((point, index) => {
            const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
            text.setAttribute("x", point.x);
            text.setAttribute("y", this.padding.top + this.chartHeight + 20);
            text.setAttribute("text-anchor", "middle");
            text.setAttribute("font-size", "12");
            text.setAttribute("fill", "#666");
            text.textContent = point.label;
            this.container.appendChild(text);
        });

        // Labels do eixo Y
        const steps = 5;
        for (let i = 0; i <= steps; i++) {
            const value = minValue + (maxValue - minValue) * (i / steps);
            const y = this.padding.top + this.chartHeight - (i / steps * this.chartHeight);
            
            const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
            text.setAttribute("x", this.padding.left - 10);
            text.setAttribute("y", y + 4);
            text.setAttribute("text-anchor", "end");
            text.setAttribute("font-size", "12");
            text.setAttribute("fill", "#666");
            text.textContent = Math.round(value);
            this.container.appendChild(text);

            // Linhas de grade horizontais
            if (i > 0) {
                const gridLine = document.createElementNS("http://www.w3.org/2000/svg", "line");
                gridLine.setAttribute("x1", this.padding.left);
                gridLine.setAttribute("y1", y);
                gridLine.setAttribute("x2", this.padding.left + this.chartWidth);
                gridLine.setAttribute("y2", y);
                gridLine.setAttribute("stroke", "#f0f0f0");
                gridLine.setAttribute("stroke-width", "1");
                this.container.appendChild(gridLine);
            }
        }
    }

    showLineTooltip(event, point) {
        const tooltip = this.getOrCreateLineTooltip();
        tooltip.innerHTML = `
            <strong>${point.label}</strong><br>
            Usuários: ${point.value}
        `;
        
        tooltip.style.display = 'block';
        tooltip.style.left = event.pageX + 10 + 'px';
        tooltip.style.top = event.pageY - 10 + 'px';
    }

    hideLineTooltip() {
        const tooltip = document.getElementById('line-chart-tooltip');
        if (tooltip) {
            tooltip.style.display = 'none';
        }
    }

    getOrCreateLineTooltip() {
        let tooltip = document.getElementById('line-chart-tooltip');
        if (!tooltip) {
            tooltip = document.createElement('div');
            tooltip.id = 'line-chart-tooltip';
            tooltip.className = 'chart-tooltip';
            document.body.appendChild(tooltip);
        }
        return tooltip;
    }

    updateData(data, labels, title = '') {
        this.data = data;
        this.labels = labels;
        this.title = title;
        this.init();
    }
}

// Variável global para armazenar a instância do gráfico de linha
let lineChartInstance = null;

// Função para carregar dados de usuários por mês
function loadUsersByMonthData() {
    fetch('/controllers/api_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            functions: ['getUsuariosPorMes']
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Dados de usuários por mês:', data);
        
        const monthsData = data.getUsuariosPorMes || [];
        
        if (monthsData.length > 0) {
            const labels = monthsData.map(item => {
                const meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 
                              'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
                return `${meses[item.mes - 1]}/${item.ano}`;
            });
            
            const values = monthsData.map(item => parseInt(item.total));
            
            if (lineChartInstance) {
                lineChartInstance.updateData(values, labels, 'Novos Usuários Por Mês');
            } else {
                lineChartInstance = new LineChartSVG(
                    'lineChart',
                    values,
                    labels,
                    'Novos Usuários Por Mês',
                    '#36A2EB'
                );
            }
        } else {
            // Dados de exemplo se não houver dados
            const exampleData = [2, 5, 3, 8, 6];
            const exampleLabels = ['Jan/2025', 'Fev/2025', 'Mar/2025', 'Abr/2025', 'Mai/2025'];
            
            if (lineChartInstance) {
                lineChartInstance.updateData(exampleData, exampleLabels, 'Novos Usuários Por Mês (Exemplo)');
            } else {
                lineChartInstance = new LineChartSVG(
                    'lineChart',
                    exampleData,
                    exampleLabels,
                    'Novos Usuários Por Mês (Exemplo)',
                    '#36A2EB'
                );
            }
        }
    })
    .catch(error => {
        console.error('Erro ao carregar dados de usuários por mês:', error);
        
        // Dados de fallback em caso de erro
        const fallbackData = [1, 2, 1, 3, 2];
        const fallbackLabels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai'];
        
        if (lineChartInstance) {
            lineChartInstance.updateData(fallbackData, fallbackLabels, 'Usuários Por Mês (Erro)');
        } else {
            lineChartInstance = new LineChartSVG(
                'lineChart',
                fallbackData,
                fallbackLabels,
                'Usuários Por Mês (Erro)',
                '#FF6384'
            );
        }
    });
}

// Inicializar o gráfico quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    loadDatabaseData();
    loadUsersByMonthData();
});