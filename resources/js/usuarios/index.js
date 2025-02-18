import { showFormUser, loadUsers, confirmDelete } from "./actionUsers";

document.addEventListener('DOMContentLoaded', () => {
    loadUsers();
    document.getElementById("add-user").addEventListener("click", showFormUser);
    document.addEventListener('click', function (event) {
        switch (event.target.classList.contains) {
            case 'delete-user':
                confirmDelete(event.target.dataset.id);
                break;
            case 'edit-user':
                console.log('Editando')
                break;

            default:
                break;
        }
    });
});

