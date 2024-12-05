import { closeModal } from '../utils/modal';

let registerForm;
const showFormUser = async () => {

    const formResponse = await fetch('/users/add', {
        method: "GET",
        headers: {
            'Authorization' : 'Bearer '+localStorage.getItem('token')
        }
    });

    let modal = new bootstrap.Modal(document.getElementById('modalConfig'));
    let modalBody = document.querySelector('.modal-body');
    let modalTitle = document.querySelector('.modal-title');

    const formResponseText = await formResponse.text();

    modalTitle.innerHTML = "Agregar Usuario";
    modalBody.innerHTML = "";
    modalBody.innerHTML = formResponseText;

    modal.show();
    registerForm = document.getElementById('addUser');
    registerUser(registerForm);
}

const registerUser = (registerForm) => {

    $('#addUser').validate({
        rules: {
            name: { required: true, minlength: 4 },
            email: { required: true },
            password: { required: true }
        },
        messages: {
            name: {
                required: 'El nombre es requerido',
                minlength: 'Debe tener al menos 4 caracteres'
            },
            email: {
                required: 'El email es requerido',
            },
            password: {
                required: 'La contraseña es requerido',
            }
        }
    });

    registerForm.onsubmit = async (event) => {
        
        event.preventDefault();
        
        if(!$("#addUser").valid()){
            return;
        }
        const formData = new FormData(event.target);
        
        const response = await fetch('api/users/register', {
            method: "POST",
            headers: {
                'Authorization': 'Bearer '+localStorage.getItem('token')
            },
            body: formData
        });

        const responseJson = await response.json();
        if(responseJson.data.attributes.statusCode === 201){
            closeModal();
        }

    }
}


export {showFormUser};