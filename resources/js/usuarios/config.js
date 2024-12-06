import { eventListener } from '../utils/event';
import { detachRole, showRoles } from '../auth/roles/roles';
import { availablePermissions, detachPermission } from '../auth/permissions/permisos';


const attachEventListeners = () => {
    eventListener('detach-role', detachRole);
    eventListener('atach-available-role', showRoles);
    eventListener('detach-permission', detachPermission);
    eventListener('atach-available-permission', availablePermissions);
};

// Imprimimos la data en el div
const loadData =  (data) => {

    let divContent = document.getElementById('content');
    divContent.innerHTML = data;
    attachEventListeners();
}

const configUser = async (user) => {

    console.log(user);
    const response = await fetch(`users/${user}/roles-permissions`,{
        method: 'GET',
        headers: {
            'Authorization': 'Bearer '+localStorage.getItem('token')
        }
    });

    const responseText = await response.text();

    loadData(responseText);

}

export { configUser };