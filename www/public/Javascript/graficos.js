class PieChartSVG {
  constructor(containerId, data, colors, labels, title = "") {
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
    this.container.innerHTML = "";
    this.createChart();
    this.createLegend();
    this.animateChart();
  }

  polarToCartesian(centerX, centerY, radius, angleInDegrees) {
    const angleInRadians = ((angleInDegrees - 90) * Math.PI) / 180.0;
    return {
      x: centerX + radius * Math.cos(angleInRadians),
      y: centerY + radius * Math.sin(angleInRadians),
    };
  }

  describeArc(x, y, radius, startAngle, endAngle) {
    const start = this.polarToCartesian(x, y, radius, endAngle);
    const end = this.polarToCartesian(x, y, radius, startAngle);
    const largeArcFlag = endAngle - startAngle <= 180 ? "0" : "1";

    return [
      "M",
      x,
      y,
      "L",
      start.x,
      start.y,
      "A",
      radius,
      radius,
      0,
      largeArcFlag,
      0,
      end.x,
      end.y,
      "Z",
    ].join(" ");
  }

  createChart() {
    this.container.innerHTML = "";

    let currentAngle = 0;
    const total = this.data.reduce((sum, value) => sum + value, 0);

    this.data.forEach((value, index) => {
      const percentage = (value / total) * 100;
      const sliceAngle = (value / total) * 360;

      const startAngle = currentAngle;
      const endAngle = currentAngle + sliceAngle;

      const start = this.polarToCartesian(
        this.centerX,
        this.centerY,
        this.radius,
        endAngle
      );
      const end = this.polarToCartesian(
        this.centerX,
        this.centerY,
        this.radius,
        startAngle
      );

      const largeArcFlag = sliceAngle <= 180 ? "0" : "1";

      const pathData = [
        "M",
        this.centerX,
        this.centerY,
        "L",
        start.x,
        start.y,
        "A",
        this.radius,
        this.radius,
        0,
        largeArcFlag,
        0,
        end.x,
        end.y,
        "Z",
      ].join(" ");

      const path = document.createElementNS(
        "http://www.w3.org/2000/svg",
        "path"
      );
      path.setAttribute("d", pathData);
      path.setAttribute("fill", this.colors[index % this.colors.length]);
      path.setAttribute("class", "pie-slice");
      path.setAttribute("data-index", index);
      path.setAttribute("data-value", value);
      path.setAttribute("data-percentage", percentage.toFixed(1));
      path.setAttribute("data-label", this.labels[index]);

      // Event listeners
      path.addEventListener("mouseenter", (e) => this.onSliceHover(e, true));
      path.addEventListener("mouseleave", (e) => this.onSliceHover(e, false));
      path.addEventListener("click", (e) => this.onSliceClick(e));

      this.container.appendChild(path);

      currentAngle += sliceAngle;
    });
  }

  createLegend() {
    const legendContainer = document.getElementById("chartLegend");
    legendContainer.innerHTML = "";

    this.labels.forEach((label, index) => {
      const legendItem = document.createElement("div");
      legendItem.className = "legend-item";
      legendItem.setAttribute("data-index", index);

      const colorBox = document.createElement("div");
      colorBox.className = "legend-color";
      colorBox.style.backgroundColor = this.colors[index];

      const labelText = document.createElement("span");
      labelText.className = "legend-label";
      labelText.textContent = `${label}: ${this.data[index]}`;

      legendItem.appendChild(colorBox);
      legendItem.appendChild(labelText);

      // Event listeners para destacar fatia correspondente
      legendItem.addEventListener("mouseenter", () =>
        this.highlightSlice(index, true)
      );
      legendItem.addEventListener("mouseleave", () =>
        this.highlightSlice(index, false)
      );

      legendContainer.appendChild(legendItem);
    });
  }

  animateChart() {
    const slices = this.container.querySelectorAll(".pie-slice");
    slices.forEach((slice, index) => {
      setTimeout(() => {
        slice.style.transition = `transform ${
          this.animationDuration / slices.length
        }ms ease-out`;
        slice.style.transform = "scale(1)";
      }, index * (this.animationDuration / slices.length));
    });
  }

  onSliceHover(event, isEntering) {
    const slice = event.target;
    const index = parseInt(slice.getAttribute("data-index"));

    if (isEntering) {
      slice.style.transformOrigin = `${this.centerX}px ${this.centerY}px`;
      slice.style.transform = "scale(1.1)";
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
    const label = slice.getAttribute("data-label");
    const value = slice.getAttribute("data-value");
    const percentage = slice.getAttribute("data-percentage");

    alert(
      `Você clicou em: ${label}\nQuantidade: ${value}\nPercentual do total: ${percentage}%`
    );
  }

  showTooltip(event, slice) {
    const tooltip = this.getOrCreateTooltip();
    const label = slice.getAttribute("data-label");
    const value = slice.getAttribute("data-value");
    const percentage = slice.getAttribute("data-percentage");

    tooltip.innerHTML = `
            <strong>${label}</strong><br>
            Quantidade: ${value}<br>
            ${percentage}% do total
        `;

    tooltip.style.display = "block";
    tooltip.style.left = event.pageX + 10 + "px";
    tooltip.style.top = event.pageY - 10 + "px";
  }

  hideTooltip() {
    const tooltip = document.getElementById("chart-tooltip");
    if (tooltip) {
      tooltip.style.display = "none";
    }
  }

  getOrCreateTooltip() {
    let tooltip = document.getElementById("chart-tooltip");
    if (!tooltip) {
      tooltip = document.createElement("div");
      tooltip.id = "chart-tooltip";
      tooltip.className = "chart-tooltip";
      document.body.appendChild(tooltip);
    }
    return tooltip;
  }

  highlightSlice(index, highlight) {
    const slice = this.container.querySelector(`[data-index="${index}"]`);
    if (slice) {
      if (highlight) {
        slice.style.transformOrigin = `${this.centerX}px ${this.centerY}px`;
        slice.style.transform = "scale(1.1)";
        slice.style.filter = "brightness(1.1)";
      } else {
        slice.style.transform = "scale(1)";
        slice.style.filter = "brightness(1)";
      }
    }
  }

  highlightLegendItem(index, highlight) {
    const legendItem = document.querySelector(
      `#chartLegend .legend-item[data-index="${index}"]`
    );
    if (legendItem) {
      if (highlight) {
        legendItem.style.backgroundColor = "#f0f0f0";
      } else {
        legendItem.style.backgroundColor = "transparent";
      }
    }
  }

  // Método para atualizar dados do gráfico
  updateData(data, labels, title = "") {
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
  fetch("/controllers/api_data.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      functions: ["getCountNotas", "getCountLembrete"],
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Dados recebidos:", data);

      // Extrair contadores dos dados retornados
      const notasCount =
        data.getCountNotas && data.getCountNotas[0]
          ? parseInt(Object.values(data.getCountNotas[0])[0])
          : 0;
      const lembretesCount =
        data.getCountLembrete && data.getCountLembrete[0]
          ? parseInt(Object.values(data.getCountLembrete[0])[0])
          : 0;

      console.log("Notas:", notasCount, "Lembretes:", lembretesCount);

      // Se ambos forem zero, ocultar gráfico e mostrar apenas a legenda
      if (notasCount === 0 && lembretesCount === 0) {
        // Ocultar o container do gráfico
        const pieChartContainer = document.getElementById("pieChart");
        if (pieChartContainer) {
          pieChartContainer.style.display = "none";
        }

        // Criar apenas a legenda com valores zerados
        createEmptyLegend();
      } else if (notasCount === 0 || lembretesCount === 0) {
        // Se um dos valores for zero, mostrar o gráfico com 100% para o outro
        const pieChartContainer = document.getElementById("pieChart");
        if (pieChartContainer) {
          pieChartContainer.style.display = "block";
        }

        const realData = [notasCount, lembretesCount];
        const realLabels = ["Notas", "Lembretes"];
        const colors = ["#6B46C1", "#C084FC"];

        // Garante que o gráfico mostre 100% para o valor não zero
        const dataForChart = realData.map((v) => (v === 0 ? 0 : 1));
        const labelsForChart = realLabels;
        const valuesForLegend = realData;

        if (pieChartInstance) {
          pieChartInstance.updateData(
            dataForChart,
            labelsForChart,
            "Dados do Sistema"
          );
        } else {
          pieChartInstance = new PieChartSVG(
            "pieChart",
            dataForChart,
            colors,
            labelsForChart
          );
        }

        // Atualiza a legenda para mostrar o valor real
        const legendContainer = document.getElementById("chartLegend");
        if (legendContainer) {
          legendContainer.innerHTML = "";
          labelsForChart.forEach((label, index) => {
            const legendItem = document.createElement("div");
            legendItem.className = "legend-item";

            const colorBox = document.createElement("div");
            colorBox.className = "legend-color";
            colorBox.style.backgroundColor = colors[index];

            const labelText = document.createElement("span");
            labelText.className = "legend-label";
            labelText.textContent = `${label}: ${valuesForLegend[index]}`;

            legendItem.appendChild(colorBox);
            legendItem.appendChild(labelText);
            legendContainer.appendChild(legendItem);
          });
        }
      } else {
        // Mostrar o container do gráfico se estava oculto
        const pieChartContainer = document.getElementById("pieChart");
        if (pieChartContainer) {
          pieChartContainer.style.display = "block";
        }

        // Usar dados reais do banco
        const realData = [notasCount, lembretesCount];
        const realLabels = ["Notas", "Lembretes"];
        const colors = ["#6B46C1", "#C084FC"]; // Roxo para combinar com a imagem

        if (pieChartInstance) {
          pieChartInstance.updateData(realData, realLabels, "Dados do Sistema");
        } else {
          pieChartInstance = new PieChartSVG(
            "pieChart",
            realData,
            colors,
            realLabels
          );
        }
      }
    })
    .catch((error) => {
      console.error("Erro ao carregar dados:", error);

      // Em caso de erro, ocultar gráfico e mostrar legenda zerada
      const pieChartContainer = document.getElementById("pieChart");
      if (pieChartContainer) {
        pieChartContainer.style.display = "none";
      }

      createEmptyLegend();
    });
}

// Função para criar legenda vazia quando não há dados
function createEmptyLegend() {
  const legendContainer = document.getElementById("chartLegend");
  if (!legendContainer) return;

  legendContainer.innerHTML = "";

  const labels = ["Notas", "Lembretes"];
  const colors = ["#6B46C1", "#C084FC"];

  labels.forEach((label, index) => {
    const legendItem = document.createElement("div");
    legendItem.className = "legend-item";

    const colorBox = document.createElement("div");
    colorBox.className = "legend-color";
    colorBox.style.backgroundColor = colors[index];

    const labelText = document.createElement("span");
    labelText.className = "legend-label";
    labelText.textContent = `${label}: 0`;

    legendItem.appendChild(colorBox);
    legendItem.appendChild(labelText);
    legendContainer.appendChild(legendItem);
  });
}

class BarChartSVG {
  constructor(containerId, data, labels, title = "", color = "#6B46C1") {
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
    this.container.innerHTML = "";
    this.createChart();
  }

  createChart() {
    const maxValue = Math.max(...this.data);
    const minValue = 0;
    const valueRange = maxValue || 1;

    const barWidth = (this.chartWidth / this.data.length) * 0.8;
    const barSpacing = (this.chartWidth / this.data.length) * 0.2;

    // Criar barras
    this.data.forEach((value, index) => {
      const barHeight = (value / valueRange) * this.chartHeight;
      const x =
        this.padding.left + index * (barWidth + barSpacing) + barSpacing / 2;
      const y = this.padding.top + this.chartHeight - barHeight;

      // Criar retângulo da barra com bordas arredondadas
      const rect = document.createElementNS(
        "http://www.w3.org/2000/svg",
        "rect"
      );
      rect.setAttribute("x", x);
      rect.setAttribute("y", y);
      rect.setAttribute("width", barWidth);
      rect.setAttribute("height", barHeight);
      rect.setAttribute("fill", this.color);
      rect.style.cursor = "pointer";

      // Adicionar dados para tooltip
      rect.setAttribute("data-value", value);
      rect.setAttribute("data-label", this.labels[index]);

      // Animação inicial
      rect.style.transformOrigin = `${x + barWidth / 2}px ${
        this.padding.top + this.chartHeight
      }px`;
      rect.style.transform = "scaleY(0)";

      // Event listeners
      rect.addEventListener("mouseenter", (e) =>
        this.showBarTooltip(e, value, this.labels[index])
      );
      rect.addEventListener("mouseleave", () => this.hideBarTooltip());
      rect.addEventListener("mouseenter", (e) => {
        e.target.setAttribute("fill", this.lightenColor(this.color, 20));
      });
      rect.addEventListener("mouseleave", (e) => {
        e.target.setAttribute("fill", this.color);
      });

      this.container.appendChild(rect);

      // Adicionar valor no topo da barra
      if (value > 0) {
        const valueText = document.createElementNS(
          "http://www.w3.org/2000/svg",
          "text"
        );
        valueText.setAttribute("x", x + barWidth / 2);
        valueText.setAttribute("y", y - 5);
        valueText.setAttribute("text-anchor", "middle");
        valueText.setAttribute("font-size", "12");
        valueText.setAttribute("font-weight", "bold");
        valueText.setAttribute("fill", "#333");
        valueText.textContent = value;
        this.container.appendChild(valueText);
      }
    });

    // Criar eixos
    this.createAxes(maxValue);

    // Animar barras
    this.animateBars();
  }

  createAxes(maxValue) {
    // Eixo X
    const xAxis = document.createElementNS(
      "http://www.w3.org/2000/svg",
      "line"
    );
    xAxis.setAttribute("x1", this.padding.left);
    xAxis.setAttribute("y1", this.padding.top + this.chartHeight);
    xAxis.setAttribute("x2", this.padding.left + this.chartWidth);
    xAxis.setAttribute("y2", this.padding.top + this.chartHeight);
    xAxis.setAttribute("stroke", "#666");
    xAxis.setAttribute("stroke-width", "2");
    this.container.appendChild(xAxis);

    // Eixo Y
    const yAxis = document.createElementNS(
      "http://www.w3.org/2000/svg",
      "line"
    );
    yAxis.setAttribute("x1", this.padding.left);
    yAxis.setAttribute("y1", this.padding.top);
    yAxis.setAttribute("x2", this.padding.left);
    yAxis.setAttribute("y2", this.padding.top + this.chartHeight);
    yAxis.setAttribute("stroke", "#666");
    yAxis.setAttribute("stroke-width", "2");
    this.container.appendChild(yAxis);

    // Labels do eixo X
    const barWidth = (this.chartWidth / this.data.length) * 0.8;
    const barSpacing = (this.chartWidth / this.data.length) * 0.2;

    this.labels.forEach((label, index) => {
      const x =
        this.padding.left +
        index * (barWidth + barSpacing) +
        barSpacing / 2 +
        barWidth / 2;
      const text = document.createElementNS(
        "http://www.w3.org/2000/svg",
        "text"
      );
      text.setAttribute("x", x);
      text.setAttribute("y", this.padding.top + this.chartHeight + 20);
      text.setAttribute("text-anchor", "middle");
      text.setAttribute("font-size", "12");
      text.setAttribute("fill", "#333");
      text.textContent = label;
      this.container.appendChild(text);
    });

    // Linhas de grade horizontais apenas (sem os números)
    const steps = 5;
    for (let i = 1; i <= steps; i++) {
      // Começar em 1 para pular a linha do zero
      const y =
        this.padding.top + this.chartHeight - (i / steps) * this.chartHeight;

      const gridLine = document.createElementNS(
        "http://www.w3.org/2000/svg",
        "line"
      );
      gridLine.setAttribute("x1", this.padding.left);
      gridLine.setAttribute("y1", y);
      gridLine.setAttribute("x2", this.padding.left + this.chartWidth);
      gridLine.setAttribute("y2", y);
      gridLine.setAttribute("stroke", "#999");
      gridLine.setAttribute("stroke-width", "1");
      this.container.appendChild(gridLine);
    }
  }

  animateBars() {
    const bars = this.container.querySelectorAll("rect");
    bars.forEach((bar, index) => {
      setTimeout(() => {
        bar.style.transition = "transform 0.6s ease-out";
        bar.style.transform = "scaleY(1)";
      }, index * 100);
    });
  }

  lightenColor(color, percent) {
    const num = parseInt(color.replace("#", ""), 16);
    const amt = Math.round(2.55 * percent);
    const R = (num >> 16) + amt;
    const G = ((num >> 8) & 0x00ff) + amt;
    const B = (num & 0x0000ff) + amt;
    return (
      "#" +
      (
        0x1000000 +
        (R < 255 ? (R < 1 ? 0 : R) : 255) * 0x10000 +
        (G < 255 ? (G < 1 ? 0 : G) : 255) * 0x100 +
        (B < 255 ? (B < 1 ? 0 : B) : 255)
      )
        .toString(16)
        .slice(1)
    );
  }

  showBarTooltip(event, value, label) {
    const tooltip = this.getOrCreateBarTooltip();
    tooltip.innerHTML = `
            <strong>${label}</strong><br>
            Usuários: ${value}
        `;

    tooltip.style.display = "block";
    tooltip.style.left = event.pageX + 10 + "px";
    tooltip.style.top = event.pageY - 10 + "px";
  }

  hideBarTooltip() {
    const tooltip = document.getElementById("bar-chart-tooltip");
    if (tooltip) {
      tooltip.style.display = "none";
    }
  }

  getOrCreateBarTooltip() {
    let tooltip = document.getElementById("bar-chart-tooltip");
    if (!tooltip) {
      tooltip = document.createElement("div");
      tooltip.id = "bar-chart-tooltip";
      tooltip.className = "chart-tooltip";
      document.body.appendChild(tooltip);
    }
    return tooltip;
  }

  updateData(data, labels, title = "") {
    this.data = data;
    this.labels = labels;
    this.title = title;
    this.init();
  }
}

// Variável global para armazenar a instância do gráfico de linha
let barChartInstance = null;

// Função para carregar dados de usuários por mês
function loadUsersByMonthData() {
  fetch("/controllers/api_data.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      functions: ["getUsuariosPorMes"],
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Dados de usuários por mês:", data);

      const monthsData = data.getUsuariosPorMes || [];

      // Sempre gerar os últimos 6 meses a partir de hoje
      const now = new Date();
      const meses = [
        "Jan",
        "Fev",
        "Mar",
        "Abr",
        "Mai",
        "Jun",
        "Jul",
        "Ago",
        "Set",
        "Out",
        "Nov",
        "Dez",
      ];

      const last6Months = [];

      // Gerar os últimos 6 meses sempre a partir do mês atual
      for (let i = 5; i >= 0; i--) {
        const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
        const year = date.getFullYear();
        const month = date.getMonth() + 1;

        // Procurar dados para este mês/ano
        const monthData = monthsData.find(
          (item) => parseInt(item.mes) === month && parseInt(item.ano) === year
        );

        const label = `${meses[date.getMonth()]}/${year}`;
        const value = monthData ? parseInt(monthData.total) : 0;

        last6Months.push({
          label: label,
          value: value,
          mes: month,
          ano: year,
        });
      }

      console.log("Últimos 6 meses processados:", last6Months);

      const labels = last6Months.map((item) => item.label);
      const values = last6Months.map((item) => item.value);

      console.log("Labels:", labels);
      console.log("Valores:", values);

      if (barChartInstance) {
        barChartInstance.updateData(values, labels, "");
      } else {
        barChartInstance = new BarChartSVG(
          "barChart",
          values,
          labels,
          "",
          "#6B46C1"
        );
      }
    })
    .catch((error) => {
      console.error("Erro ao carregar dados de usuários por mês:", error);

      // Dados de fallback em caso de erro - últimos 6 meses
      const now = new Date();
      const meses = [
        "Jan",
        "Fev",
        "Mar",
        "Abr",
        "Mai",
        "Jun",
        "Jul",
        "Ago",
        "Set",
        "Out",
        "Nov",
        "Dez",
      ];

      const fallbackData = [];
      const fallbackLabels = [];

      for (let i = 5; i >= 0; i--) {
        const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
        fallbackLabels.push(`${meses[date.getMonth()]}/${date.getFullYear()}`);
        fallbackData.push(Math.floor(Math.random() * 5) + 1);
      }

      if (barChartInstance) {
        barChartInstance.updateData(fallbackData, fallbackLabels, "");
      } else {
        barChartInstance = new BarChartSVG(
          "barChart",
          fallbackData,
          fallbackLabels,
          "",
          "#6B46C1"
        );
      }
    });
}

// Inicializar o gráfico quando a página carregar
document.addEventListener("DOMContentLoaded", function () {
  loadDatabaseData();
  loadUsersByMonthData();
});
