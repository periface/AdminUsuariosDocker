import { configUser } from './config';
import { eventListener } from '../utils/event';
import { closeModal } from '../utils/modal';

let addUser = document.getElementById('users');
let registerForm;

// Función para mostrar la lista de los usuarios
const loadData =  (data) => {
    let divContent = document.getElementById('content');
    divContent.innerHTML = "";

    divContent.innerHTML = data;
    eventListener('.config-user', configUser);
    eventListener('.add-user', showFormUser);
    eventListener('.delete-user', confirmDelete);
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
            switch (response.status) {
                case 403:
                    toastr.warning('No cuenta con los permisos para realizar esta acción', 'Acceso Denegado');
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
addUser.onclick = () => {
    getUsers();
}

// Agregar usuarios
// Mostramos el form en el modal
const showFormUser = async () => {
    
    const formResponse = await fetch('/users/add', {
        method: "GET",
        headers: {
            'Authorization' : 'Bearer '+localStorage.getItem('token')
        }
    });

    let modal = new bootstrap.Modal(document.getElementById('modalConfig'));
    let modalBody = document.querySelector('.modal-body');

    const formResponseText = await formResponse.text();

    modalBody.innerHTML = "";
    modalBody.innerHTML = formResponseText;

    modal.show();
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
                'Authorization': 'Bearer '+localStorage.getItem('token')
            },
            body: formData
        });

        const responseJson = await response.json();
        if(responseJson.data.attributes.statusCode === 201){
            closeModal();
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
    console.log('Delete User', user);
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
