import { createToast, show_confirm_action } from "../utils/helpers";
const state = {
    API_URL: "/api/v1/dimension",
    WEB_URL: "/dimension",
    isTableLoading: false,
    tableDefaultState: {
        page: 1,
        limit: 10,
        sort: 'id',
        order: 'asc',
        search: ''
    },
    tableReq: {
        page: 1,
        limit: 10,
        sort: 'id',
        order: 'asc',
        search: ''
    },

    columnEventsSet: false,
    rowsEventsSet: false,
    footerEventsSet: false,
    xcsrftoken: document.querySelector('meta[name="csrf-token"]').content || '',
    bearertoken: localStorage.getItem('token'),
    dimensionModal: $("#dimensionModal"),

    dimensionesTableContainer: document.getElementById('table-container'),
    dimensionesForm: document.getElementById('dimensionForm'),
    dimensionesFieldContainer: document.getElementById('dimensionFields'),
    modalOpenButtons: document.getElementsByClassName('dimensionModalBtn'),
    rowsPerPageInput: document.getElementsByClassName('js-change-rows'),
    changePageInput: document.getElementsByClassName('js-change-page'),
    deleteDimensionButton: document.getElementsByClassName('js-delete-dimension'),
    searchInput: document.getElementsByClassName('js-search'),
    sort: document.getElementsByClassName('sort'),
    validateForm: () => {
        $("#dimensionForm").validate({
            rules: {
                nombre: { required: true, minlength: 4 },
                descripcion: { required: true, minlength: 4 }
            },
            messages: {
                nombre: {
                    required: 'El nombre es requerido',
                    minlength: 'Debe tener al menos 4 caracteres'
                },
                descripcion: {
                    required: 'La descripción es requerida',
                    minlength: 'Debe tener al menos 4 caracteres'
                },
            }
        });
    }
}

async function fetch_rows() {
    try {
        state.rowsEventsSet = false; // los rows cambian, por lo que los eventos también
        state.footerEventsSet = false; // los rows cambian, por lo que los eventos también
        state.columnEventsSet = false; // los rows cambian, por lo que los eventos también
        state.isTableLoading = true;
        // toy usando vistas parciales, por que soy bien huevon
        // todo: paginar, ordenar y filtrar (a lo mejor aquí no)
        const response = await fetch(state.WEB_URL + '/get_table_rows', {
            method: 'POST',
            body: JSON.stringify(state.tableReq),
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken,
                "Accept": "application/json, text/plain, */*",
                "X-Requested-With": "XMLHttpRequest",
                "Content-Type": "application/json"
            },
            credentials: 'same-origin'
        });
        const rowsHTML = await response.text();
        state.isTableLoading = false;
        state.dimensionesTableContainer.innerHTML = rowsHTML;

        bind_header_events();
        await bind_modal_events();
        bind_footer_events();
    } catch (error) {
        state.isTableLoading = false;
        state.dimensionesTableContainer.innerHTML = `
        <h1 class="text-center">
            <p colspan="5"
             class="text-center">Ocurrió un error inesperado
             <br> ${error}
            </p>
        </h1>`;
    }
}
function bind_footer_events() {
    if (state.footerEventsSet) {
        return;
    }
    for (let rowsPerPageInput of state.rowsPerPageInput) {
        rowsPerPageInput.addEventListener('change', function() {
            changeLimit(this.value);
        });
    }
    for (let changePageInput of state.changePageInput) {
        changePageInput.addEventListener('click', function() {
            const page = this.dataset.page;
            changePage(page);
        });
    }
    state.footerEventsSet = true;
}
/**
 * Binds header events once, like sorting
 */
function bind_header_events() {
    if (state.columnEventsSet) {
        return;
    }
    // SORT EVENTS
    const elements = state.sort
    for (let element of elements) {
        element.addEventListener('click', (_) => {
            if (element.dataset.sort === state.tableReq.sort) {
                state.tableReq.order = state.tableReq.order === 'asc' ? 'desc' : 'asc';
                changeSort(element.dataset.sort, state.tableReq.order);
            } else {
                changeSort(element.dataset.sort, 'asc');
            }

        });
    }
    const searchInput = state.searchInput[0];
    searchInput.addEventListener('input', debounce(function() {
        searchTable(searchInput.value);
    }, 1000));
    state.columnEventsSet = true;
}
async function start_view() {
    await fetch_rows();
}

function debounce(func, wait, immediate) {
    let timeout;
    return function() {
        const context = this,
            args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}
async function bind_modal_events() {
    if (state.rowsEventsSet) {
        return;
    }
    for (let modalOpenButton of state.modalOpenButtons) {
        modalOpenButton.addEventListener('click', async function() {
            const id = this.dataset.id;
            await load_dimension_fields(id);
            state.dimensionModal.modal('show');
            state.dimensionesForm.onsubmit = async (e) => {
                e.preventDefault();
                state.validateForm();
                let formData = new FormData(e.target);

                const response = await fetch(state.API_URL, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': state.xcsrftoken,
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    }
                });
                const responseJson = await response.json();
                console.log(responseJson);
                if (!responseJson.error) {
                    state.dimensionModal.modal('hide');
                    await fetch_rows();
                    createToast('Administración de Dimensiones',
                        `Se guardó correctamente la información.
                         <a href="${state.WEB_URL}/${responseJson.data}"
                        class="btn btn-success btn-sm">Ver</a>`, true);
                } else {
                    console.log(responseJson);
                    createToast('Administración de Dimensiones', responseJson.error, false);
                }
            }
        });
    }

    for (let deleteButton of state.deleteDimensionButton) {
        deleteButton.addEventListener('click', async function() {
            const confirmResponse = await show_confirm_action();

            if (confirmResponse) {
                const id = deleteButton.dataset.id;
                const response = await fetch(state.API_URL + '/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': state.xcsrftoken,
                        'Authorization': 'Bearer ' + state.bearertoken,
                    }
                });
                const responseJson = await response.json();
                console.log(responseJson);
                if (!responseJson.error) {
                    createToast('Administración de Dimensiones', 'Se eliminó correctamente la información', true);
                    await fetch_rows();
                } else {
                    createToast('Administración de Dimensiones', responseJson.error, false);
                }
            }
        });
    }

    state.rowsEventsSet = true;
}
start_view().then(() => {
    console.log(state.isTableLoading);
}).catch((error) => {
    console.log(error);
})


// EVENT HANDLERS

//get_dimension_fields
async function load_dimension_fields(id) {
    let url = state.WEB_URL + '/get_dimension_fields';
    if (id) {
        url += '?id=' + id;
    }
    const response = await fetch(url, {
        headers: {
            'X-CSRF-TOKEN': state.xcsrftoken
        }
    });
    const fieldsHTML = await response.text();
    state.dimensionesFieldContainer.innerHTML = fieldsHTML;
}
function changePage(page) {
    state.tableReq.page = page;
    fetch_rows().then(() => {
        console.log('Page changed');
    }).catch((error) => {
        console.log(error);
    })
}
function changeLimit(limit) {
    state.tableReq.limit = limit;
    state.tableReq.page = 1;
    fetch_rows().then(() => {
        console.log('Limit changed');
    }).catch((error) => {
        console.log(error);
    })
}
function changeSort(sort, order) {
    state.tableReq.sort = sort;
    state.tableReq.order = order;

    fetch_rows().then(() => {
        console.log('Sort changed');
    }).catch((error) => {
        console.log(error);
    });
}
function searchTable(search) {
    state.tableReq.search = search;
    fetch_rows().then(() => {
        console.log('Search changed');
    }).catch((error) => {
        console.log(error);
    })
}

