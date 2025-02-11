import { get_stats } from './cruds.js';

import { Chart } from "chart.js/auto";
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
function total_meta(data) {
    const status = document.getElementById('status');
    const meta_html = get_meta_html(data);
    status.innerHTML = meta_html;
    const total = document.getElementById('total');
    const total_html = get_total_html(data);
    total.innerHTML = total_html;
}
function get_total_html(data) {
    //es decimal  //es decimal tambn :v
    let { total, sentido, totalValue, metaValue } = data;
    let icon = '';
    let color = '';
    // neta que a veces te odio js
    totalValue = parseInt(totalValue);
    metaValue = parseInt(metaValue);
    //
    switch (sentido) {
        case 'ascendente':
            if (totalValue < metaValue) {
                icon = '<i class="fas fa-arrow-down"></i>';
                color = 'text-red-500';
            } else {
                icon = '<i class="fas fa-arrow-up"></i>';
                color = 'text-green-500';
            }
            break;
        case 'descendente':
            if (totalValue > metaValue) {
                icon = '<i class="fas fa-arrow-up"></i>';
                color = 'text-red-500';
            } else {
                icon = '<i class="fas fa-arrow-down"></i>';
                color = 'text-green-500';
            }
            break;
        case 'constante':
            if (totalValue == metaValue) {
                icon = '<i class="fas fa-equals"></i>';
                color = 'text-green-500';
            } else {
                color = 'text-red-500';
            }
            break;
        default:
            icon = '';
            break;
    }
    return `<span class="font-bold text-md">Total alcanzado:</span><span class="${color} text-md">
        ${total}
        ${icon}
    </span>`;
}
function get_meta_html(data) {
    const { meta, sentido } = data;
    let icon = '';
    switch (sentido) {
        case 'ascendente':
            icon = '<i class="fas fa-arrow-up"></i>';
            break;
        case 'descendente':
            icon = '<i class="fas fa-arrow-down"></i>';
            break;
        case 'constante':
            icon = '<i class="fas fa-equals"></i>';
            break;
        default:
            icon = '';
            break;
    }
    return `<span class="font-bold text-md">Meta esperada:</span> <span class="text-md">
       ${meta}
        ${icon}
    </span>`;
}
function get_month_name_and_day(date) {
    const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    const d = new Date(date);
    const result = `${months[d.getMonth()]} ${d.getDate()}`;
    // add year at end
    return result + ' ' + d.getFullYear();
}
function get_line_data(data) {
    console.log(data);
    data.evaluation_results = data.evaluation_results.filter((evaluation) => evaluation.resultado != null);
    data.evaluation_results.sort((a, b) => {
        return new Date(a.fecha) - new Date(b.fecha);
    });
    data.evaluation_results = data.evaluation_results.filter((evaluation) => evaluation.status === 'aprobado');
    const labels = []
    const main_data_set = {
        label: data.indicador.nombre,
        data: [],
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
    }
    const data_set = [];
    let index = 0;
    for (const evaluation of data.evaluation_results) {
            labels.push(get_month_name_and_day(evaluation.fecha));
        main_data_set.data.push({
            x: labels[index],
            y: evaluation.resultado
        });
        index++;
    }
    data_set.push(main_data_set);
    console.log(data_set);
    return {
        labels,
        datasets: data_set
    }
}
function line_chart(data) {
    const line_data = get_line_data(data);
    const container = document.getElementById('line-chart');
    const config = {
        type: 'line',
        data: {
            labels: line_data.labels,
            datasets: line_data.datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Evolución del indicador'
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


