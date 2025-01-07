import { eventListener } from "../utils/event";
import { openModal } from "../utils/modal";

const areas = document.getElementById('areas');

const attachEventListeners = () => {
    eventListener('add-area', showFormArea);
};

const loadData =  (data) => {

    let divContent = document.getElementById('content');
    divContent.innerHTML = "";

    divContent.innerHTML = data;
    attachEventListeners();
    
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

const addArea = async(registerForm) => {
    console.log('En addRole ', registerForm);
    $('#addArea').validate({
        rules: {
            nombre: { required: true },
            siglas: { required: true }
        },
        messages: {
            nombre: {
                required: 'El nombre es requerido',
                minlength: 'Debe tener al menos 4 caracteres'
            },
            siglas: {
                required: 'Las siglas son requeridas',
                minlength: 'Debe tener al menos 4 caracteres'
            }
        }
    });

    registerForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        if(!$("#addArea").valid()){
            return;
        }

        const form = new FormData(event.target);
        console.log(form);
        const response = await fetch('api/areas', {
            method: "POST",
            headers: {
                Authorization: 'Bearer '+localStorage.getItem('token'),
                Accept: 'application/json'
            },
            body: form
        });

        const responseJson = await response.json();
        if(responseJson.data.attributes.statusCode === 201){
            closeModal();
            showNotification('Éxito', 'Operacion realizada con éxito', 'success');
            console.log(responseJson);
        }

    });
}

// Modal para agregar un rol
const showFormArea = async () => {

    const response = await fetch('/areas/create', {
        method: "GET",
        headers: {
            Authorization: 'Bearer '+localStorage.getItem('token')
        }
    });

    const responseText = await response.text();

    console.log(responseText);
    openModal(responseText, 'Agregar Nueva Área');
    let registerArea = document.getElementById('addArea');

    addArea(registerArea);
    
}