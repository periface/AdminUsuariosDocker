import { configUser } from './config';
import { eventListener } from '../utils/event';
import { closeModal, openModal } from '../utils/modal';
import { showNotification } from '../utils/notification';
import { loadPage } from '../utils/pageLoader';
let addUser = document.getElementById('users');
let registerForm;

const attachEventListeners = () => {
    eventListener('config-user', configUser);
    eventListener('add-user', showFormUser);
    eventListener('delete-user', confirmDelete);
};

// Mandamos llamar los usuarios al hacer click
addUser.onclick = (event) => {
    event.preventDefault();
    loadPage('/users', 'content', attachEventListeners);
}

// Agregar usuarios
// Mostramos el form en el modal
const showFormUser = async () => {

    const formResponse = await fetch('/users/create', {
        method: "GET",
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    });

    const formResponseText = await formResponse.text();

    openModal(formResponseText, 'Agregar Usuario');

    registerForm = document.getElementById('addUser');
    registerUser(registerForm);
}

// Aplicamos validacion al formulario y procesamos el submit
const registerUser = (registerForm) => {

    $('#addUser').validate({
        rules: {
            name: { required: true, minlength: 4 },
            paterno: { required: true },
            email: { required: true },
            password: { required: true }
        },
        messages: {
            name: {
                required: 'El nombre es requerido',
                minlength: 'Debe tener al menos 4 caracteres'
            },
            paterno: {
                required: 'El apellido es requerido',
            },
            email: {
                required: 'El email es requerido',
            },
            password: {
                required: 'La contraseña es requerida',
            }
        }
    });

    registerForm.onsubmit = async (event) => {

        event.preventDefault();

        if (!$("#addUser").valid()) {
            return;
        }
        const formData = new FormData(event.target);

        const response = await fetch('api/users/register', {
            method: "POST",
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token'),
                Accept: 'application/json'
            },
            body: formData
        });

        const responseJson = await response.json();
        if (responseJson.data.attributes.statusCode === 201) {
            closeModal();
            showNotification('Éxito', responseJson.data.attributes.data, 'success');
            loadPage('/users', 'content', attachEventListeners);
        }

    }
}

// Eliminar usuario
const deleteUser = async (user) => {

    try {

        const response = await fetch(`api/users/${user}`, {
            method: "DELETE",
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        if (response.status === 204) {
            loadPage('/users', 'content', attachEventListeners);
            return true;
        }
    } catch (error) {

    }


}

// Confirm Elimina Usuario
const confirmDelete = (user) => {
    Swal.fire({
        title: "¿Desea continuar?",
        text: "Está a punto de eliminar este usuario",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: 'Cancelar',
        confirmButtonText: "Eliminar"
    }).then((result) => {
        if (result.isConfirmed) {

            if (deleteUser(user)) {

                setTimeout(() => {
                    Swal.fire({
                        title: "Elemento Eliminado",
                        text: "La acción se completó con éxito",
                        icon: "success"
                    });
                }, 800);
            };

        }
    });
}
