import { showFormUser, loadUsers, confirmDelete } from "./actionUsers";

document.addEventListener('DOMContentLoaded', () => {
    loadUsers();
    document.getElementById("add-user").addEventListener("click", showFormUser);
    document.addEventListener('click', function (event) {
        let targetClass = event.target.classList;
        switch (true) {
            case targetClass.contains('delete-user'):
                confirmDelete(event.target.dataset.id);
                break;
            case targetClass.contains('edit-user'):
                console.log('Editando')
                break;

            default:
                break;
        }
    });
});

