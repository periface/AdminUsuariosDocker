const WEB_URL = '/users';
const API_URL = '/api/users';

/**
 * Método GET para obtener todos los usuarios
 * @returns response.text.- Regresa una tabla con la lista de usuarios
 */
export const fetchUsers = async () => {
    try {

        const response = await fetch(`${WEB_URL}/fetchUsers`, {
            method: "GET",
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        return response.text();

    } catch (error) {
        console.log('Error al obtener información de los usuarios', error);
        return `<p>Ocurrió un error al obtener la información de los usuarios</p>`
    }
}

/**
 * Método GET para regresar el formulario de alta de usuarios
 * @returns response.text.- Retorna el formulario de alta de usuarios
 */
export const fetchUserForm = async () => {
    try {
        const response = await fetch(`${WEB_URL}/create`, {
            method: "GET",
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        return await response.text();
    } catch (error) {
        console.log('Error al obtener formulario', error);
        return `<p>Ocurrió un error al obtener el formulario.</p>`
    }
}

export const fetchEditFormUser = async (user) => {
    try {
        const response = await fetch(`${WEB_URL}/${user}/edit`, {
            method: "GET",
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        return await response.text();
    } catch (error) {
        console.log('Error al obtener formulario', error);
        return `<p>Ocurrió un error al obtener el formulario.</p>`
    }
}

/**
 * Método POST para el alta de usuarios
 * @param { formData } recibe el formulario con la información del usuario
 * @returns response.json.- Retorna objeto json con status y mensaje en caso de éxito
 * @returns null en caso de error
 */
export const registerUser = async (formData) => {
    try {
        const response = await fetch('api/users/register', {
            method: "POST",
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token'),
                Accept: 'application/json'
            },
            body: formData
        });

        return await response.json();

    } catch (error) {
        console.log('Error al registrar usuario', error);
        return null;
    }
}

export const updateUser = async (formData, user) => {
    try {
        const response = await fetch(`${API_URL}/${user}`, {
            method: "POST",
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json',
            },
            body: formData
        });

        return await response.json();

    } catch (error) {
        console.log('Error al registrar usuario', error);
        return null;
    }
}

/**
 * 
 * @param {*} user 
 */
export const eliminarUsuario = async (user) => {

    try {
        const response = await fetch(`${API_URL}/${user}`, {
            method: "DELETE",
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });
        console.log(response);
        if (response.status === 204) {
            return true;
        }


    } catch (error) {
        console.log('Ocurrió un error al eliminar el usuario', error);
        return false;
    }

}