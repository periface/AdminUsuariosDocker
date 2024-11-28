import { configUser } from '../../usuarios/config';
import { openModal, closeModal } from '../../utils/modal';
import { eventListener } from '../../utils/event';



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
    openModal(responseText);
    eventListener('.atach-role', atachRole);
}

export {showRoles, detachRole};