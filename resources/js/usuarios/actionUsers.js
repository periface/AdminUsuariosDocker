import { closeModal, openModal } from "../utils/modal";
import { fetchUserForm, fetchUsers, registerUser, eliminarUsuario, fetchEditFormUser, updateUser } from "./apiUser";
import { showNotification } from "../utils/notification";


/**
 * Función para obtener una tabla con todos los usuarios e inyectarla en la vista index
 */
export const loadUsers = async () => {
    const tableUsers = document.getElementById('table-users');
    tableUsers.innerHTML = `
         <div class="d-flex justify-content-center mt-4">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    const response = await fetchUsers();
    tableUsers.innerHTML = await response;
}

/**
 * Función para mostrar el formulario de alta en un modal
 */
export const showFormUser = async () => {

    const formHtml = await fetchUserForm();
    openModal(formHtml, 'Agregar Usuario');
    setTimeout(() => attachFormEvents(), 100);

}

/**
 * Función para agregar eventos y validaciones al formulario antes de procesarlo
 */
const attachFormEvents = () => {

    let userForm = document.getElementById("userForm");
    $('#userForm').validate({
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

    userForm.onsubmit = async (event) => {

        event.preventDefault();
        if (!$("#userForm").valid()) return;

        const formData = new FormData(event.target);
        const responseJson = await registerUser(formData);

        if (responseJson.data.attributes.statusCode === 201) {
            closeModal();
            showNotification('Éxito', responseJson.data.attributes.data, 'success');
            setTimeout(() => loadUsers(), 800);
        } else {
            console.log(responseJson);
            showNotification('Solicitud no procesada', responseJson.data.attributes.data, 'error');
        }
    }

}

export const editFormUser = async (user) => {
    const formHtml = await fetchEditFormUser(user);
    openModal(formHtml, 'Editar Usuario');

    let userForm = document.getElementById("userForm");
    $('#userForm').validate({
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
            apPaterno: {
                required: 'El apellido es requerido',
            }
        }
    });

    userForm.onsubmit = async (event) => {
        event.preventDefault();
        if (!$("#userForm").valid()) return;
        let user = document.getElementById("user").value;
        const formData = new FormData($("#userForm")[0]);

        formData.append("_method", "PUT");

        const responseJson = await updateUser(formData, user);
        if (responseJson.data.attributes.statusCode === 200) {
            closeModal();
            showNotification('Proceso Completado', responseJson.data.attributes.data, 'success');
            setTimeout(() => loadUsers(), 800);
        }
    }
}

export const confirmDelete = (user) => {
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

            if (eliminarUsuario(user)) {
                loadUsers();
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


let prevRolText = null;

// Capturar el valor previo antes de que el usuario cambie la opción
document.addEventListener('focus', function (event) {
    if (event.target.id === 'roleId') {
        const selectedOption = event.target.selectedOptions[0]; // Almacena el valor actual cuando el select gana el foco
        prevRolText = selectedOption.text;
    }
}, true);

document.addEventListener('change', function (event) {
    if (event.target.id === 'roleId') {
        const selectedOption = event.target.selectedOptions[0];
        const currentRolText = selectedOption.text; // El valor después de hacer el cambio

        console.log(event);
        console.log('Valor previo:', prevRolText);
        console.log('Valor actual:', currentRolText);

        // Hacer la comparación
        if (prevRolText === 'Supervisor de Área' && currentRolText !== prevRolText) {
            confirmAction('¿Desea continuar?', 'Si cambias el rol del usuario, el área quedará sin un responsable asignado.', 'warning');
        } else {
            console.log('El valor no cambió de "Supervisor de Área"');
        }
    }
});

const confirmAction = (title, text, icon) => {
    Swal.fire({
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: 'Cancelar',
        confirmButtonText: "Continuar"
    }).then((result) => {
        if (result.isConfirmed) {
            return true;
        } else {
            closeModal();
        }
    });
}