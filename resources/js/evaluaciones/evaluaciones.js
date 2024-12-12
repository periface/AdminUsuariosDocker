const evaluaciones = document.getElementById('evals');

// Función para mostrar la lista de los usuarios
const loadData =  (data) => {

    let divContent = document.getElementById('content');
    divContent.innerHTML = "";

    divContent.innerHTML = data;
    
}

const getEvaluaciones = async() => {
    
    const response = await fetch('/evaluaciones', {
        method: "GET",
        headers: {
            Authorization: 'Bearer '+localStorage.getItem('token'),
            Accept: 'application/json'
        }
    })

    if(!response.ok){
            const responseJson = await response.json();
            switch (response.status) {
                case 403:
                    toastr.error(responseJson.data, 'Acceso Denegado');
                    break;
                default:
                    break;
            }
    }
    
    const responseText = await response.text();
    loadData(responseText);

}

evaluaciones.addEventListener('click', (event) => {
    event.preventDefault();
    getEvaluaciones();
})