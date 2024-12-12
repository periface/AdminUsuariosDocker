const areas = document.getElementById('areas');

const loadData =  (data) => {

    let divContent = document.getElementById('content');
    divContent.innerHTML = "";

    divContent.innerHTML = data;
    
}

const getAreas = async () => {
    
    const response = await fetch('/areas', {
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

areas.addEventListener('click', (event) => {
    event.preventDefault();
    getAreas();
});