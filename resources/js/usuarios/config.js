import { eventListener } from '../utils/event';
import { detachRole, showRoles } from '../auth/roles/roles';
import { availablePermissions, detachPermission } from '../auth/permissions/permisos';

// Imprimimos la data en el div
const loadData =  (data) => {

    let divContent = document.getElementById('content');
    divContent.innerHTML = "";
    divContent.innerHTML = data;

    eventListener('.detach-role', detachRole);
    eventListener('.atach-role', showRoles);
    
    eventListener('.detach-permission', detachPermission);
    eventListener('.atach-permission', availablePermissions);

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