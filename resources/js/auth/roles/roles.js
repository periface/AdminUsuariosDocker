import { configUser } from '../../usuarios/config';
import { openModal, closeModal } from '../../utils/modal';
import { eventListener } from '../../utils/event';
import { showNotification } from '../../utils/notification';
import { availablePermissionsRole } from '../permissions/permisos';

const attachEventListeners = () => {
    eventListener('edit-role', showFormEdit);
    eventListener('delete-role', confirmDelete);
    eventListener('add-role', showFormRole);
    eventListener('permissions-role', rolePermissions);
    eventListener('atach-permission-role', availablePermissionsRole);
};

// Función para mostrar la lista de los usuarios
const loadData =  (data) => {

    let divContent = document.getElementById('content');
    divContent.innerHTML = "";

    divContent.innerHTML = data;
    attachEventListeners();
    
}

const atachRole = async (role) => {
    
    let element = document.getElementById('usuario');
    let user = element.dataset.user;
    
    if(role === null){
        console.log('si esta nulo');
        let roleElement = document.getElementsByClassName('.atach-role');
        role = roleElement.dataset.role;
    }

    const response = await fetch(`api/users/${user}/roles/${role}`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer '+ localStorage.getItem('token')
        }
    });

    const responseJson = await response.json();
    if (responseJson.data.attributes.statusCode === 200) {
        configUser(user);
        closeModal();
    }
    console.log(responseJson);
}

const detachRole = async(role) => {

    let element = document.getElementById('usuario');
    let user = element.dataset.user;
    console.log('Aqui va el método delete');
    console.log(`api/users/${user}/roles/${role}`);

    const response = await fetch(`api/users/${user}/roles/${role}`, {
        method: "DELETE",
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer '+ localStorage.getItem('token')
        }
    });

    const responseJson = await response.json();
    if (responseJson.data.attributes.statusCode === 200) {
        configUser(user);
    }
    console.log(responseJson);

}

const showRoles = async () => {
    let element = document.getElementById('usuario');
    let user = element.dataset.user;
    const response = await fetch(`/roles/${user}`, {
        method: "GET",
        headers: {
            'Content-Type':'application/json',
            'Auhtorization':'Bearer '+ localStorage.getItem('token')
        }
    })

    const responseText = await response.text();
    openModal(responseText, 'Roles Disponibles');
    eventListener('atach-role', atachRole);
}

const getRoles = async () => {
    try {
        const response = await fetch('/roles', {
            method: "GET",
            headers: {
                'Authorization' : 'Bearer '+localStorage.getItem('token')
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

    } catch (error) {
        console.log('Es el response', error);
    }
    
    
}

let element = document.getElementById('roles');
element.onclick = (event) => {
    event.preventDefault();
    getRoles();
}

const showFormEdit = async(role) => {

    const response = await fetch(`/roles/${role}/edit`, {
        method: "GET",
        headers: {
            'Authorization' : 'Bearer '+localStorage.getItem('token'),
            'Accept': 'application/json'
        }
    });

    const responseText = await response.text();
    openModal(responseText, 'Editar Rol');

    const element = document.getElementById('editRol');
    editRole(element);
}

const editRole = (element) => {

    $('#editRol').validate({
        rules: {
            name: { required: true, minlength: 4 }
        },
        messages: {
            name: {
                required: 'El nombre es requerido',
                minlength: 'Debe tener al menos 4 caracteres'
            }
        }
    });

    element.onsubmit = async(event) => {

        event.preventDefault();

        const role = document.getElementById('role_id').value;
        const formData = new FormData(event.target);

        const formObject = {};
        formData.forEach((value, key) => {
            formObject[key] = value;
        });

        console.log(formObject);

        const response = await fetch('/api/roles/'+ role, {
            method: "POST",
            headers: {
                'Authorization': "Bearer "+localStorage.getItem('token'),
                'X-HTTP-Method-Override': 'PUT',
                'Accept': 'application/json'
            },
            body: formData
        });

        const responseJson = await response.json();
        console.log(responseJson);
    }
}

// Eliminar usuario
const deleteRol = async (rol) => {
    
    try {
        
        const response =  await fetch(`api/roles/${rol}`, {
            method: "DELETE",
            headers: {
                'Content-Type' : 'application/json',
                'Authorization': 'Bearer '+localStorage.getItem('token')
            }
        });

        if(response.status === 204){
            getRoles();
            return true;
        }
    } catch (error) {
        
    }


}

// Confirm Elimina Usuario
const confirmDelete = (rol) => {
    console.log('Delete Rol', rol);
    Swal.fire({
        title: "¿Desea continuar?",
        text: "Está a punto de eliminar este rol",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: 'Cancelar',
        confirmButtonText: "Eliminar"
      }).then((result) => {
        if (result.isConfirmed) {

            if(deleteRol(rol)){

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

const showFormRole = async () => {
    
    const response = await fetch('/roles/create', {
        method: "GET",
        headers:{
            Authorization: "Bearer "+localStorage.getItem('token')
        }
    });

    const responseText = await response.text();

    openModal(responseText, 'Agregar Rol');

    let registerForm = document.getElementById('addRole');
    addRole(registerForm);
}

const addRole = async(registerForm) => {
    console.log('En addRole ', registerForm);
    $('#addRole').validate({
        rules: {
            name: { required: true }
        },
        messages: {
            name: {
                required: 'El nombre es requerido',
                minlength: 'Debe tener al menos 4 caracteres'
            }
        }
    });

    registerForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        if(!$("#addRole").valid()){
            return;
        }

        const form = new FormData(event.target);

        const response = await fetch('api/roles', {
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
            getRoles();
        }

    });
}

const rolePermissions = async(role) => {
    
    const response = await fetch(`/roles/${role}/permissions`, {
        method: "GET",
        headers: {
            Authorization: 'Bearer '+localStorage.getItem('token'),
            Accept: 'application/json'
        }
    });

    const responseText = await response.text();
    loadData(responseText);

}

const attachPermissionRole = async (permission) => {
    
    const document = document.getElementById('role');
    const role = role.dataset.role;

    const response = await fetch(`api/roles/${role}/permissions/${permission}`, {
        method: "POST",
        headers: {
            Auhtorization: 'Bearer '+localStorage.getItem('token'),
            Accept: 'application/json'
        }
    });

    const responseJson = await response.json();
    if(responseJson.data.attributes.statusCode === 200){
        showNotification('Éxito', 'Operación realizada con éxito', 'success');
    }
}

export {showRoles, detachRole};