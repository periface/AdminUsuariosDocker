import { configUser } from '../../usuarios/config';
import { openModal, closeModal } from '../../utils/modal';
import { eventListener } from '../../utils/event';

// Función para mostrar la lista de los usuarios
const loadData =  (data) => {

    let divContent = document.getElementById('content');
    divContent.innerHTML = "";

    divContent.innerHTML = data;

    eventListener('.edit-role', showFormEdit);
    eventListener('.delete-role', confirmDelete);
    eventListener('.add-role', showFormRole);
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
    console.log(user);
    openModal(responseText, 'Roles Disponibles');
    eventListener('.atach-role', atachRole);
}

const getRoles = async () => {
    
    const response = await fetch('/roles', {
        method: "GET",
        headers: {
            'Authorization' : 'Bearer '+localStorage.getItem('token')
        }
    })

    const responseText = await response.text();
    loadData(responseText);
    
}

let element = document.getElementById('roles');
element.onclick = (event) => {
    event.preventDefault();
    getRoles();
}

const showFormEdit = async(data) => {

    const response = await fetch('/roles/edit-role/' + data, {
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
    
    const response = await fetch('/roles/add', {
        method: "GET",
        headers:{
            Authorization: "Bearer "+localStorage.getItem('token')
        }
    });

    const responseText = await response.text();

    let modal = new bootstrap.Modal(document.getElementById('modalConfig'));
    let modalBody = document.querySelector('.modal-body');
    let modalTitle = document.querySelector('.modal-title');

    modalTitle.innerHTML = "Agregar Rol";
    modalBody.innerHTML = "";
    modalBody.innerHTML = responseText;

    modal.show();

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
        console.log(responseJson);
    });
}

export {showRoles, detachRole};