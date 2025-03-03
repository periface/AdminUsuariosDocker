import { PerformanceChart } from "../dataviz/charts";
const state = {
    xcsrftoken: document.querySelector('meta[name="csrf-token"]').content || '',
    bearertoken: localStorage.getItem('token') || '',
    id: document.querySelector('meta[name="id"]').content || '',
}

async function load_performance_charts() {
    const performanceChartDimension =
        new PerformanceChart(
            {
                canvas: document.getElementById('radar-dimensiones'),
                id: parseInt(state.id),
                tipo: "dimensiones",
                title: 'Rendimiento por dimensiones',
                bearertoken: state.bearertoken,
                xcsrftoken: state.xcsrftoken,
                nivel: "area",
            }
        );
    await performanceChartDimension.init();

    const performanceChartCategory = new PerformanceChart(
        {
            canvas: document.getElementById('radar-categorias'),
            id: parseInt(state.id),
            tipo: "categorias",
            title: 'Rendimiento por categorías',
            bearertoken: state.bearertoken,
            xcsrftoken: state.xcsrftoken,
            nivel: "area",
        }
    );
    await performanceChartCategory.init();
}

async function init_view() {
    await load_performance_charts();
}

init_view().then(() => {
    console.log('view loaded');
}).catch((error) => {
    console.error('error loading view', error);
});
