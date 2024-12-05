import { eventListener } from '../../utils/event';
import { openModal, closeModal } from '../../utils/modal';
import { configUser } from '../../usuarios/config';

// Función para mostrar la lista de los usuarios
const loadData =  (data) => {

    let divContent = document.getElementById('content');
    divContent.innerHTML = "";

    divContent.innerHTML = data;
    
    eventListener('.add-permission', addPermissionForm);

}

const getPermissions = async () => {
    
    const permissions = await fetch('/permissions', {
        method: "GET",
        headers: {
            'Authorization' : 'Bearer '+ localStorage.getItem('token'),
        }
    });

    const permissionsText = await permissions.text();
    loadData(permissionsText);

}

let element = document.getElementById('permissions');
element.onclick = (event) => {
    event.preventDefault();
    getPermissions();
}

const atachPermission = async (permission) => {
    
    let element = document.getElementById('usuario');
    let user = element.dataset.user;
    
    if(permission === null){
        let roleElement = document.getElementsByClassName('.atach-role');
        permission = roleElement.dataset.permission;
    }

    const response = await fetch(`api/users/${user}/permissions/${permission}`, {
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

const detachPermission = async (permission) => {

    let element = document.getElementById('usuario');
    let user = element.dataset.user;
    
    const response = await fetch(`api/users/${user}/permissions/${permission}`, {
        method: "DELETE",
        headers: {
            'Authorization': 'Bearer '+ localStorage.getItem('token'),
            'Accept' : 'application/json'
        }
    });

    const responseJson = await response.json();
    if (responseJson.data.attributes.statusCode === 200) {
        configUser(user);
    }
    console.log(responseJson);
}

const availablePermissions = async () => {
    let element = document.getElementById('usuario');
    let user = element.dataset.user;
    const response = await fetch(`/permissions/${user}/available-permissions`, {
        method: "GET",
        headers: {
            'Authorization': 'Bearer '+localStorage.getItem('token')
        }
    });

    const responseText = await response.text();
    openModal(responseText);
    eventListener('.atach-permission', atachPermission);
}

const addPermissionForm = async () => {

    const response = await fetch('/permissions/add', {
        method: "GET",
        headers: {
            Authorization: 'Bearer '+localStorage.getItem('token')
        }
    });

    const responseText = await response.text();
    openModal(responseText, 'Agregar Permiso');
    const permissionForm = document.getElementById('addPermission');
    addPermission(permissionForm)

}

const addPermission = (permissionForm) => {

    $('#addPermission').validate({
        rules: {
            name: { required: true }
        },
        messages: {
            name: {
                required: 'El nombre es requerido',
            }
        }
    });

    permissionForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        if(!$("#addPermission").valid()){
            return;
        }

        const form = new FormData(event.target);
        const response = await fetch('api/permissions', {
            method: "POST",
            headers: {
                Authorization: 'Bearer '+localStorage.getItem('token'),
                Accept: 'application/json'
            },
            body: form
        });

        const responseJson = await response.json();
        console.log(responseJson);
        
    })
}

export {availablePermissions, detachPermission}