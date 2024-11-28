const closeModal = () => {
    const modalElement = document.getElementById('modalConfig');
    const modal = bootstrap.Modal.getInstance(modalElement); // Obtener instancia del modal existente
    modal.hide();
}

const openModal = (data) => {

    let modal = new bootstrap.Modal(document.getElementById('modalConfig'));
    let modalBody = document.querySelector('.modal-body');

    modalBody.innerHTML = "";
    modalBody.innerHTML = data;
    modal.show();

}

export {openModal, closeModal};