import { showFormUser, loadUsers, confirmDelete, editFormUser } from "./actionUsers";

document.addEventListener('DOMContentLoaded', () => {
    loadUsers();
    document.getElementById("add-user").addEventListener("click", showFormUser);

    document.addEventListener('click', function (event) {
        let targetClass = event.target.classList;

        if (event.target.classList.contains('delete-user')) {
            confirmDelete(event.target.dataset.id);
        } else if (event.target.classList.contains('edit-user')) {
            editFormUser(event.target.dataset.id);
        }
    });
});

