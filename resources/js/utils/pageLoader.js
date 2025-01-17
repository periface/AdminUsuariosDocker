const menubuttons = document.getElementsByClassName('menu-button');
const router = [
    {
        page: 'users',
        route: '/users',
        containerId: 'content',
    },
    {
        page: 'roles',
        route: '/roles',
        containerId: 'content',
    },
    {
        page: 'permissions',
        route: '/permissions',
        containerId: 'content',
    }
]

function init() {
    console.log('Iniciando...');

    menubuttons.onclick = (event) => {
        event.preventDefault();
        loadPage('/users', 'content', attachEventListeners);
    }
}
init();

// Función para mostrar la lista de los usuarios
const loadData = (data, containerId, callback) => {
    if (!containerId) {
        containerId = 'content';
    }
    let divContent = document.getElementById(containerId);
    divContent.innerHTML = "";
    divContent.innerHTML = data;
    if (callback) {
        callback();
    }
}
const loadPage = async (route, containerId, callback) => {
    try {
        const response = await fetch(route, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
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
        loadData(responseText, containerId, callback);
    } catch (error) {
        console.log('Es el response', error);
    }
}

export { loadData, loadPage };
