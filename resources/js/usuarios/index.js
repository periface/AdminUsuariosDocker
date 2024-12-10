import { configUser } from './config';
import { eventListener } from '../utils/event';
import { closeModal, openModal } from '../utils/modal';
import { showNotification } from '../utils/notification';

let addUser = document.getElementById('users');
let registerForm;

const attachEventListeners = () => {
    eventListener('config-user', configUser);
    eventListener('add-user', showFormUser);
    eventListener('delete-user', confirmDelete);
};

// Función para mostrar la lista de los usuarios
const loadData =  (data) => {
    let divContent = document.getElementById('content');
    divContent.innerHTML = "";

    divContent.innerHTML = data;
    attachEventListeners();
    
}

// Función para obtener los usuarios
const getUsers = async () => {
    try {
        const response = await fetch('/users', {
            method: 'GET',
            headers: {
                'Authorization' : 'Bearer '+localStorage.getItem('token')
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
        console.log(response);
        loadData(responseText);

    } catch (error) {
        console.log('Es el response', error);
    }
}

// Mandamos llamar los usuarios al hacer click
addUser.onclick = (event) => {
    event.preventDefault();
    getUsers();
}

// Agregar usuarios
// Mostramos el form en el modal
const showFormUser = async () => {
    
    const formResponse = await fetch('/users/create', {
        method: "GET",
        headers: {
            'Authorization' : 'Bearer '+localStorage.getItem('token')
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
                Authorization: 'Bearer '+localStorage.getItem('token'),
                Accept: 'application/json'
            },
            body: formData
        });

        const responseJson = await response.json();
        if(responseJson.data.attributes.statusCode === 201){
            closeModal();
            showNotification('Éxito', 'Operacion realizada con éxito', 'success');
            getUsers();
        }

    }
}

// Eliminar usuario
const deleteUser = async (user) => {
    
    try {
        
        const response =  await fetch(`api/users/${user}`, {
            method: "DELETE",
            headers: {
                'Content-Type' : 'application/json',
                'Authorization': 'Bearer '+localStorage.getItem('token')
            }
        });

        if(response.status === 204){
            getUsers();
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

            if(deleteUser(user)){

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
