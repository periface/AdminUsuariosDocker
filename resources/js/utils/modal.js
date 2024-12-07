const closeModal = () => {
    const modalElement = document.getElementById('modalConfig');
    const modal = bootstrap.Modal.getInstance(modalElement); // Obtener instancia del modal existente
    modal.hide();
}

const openModal = (data, title) => {

    let modal = new bootstrap.Modal(document.getElementById('modalConfig'), {
        backdrop: 'static'
    });
    let modalBody = document.querySelector('.modal-body');
    let modalTitle = document.querySelector('.modal-title');

    modalTitle.innerHTML = title;

    modalBody.innerHTML = data;
    modal.show();

}

export {openModal, closeModal};