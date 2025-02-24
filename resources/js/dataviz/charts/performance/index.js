// Description: Clase para crear gráficos de rendimiento
// @ejemplo
/*
*
*  import { PerformanceChart
*  } from './dataviz/charts';
*

    const performanceChartDimension =
        new PerformanceChart(
            {
                canvas: document.getElementById('radar'),
                id: 0,
                tipo: "dimensiones",
                title: 'Rendimiento por dimensiones',
                bearertoken: state.bearertoken,
                xcsrftoken: state.xcsrftoken,
                nivel: "area",
            }
        );
    // Se inicializa el gráfico; aquí se hace la petición a la API
    await performanceChartDimension.init();
    // Se actualiza el gráfico; aquí se hace la petición a la API,
    // pero no se crea otra instancia del gráfico
    await performanceChartDimension.refresh();
*
**/

import { Chart } from "chart.js/auto";
// colores de la paleta de colores de material design
// para los gráficos de rendimiento, se obtienen de forma aleatoria
// en la función make_datasets
const materialRGBColors = [
    { bgColor: 'rgba(255, 99, 132, 0.2)', borderColor: 'rgba(255, 99, 132, 1)' }, // Rojo rosado
    { bgColor: 'rgba(54, 162, 235, 0.2)', borderColor: 'rgba(54, 162, 235, 1)' }, // Azul
    { bgColor: 'rgba(75, 192, 192, 0.2)', borderColor: 'rgba(75, 192, 192, 1)' }, // Verde agua
    { bgColor: 'rgba(255, 206, 86, 0.2)', borderColor: 'rgba(255, 206, 86, 1)' }, // Amarillo
    { bgColor: 'rgba(153, 102, 255, 0.2)', borderColor: 'rgba(153, 102, 255, 1)' }, // Morado
    { bgColor: 'rgba(255, 159, 64, 0.2)', borderColor: 'rgba(255, 159, 64, 1)' }, // Naranja
    { bgColor: 'rgba(201, 203, 207, 0.2)', borderColor: 'rgba(201, 203, 207, 1)' }, // Gris
    { bgColor: 'rgba(0, 128, 128, 0.2)', borderColor: 'rgba(0, 128, 128, 1)' }, // Verde oscuro
    { bgColor: 'rgba(220, 20, 60, 0.2)', borderColor: 'rgba(220, 20, 60, 1)' }, // Carmesí
    { bgColor: 'rgba(0, 191, 255, 0.2)', borderColor: 'rgba(0, 191, 255, 1)' }, // Azul cielo
    { bgColor: 'rgba(255, 140, 0, 0.2)', borderColor: 'rgba(255, 140, 0, 1)' } // Naranja oscuro
];
/**
 * @class Performance
 * @description Clase para crear gráficos de rendimiento
 * @example
 * const options = new PerformanceChartOptions('Rendimiento', 'Bearer token', 'X-CSRF-TOKEN');
 * const performance = new PerformanceChart(canvas, 1, 'dimensiones', options);
 * performance.init();
 *
 * @param {HTMLCanvasElement} canvas
 * @param {number} id
 * @param {string} tipo dimensiones|categorias
 * @param options
 * @returns {PerformanceChart}
 * @throws {Error} Si no se proporciona el token de autenticación
 */
class PerformanceChart {
    chart = null;
    canvas = null;
    id = null;
    tipo = null;
    options = null;
    constructor(options) {
        this.canvas = options.canvas;
        this.id = options.id;
        this.tipo = options.tipo;
        this.#validateInput(options.canvas,
            options.id,
            options.tipo);
        this.#validateOptions(options);
        console.log(options);
        this.options = options;
    }
    #validateInput(canvas, id, tipo) {
        if (!canvas || !tipo) {
            throw new Error('Parámetros inválidos');
        }
        if (!(canvas instanceof HTMLCanvasElement)) {
            throw new Error('El elemento canvas es inválido');
        }
        if (typeof id !== 'number') {
            throw new Error('El id debe ser un número');
        }
        if (typeof tipo !== 'string') {
            throw new Error('El tipo debe ser una cadena');
        }
        if (tipo !== 'dimensiones' && tipo !== 'categorias') {
            throw new Error('El tipo debe ser dimensiones o categorias');
        }
    }
    async init() {
        const chart_data = await this.#fetchChartData(
            this.id,
            this.tipo,
            this.options);
        this.chart = this.#buildChart(
            this.canvas,
            chart_data,
            this.options.title);
        return this;
    };
    async refresh() {
        const data = await this.#fetchChartData();
        this.#update(data);
    }
    #make_datasets(data) {
        const datasets = [];
        for (let area of data) {
            const color = materialRGBColors[Math.floor(Math.random() * materialRGBColors.length)];
            const dataset = {
                label: area.areaSiglas,
                data: [],
                backgroundColor: color.bgColor,
                borderColor: color.borderColor,
                borderWidth: 1
            }
            for (let dimensionKey in area.dimensionesResult) {
                const dimension = area.dimensionesResult[dimensionKey];
                dataset.data.push(dimension.value);
            }
            datasets.push(dataset);
        }
        return datasets;
    }
    #make_labels(data) {
        const dimensiones = [];

        for (let area of data) {
            for (let dimensionKey in area.dimensionesResult) {
                const dimension = area.dimensionesResult[dimensionKey];
                const found = dimensiones.find((d) => d === dimension.nombre);
                if (!found) {
                    dimensiones.push(dimension.nombre);
                }
            }
        }
        return dimensiones;
    }
    #getMinMax(datasets) {
        let min = 0;
        let max = 0;
        for (let dataset of datasets) {
            for (let data of dataset.data) {
                if (data < min) {
                    min = data;
                }
                if (data > max) {
                    max = data;
                }
            }
        }
        return { min, max };
    }

    #getChartConfig(min, max, labels, datasets, title) {
        return {
            type: 'radar',
            data: {
                labels,
                datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: title
                    }
                },
                scales: {

                    y: {
                        suggestedMin: min,
                        suggestedMax: max,
                    }
                },
            },
        }
    }
    async #fetchChartData(id, tipo, options) {
        const { incluirEtiquetas, incluirEvaluacionesAbiertas, bearertoken, xcsrftoken } = options;
        //areas/dimensiones/false/true
        const endpoint = `/api/v1/dataviz/performance/${id}/${incluirEtiquetas}/${incluirEvaluacionesAbiertas}/${tipo}`;
        const response = await fetch(endpoint, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + bearertoken,
                'X-CSRF-TOKEN': xcsrftoken
            }
        });
        const json = await response.json()
        const chart_data = json.data.attributes.data;
        return chart_data;
    }
    #buildChart(canvas, data, title) {
        const labels = this.#make_labels(data);
        const datasets = this.#make_datasets(data);
        const { min, max } = this.#getMinMax(datasets);
        return new Chart(canvas, this.#getChartConfig(min, max, labels, datasets, title));
    }
    #update(data) {
        const labels = this.#make_labels(data);
        const datasets = this.#make_datasets(data);
        const { min, max } = this.#getMinMax(datasets);
        this.chart.data.labels = labels;
        this.chart.data.datasets = datasets;
        this.chart.options.scales.y.suggestedMin = min;
        this.chart.options.scales.y.suggestedMax = max;
        this.chart.update();
    }

    #validateOptions(options) {
        if (!options) {
            throw new Error('Opciones inválidas');
        }
    }

}
export {
    PerformanceChart
}
