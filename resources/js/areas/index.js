import { eventListener } from "../utils/event";
import { openModal, closeModal } from "../utils/modal";
import { showNotification } from "../utils/notification";

const areas = document.getElementById('areas');

const attachEventListeners = () => {
    eventListener('add-area', showFormArea);
    eventListener('edit-area', showFormEdit);
    eventListener('delete-area', confirmDelete);
};

const loadData = (data) => {

    let divContent = document.getElementById('content');
    divContent.innerHTML = "";

    divContent.innerHTML = data;
    attachEventListeners();

}

const getAreas = async () => {

    const response = await fetch('/areas', {
        method: "GET",
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token'),
            Accept: 'application/json'
        }
    });

    if (!response.ok) {
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

// Con el cambio MPA no se requiere esta función
// areas.addEventListener('click', (event) => {
//     event.preventDefault();
//     getAreas();
// });

const addArea = async (registerForm) => {
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

        if (!$("#addArea").valid()) {
            return;
        }

        const form = new FormData(event.target);
        console.log(form);
        const response = await fetch('api/areas', {
            method: "POST",
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token'),
                Accept: 'application/json'
            },
            body: form
        });

        const responseJson = await response.json();
        if (responseJson.data.attributes.statusCode === 201) {
            closeModal();
            showNotification('Éxito', 'Operacion realizada con éxito', 'success');
            getAreas();
        }

    });
}

const editArea = (element) => {

    $('#editArea').validate({
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

    element.onsubmit = async (event) => {

        event.preventDefault();

        const area = document.getElementById('areaId').value;
        const formData = new FormData(event.target);

        const formObject = {};
        formData.forEach((value, key) => {
            formObject[key] = value;
        });

        console.log(formObject);

        const response = await fetch(`/api/areas/${area}`, {
            method: "POST",
            headers: {
                'Authorization': "Bearer " + localStorage.getItem('token'),
                'X-HTTP-Method-Override': 'PUT',
                'Accept': 'application/json'
            },
            body: formData
        });

        const responseJson = await response.json();

        closeModal();
        showNotification('Éxito', 'Operacion realizada con éxito', 'success');
        getAreas();

        console.log(responseJson);
    }
}


const showFormEdit = async (area) => {

    const response = await fetch(`/areas/${area}/edit`, {
        method: "GET",
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            'Accept': 'application/json'
        }
    });

    const responseText = await response.text();
    openModal(responseText, 'Editar Area');

    const element = document.getElementById('editArea');
    editArea(element);
}

// Modal para agregar un rol
const showFormArea = async () => {

    const response = await fetch('/areas/create', {
        method: "GET",
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
        }
    });

    const responseText = await response.text();

    console.log(responseText);
    openModal(responseText, 'Agregar Nueva Área');
    let registerArea = document.getElementById('addArea');

    addArea(registerArea);

}

// Eliminar usuario
const deleteArea = async (area) => {

    try {

        const response = await fetch(`api/areas/${area}`, {
            method: "DELETE",
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        if (response.status === 204) {
            getAreas();
            return true;
        }
    } catch (error) {

    }


}

// Confirm Elimina Usuario
const confirmDelete = (area) => {
    console.log('Delete Rol', area);
    Swal.fire({
        title: "¿Desea continuar?",
        text: "Está a punto de eliminar el área",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: 'Cancelar',
        confirmButtonText: "Eliminar"
    }).then((result) => {
        if (result.isConfirmed) {

            if (deleteArea(area)) {

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

// Editar
document.addEventListener('click', (event) => {
    if (event.target.classList.contains('edit-area')) {
        event.preventDefault();
        let id = event.target.dataset.id || event.target.id;
        showFormEdit(id);
    }
})