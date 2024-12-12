const dimensiones = document.getElementById('dimensiones');

const loadData =  (data) => {

    let divContent = document.getElementById('content');
    divContent.innerHTML = "";

    divContent.innerHTML = data;
    
}

const getDimensiones = async () => {

    const response = await fetch('/dimensiones', {
        method: "GET",
        headers: {
            Authorization: 'Bearer '+localStorage.getItem('token'),
            Accept: 'application/json'
        }
    });

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

dimensiones.addEventListener('click', (event) => {
    event.preventDefault();
    getDimensiones();
});