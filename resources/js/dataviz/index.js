import { radarChart, stackedBarChart } from "./dataviz";

const monitor = document.getElementById('monitor');

const loadData = (data) => {

    let divContent = document.getElementById('content');
    divContent.innerHTML = "";

    divContent.innerHTML = data;

}

const getDataViz = async () => {
    const response = await fetch('/monitor', {
        method: "GET",
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token'),
            Accept: 'application/json'
        }
    });

    const responseText = await response.text();
    loadData(responseText);
    radarChart();
    stackedBarChart();
}

monitor.addEventListener('click', (event) => {
    event.preventDefault();
    getDataViz();
});