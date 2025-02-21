// Description: Clase para crear gráficos de rendimiento
// @ejemplo
/*
 *
 *  import { PerformanceChart,
 *           PerformanceChartOptions
 *  } from './dataviz/charts_areas.js';
 *
    const options = new PerformanceChartOptions
                    (
                        'Rendimiento por dimensiones', // Título
                        state.bearertoken,  // Token de autenticación
                        state.xcsrftoken    // Token CSRF
                    );

    const performanceChartDimension = new PerformanceChart(
        document.getElementById('radar'), // Elemento canvas
        0, // Id, del área, en este caso 0 carga todas las áreas
        "dimensiones", // Tipo de gráfico: dimensiones|categorias
        options // Opciones
    );
    // Se inicializa el gráfico; aquí se hace la petición a la API
    await performanceChartDimension.init();
    // Se actualiza el gráfico; aquí se hace la petición a la API,
    // pero no se crea otra instancia del gráfico
    await performanceChartDimension.refresh();
 *
 * */

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
 * @param {string} tipo
 * @param {PerformanceChartOptions} options
 * @returns {PerformanceChart}
 * @throws {Error} Si no se proporciona el token de autenticación
 */
class PerformanceChart {
    chart = null;
    canvas = null;
    id = null;
    tipo = null;
    options = null;
    constructor(canvas, id, tipo, options) {
        this.#validateOptions(options);
        this.canvas = canvas;
        this.id = id;
        this.tipo = tipo;
        this.options = options;
    }
    async init() {
        const chart_data = await this.#fetchChartData(this.id, this.tipo, this.options);
        this.chart = this.#buildChart(this.canvas, chart_data, this.options.title);
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
        if (!options instanceof PerformanceChartOptions) {
            throw new Error('Opciones inválidas, debe ser una instancia de PerformanceChartOptions');
        }
    }

}
class PerformanceChartOptions {
    incluirEtiquetas = false;
    incluirEvaluacionesAbiertas = false;
    title = 'Rendimiento';
    bearertoken;
    xcsrftoken;
    /**
     * @param {string} title
     * @param {string} bearertoken
     * @param {string} xcsrftoken
     * @throws {Error} Si no se proporciona el token de autenticación
     * @constructor
     * @returns {PerformanceChartOptions}
     * @example
     * const options = new PerformanceChartOptions('Rendimiento', false, false, 'Bearer token', 'X-CSRF-TOKEN');
     * performanceChart(canvas, 1, 'dimensiones', options);
     */
    constructor(title, bearertoken, xcsrftoken) {
        this.incluirEtiquetas = true;
        this.incluirEvaluacionesAbiertas = false;
        this.title = title;
        if (bearertoken === undefined || xcsrftoken === undefined) {
            throw new Error('Falta el token de autenticación');
        }
        this.bearertoken = bearertoken;
        this.xcsrftoken = xcsrftoken;
    }
}

export {
    PerformanceChartOptions,
    PerformanceChart
}
