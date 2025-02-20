import { UNIDADES } from '../UNIDADES.js';
import { get_stats } from './cruds.js';
import zoomPlugin from 'chartjs-plugin-zoom';
import { Chart, Interaction } from "chart.js/auto";
Chart.register(zoomPlugin);
const state = {
    API_URL: "/api/v1/evaluacion",
    WEB_URL: "/evaluacion",
    evaluacionId: document.getElementById('evaluacionId').value,
    xcsrftoken: document.querySelector('meta[name="csrf-token"]').content || '',
    bearertoken: localStorage.getItem('token') || '',
    donut_chart: null,
    line_chart: null
}
function init_view() {
    load_evaluacion_stats();
}

async function load_evaluacion_stats() {
    const { data, error } = await get_stats(state.evaluacionId, state);
    if (error) {
        console.error(error);
        createToast('Registros', 'Error al cargar las estadísticas', 'error');
        return;
    }
    donut_chart(data);
    line_chart(data);
    total_meta(data);
}
function total_card(data) {
    let color = '';
    let icon = '';
    let { total, sentido, totalValue, metaValue } = data;
    totalValue = parseInt(totalValue);
    metaValue = parseInt(metaValue);

    switch (sentido) {
        case 'ascendente':
            if (totalValue < metaValue) {
                icon = '<i class="fas fa-2x fa-arrow-down text-red-500"></i>';
                color = 'danger';
            } else {
                icon = '<i class="fas fa-2x fa-arrow-up text-green-500"></i>';
                color = 'success';
            }
            break;
        case 'descendente':
            if (totalValue > metaValue) {
                icon = '<i class="fas fa-2x fa-arrow-up text-red-500"></i>';
                color = 'danger';
            } else {
                icon = '<i class="fas fa-2x fa-arrow-down text-green-500"></i>';
                color = 'success';
            }
            break;
        case 'constante':
            if (totalValue == metaValue) {
                icon = '<i class="fas fa-2x fa-equals text-green-500"></i>';
                color = 'success';
            } else {
                color = 'danger';
            }
            break;
        default:
            icon = '';
            break;
    }
    return `
    <div class="card border-left-${color} shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-${color} text-uppercase mb-1">
                        Total alcanzado</div>
                    <div class="h2 mb-0 font-weight-bold text-gray-800">${total}</div>
                </div>
                <div class="col-auto">
                    ${icon}
                </div>
            </div>
        </div>
    </div>
`
}
function meta_card(data) {
    const { meta, sentido } = data;
    let icon = '';
    switch (sentido) {
        case 'ascendente':
            icon = '<i class="fas fa-arrow-up fa-2x text-gray-500"></i>';
            break;
        case 'descendente':
            icon = '<i class="fas fa-arrow-down fa-2x text-gray-500"></i>';
            break;
        case 'constante':
            icon = '<i class="fas fa-equals fa-2x text-gray-500"></i>';
            break;
        default:
            icon = '';
            break;
    }
    return `

    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Meta esperada</div>
                    <div class="h2 mb-0 font-weight-bold text-gray-800">${meta}</div>
                </div>
                <div class="col-auto">
                    ${icon}
                </div>
            </div>
        </div>
    </div>
`
}
function total_meta(data) {
    const status = document.getElementById('status');
    const meta_html = meta_card(data);
    status.innerHTML = meta_html;
    const total = document.getElementById('total');
    const total_html = total_card(data);
    total.innerHTML = total_html;
}
function get_month_name_and_day(date) {
    const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    const d = new Date(date);
    const result = `${months[d.getMonth()]} ${d.getDate()}`;
    // add year at end
    return result + ' ' + d.getFullYear();
}
function get_line_data(data) {
    data.evaluation_results = data.evaluation_results.filter((evaluation) => evaluation.resultado != null);
    data.evaluation_results.sort((a, b) => {
        return new Date(a.fecha) - new Date(b.fecha);
    });
    const meta = parseFloat(data.metaValue);
    const sentido = data.sentido;
    const labels = []
    const main_data_set = {
        label: data.indicador.nombre,
        data: [],
        fill: true,
        backgroundColor: "rgba(78, 115, 223, 0.05)",
        borderColor: "rgba(78, 115, 223, 1)",
        lineTension: 0.3,
        pointRadius: 3,
        pointBackgroundColor: (context) => {
            const value = context.parsed.y;
            if (value === null || value === undefined) {
                return null;
            }
            if (sentido === 'ascendente') {
                if (value > meta) {
                    return 'rgba(0, 255, 0, 1)';
                } else {
                    return 'rgba(255, 0, 0, 1)';
                }
            }
            if (sentido === 'descendente') {
                if (value < meta) {
                    return 'rgba(0, 255, 0, 1)';
                } else {
                    return 'rgba(255, 0, 0, 1)';
                }
            }
        },
        pointBorderColor: (context) => {
            const value = context.parsed.y;
            if (value === null || value === undefined) {
                return null;
            }
            if (sentido === 'ascendente') {
                if (value > meta) {
                    return 'rgba(0, 255, 0, 1)';
                } else {
                    return 'rgba(255, 0, 0, 1)';
                }
            }
            if (sentido === 'descendente') {
                if (value < meta) {
                    return 'rgba(0, 255, 0, 1)';
                } else {
                    return 'rgba(255, 0, 0, 1)';
                }
            }
        },
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(255, 255, 255, 1)",
        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
        pointHitRadius: 10,
        pointBorderWidth: 2,
        stepped: true,
        pointStyle: (context) => {
            const value = context.parsed.y;
            if (value === null || value === undefined) {
                return null;
            }
            if (sentido === 'ascendente') {
                if (value > meta) {
                    return 'circle';
                } else {
                    return 'rect';
                }
            }
            if (sentido === 'descendente') {
                if (value < meta) {
                    return 'circle';
                } else {
                    return 'rect';
                }
            }
        },
        segment: {
            borderColor: (context) => {
                const value = context.p1.parsed.y;
                if (value === null || value === undefined) {
                    return null;
                }
                if (sentido === 'ascendente') {

                    if (value > meta) {
                        return 'rgba(0, 255, 0, 1)';
                    } else {
                        return 'rgba(255, 0, 0, 1)';
                    }
                }
                if (sentido === 'descendente') {
                    if (value < meta) {
                        return 'rgba(0, 255, 0, 1)';
                    } else {
                        return 'rgba(255, 0, 0, 1)';
                    }
                }
            },
            borderDash: (context) => {
                const value = context.p1.parsed.y;
                if (value === null || value === undefined) {
                    return null;
                }
                if (sentido === 'ascendente') {
                    if (value > meta) {
                        return [0, 0];
                    } else {
                        return [5, 5];
                    }
                }
                if (sentido === 'descendente') {
                    if (value < meta) {
                        return [0, 0];
                    } else {
                        return [5, 5];
                    }
                }
            }
        }
    }
    // yellowish colors
    const envalidacion_data_set = {
        label: 'En validación',
        data: [],
        fill: false,
        backgroundColor: "rgba(255, 193, 7, 0.05)",
        borderColor: "rgba(255, 193, 7, 1)",
        lineTension: 0.3,
        pointRadius: 3,
        pointBackgroundColor: "rgba(255, 193, 7, 0.05)",
        pointBorderColor: "rgba(255, 193, 7, 1)",
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(255, 193, 7, 1)",
        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
        pointHitRadius: 10,
        pointBorderWidth: 2,
        borderDash: [5, 5],
        stepped: true,
    }
    const rechazados_data_set = {
        label: 'Rechazados',
        data: [],
        fill: false,
        backgroundColor: "rgba(255, 0, 0, 0.05)",
        borderColor: "rgba(255, 0, 0, 1)",
        lineTension: 0.3,
        pointRadius: 3,
        pointBackgroundColor: "rgba(255, 0, 0, 0.05)",
        pointBorderColor: "rgba(255, 0, 0, 1)",
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(255, 0, 0, 1)",
        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
        pointHitRadius: 10,
        pointBorderWidth: 2,
        borderDash: [5, 5],
        stepped: true,
    }

    const data_set = [];

    let index = 0;
    for (const evaluation of data.evaluation_results) {
        labels.push(get_month_name_and_day(evaluation.fecha));
        if (evaluation.status === 'aprobado') {
            main_data_set.data.push({
                x: labels[index],
                y: evaluation.resultado,
                status: evaluation.status
            });
        }
        else if (evaluation.status === 'capturado') {
            envalidacion_data_set.data.push({
                x: labels[index],
                y: evaluation.resultado,
                status: evaluation.status
            });
        } else if (evaluation.status === 'rechazado') {
            rechazados_data_set.data.push({
                x: labels[index],
                y: evaluation.resultado,
                status: evaluation.status
            });
        }
        index++;
    }
    data_set.push(main_data_set);
    //data_set.push(envalidacion_data_set);
    //data_set.push(rechazados_data_set);
    return {
        labels,
        datasets: data_set
    }
}
function line_chart(data) {
    console.log(data);

    const meta = parseFloat(data.metaValue);
    const sentido = data.sentido;
    const line_data = get_line_data(data);
    const container = document.getElementById('line-chart');
    const unidad = UNIDADES.filter((unidad) => unidad.nombre === data.indicador.unidad_medida);
    let simbolo = '';
    if (unidad.length > 0) {
        simbolo = unidad[0].friendly + " " + unidad[0].simbolo;
    }
    else {
        simbolo = data.indicador.unidad_medida;
    }
    const config = {
        type: 'line',
        data: {
            labels: line_data.labels,
            datasets: line_data.datasets
        },
        options: {
            mantainAspectRatio: false,
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            },
            scales: {

                x: {
                    display: true,
                    border: {
                        display: true,
                    },
                    title: {
                        display: true,
                        text: 'Periodos de captura',
                        color: '#AB0033',
                        font: {
                            family: 'Encode Sans',
                            size: 20,
                            lineHeight: 1.2,
                        },
                        padding: { top: 20, left: 0, right: 0, bottom: 0 }
                    }
                },
                y: {
                    border: {
                        display: true,
                    },

                    display: true,
                    title: {
                        display: true,
                        text: simbolo,
                        color: '#AB0033',
                        font: {
                            family: 'Encode Sans',
                            size: 20,
                            lineHeight: 1.2
                        },
                        padding: { top: 30, left: 0, right: 0, bottom: 0 }
                    }
                }
            },
            interaction: {
                intersect: false,
            },
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                zoom: {
                    limits: {
                        x: { min: 1, max: 100 },
                        y: { min: 1, max: 100 },
                    },
                    pan: {
                        enabled: true,
                        mode: 'xy',
                    },
                    zoom: {

                        wheel: {
                            enabled: true,
                        },
                        pinch: {
                            enabled: true
                        },
                        mode: 'xy',
                    }

                },
                title: {
                    display: true,
                    text: "Evolución de los resultados de la evaluación",
                }
            }
        },

    }
    state.line_chart = new Chart(container, config);
}
function donut_chart(data) {
    if (state.donut_chart) {
        state.donut_chart.data.datasets[0].data = [
            data.results_aprobado,
            data.results_pendiente,
            data.results_capturado,
            data.results_rechazado
        ];
        state.donut_chart.update();
        return;
    }
    const container = document.getElementById('donut-chart');
    const { results_aprobado, results_pendiente, results_capturado, results_rechazado } = data;
    const config = {
        type: 'pie',
        data: {
            labels: ['Aprobados', 'Pendientes', 'En validación', 'Rechazados'],
            datasets: [{
                data: [results_aprobado,
                    results_pendiente,
                    results_capturado,
                    results_rechazado
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Estado de los registros de la evaluación'
                }
            }
        },

    }
    state.donut_chart = new Chart(container, config);
}


init_view();


