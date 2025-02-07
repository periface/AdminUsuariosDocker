import { Chart } from "chart.js/auto";

/**
 * @function radarChart
 * @descripcion Renderiza un gráfico de radar para visualizar el
 * porcentaje de cumplimiento de cada área. Utiliza la librería chartjs
 * para generar la visualización
 * @params ...
 */

export const radarChart = () => {
    // 1 Importar la libreria chartjs mport { Chart } from "chart.js";

    // 2 Accedemos al contenedor para renderizar la gráfica
    const container = document.getElementById("radarChart");

    // 3 Generamos la data
    const data = {
        labels: [
            'D_Compras',
            'D_Patrimonio',
            'D_Administrativa',
            'D_Recuros Humanos',
            'D_Planeación y Control'
        ],
        datasets: [{
            label: '% Avance por Direcciones',
            data: [48, 48, 95, 19, 96],
            fill: true,
            backgroundColor: 'rgba(171,0,51, 0.5)',
            borderColor: 'rgb(171,0,51)',
            pointBackgroundColor: 'rgb(188,149,92)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(54, 162, 235)'
        }, {
            label: '% Avance por Indicadores',
            data: [96, 19, 48, 95, 26],
            fill: true,
            backgroundColor: 'rgb(188,149,92, 0.5)',
            borderColor: 'rgb(188,149,92)',
            pointBackgroundColor: 'rgb(188,149,92)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(54, 162, 235)'
        }]
    }

    // 4 Creamos nuestro objeto chart con los objetos requeridos para su renderizado
    /**
     * Recibe 3 objetos:
     * type: indica el tipo de gráfico, en este caso usaremos radar
     * data: recibe los arrays de datos, labels y dataset que pintarán la gráfica
     * options: opciones adicionales del gráfico
     */
    const radarChart = new Chart(container, {
        type: 'radar',
        data: {
            labels: [
                'Eficiencia',
                'Economía',
                'Eficacia',
                'Calidad'
            ], // Indicadores
            datasets: [{
                label: 'DG_RH',
                data: [8, 7, 6, 9], // Datos para la primera área
                fill: true,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                pointBorderColor: '#fff',
                pointRadius: 5,
            },
            {
                label: 'DG_COMPRAS',
                data: [7, 8, 7, 8], // Datos para la segunda área
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: '#fff',
                pointRadius: 5,
            },
            {
                label: 'D_PATRIMONIO',
                data: [6, 6, 7, 7], // Datos para la tercera área
                fill: true,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                pointBorderColor: '#fff',
                pointRadius: 5,
            },
            {
                label: 'D_CONTRATOS',
                data: [8, 6, 7, 8], // Datos para la cuarta área
                fill: true,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(153, 102, 255, 1)',
                pointBorderColor: '#fff',
                pointRadius: 5,
            },
            {
                label: 'D_PLANEACION_CONTROL',
                data: [9, 8, 8, 9], // Datos para la quinta área
                fill: true,
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(255, 159, 64, 1)',
                pointBorderColor: '#fff',
                pointRadius: 5,
            }]
        },
        options: {
            scales: {
                r: {
                    angleLines: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.1)' // Color de las líneas de los ángulos
                    },
                    suggestedMin: 0, // Valor mínimo sugerido para el eje
                    suggestedMax: 10, // Valor máximo sugerido para el eje
                }
            },
            responsive: true,
            maintainAspectRatio: false // Permite que el gráfico ocupe todo el tamaño del contenedor
        }
    });
}

export const stackedBarChart = () => {
    const stackedBarContainer = document.getElementById('stackedBar');
    console.log(stackedBarContainer);

    const DATA_COUNT = 7;
    const NUMBER_CFG = { count: DATA_COUNT, min: -100, max: 100 };

    const labels = ['RH', 'COMPRAS', 'PATRIMONIO', 'CONTRATOS', 'CTRL_PLAN'];
    const data = {
        labels: labels,
        datasets: [{
            label: 'Eficiencia',
            data: [12, 19, 3, 5, 2],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        },
        {
            label: 'Economía',
            data: [5, 7, 9, 12, 14],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        },
        {
            label: 'Eficacia',
            data: [8, 13, 6, 8, 10],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        },
        {
            label: 'Calidad',
            data: [8, 13, 6, 8, 10],
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1
        }
        ]
    };

    new Chart(stackedBarContainer, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: false,
                    text: 'Stacked Bar chart for pollution status'
                },
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true
                }
            }
        }
    });
}
