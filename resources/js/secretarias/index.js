
import { Repl } from 'pochijs';
import { delete_secretaria, get_rows, load_secretaria_form, post_secretaria } from './cruds.js';
import { debounce, createToast, show_confirm_action, restart_popovers } from '../utils/helpers.js';
const state = {
    API_URL: "/api/v1/secretaria",
    WEB_URL: "/secretaria",
    is_table_loading: false,
    table_default_state: {
        page: 1,
        limit: 10,
        sort: 'id',
        order: 'asc',
        search: ''
    },
    table_req: {
        page: 1,
        limit: 10,
        sort: 'id',
        order: 'asc',
        search: ''
    },
    secretaria_id: null,
    columns_events_set: false,
    rows_events_set: false,
    footer_events_set: false,
    confirm_indicador_events_set: false,
    xcsrftoken: document.querySelector('meta[name="csrf-token"]').content || '',
    bearertoken: localStorage.getItem('token') || '',
    secretaria_view: $("#secretariaModal"),
    secretarias_table_container: document.getElementById('table-container'),
    secretaria_form: document.getElementById('secretariaForm'),
    secretarias_field_container: document.getElementById('secretariaFields'),
    modal_open_buttons: document.getElementsByClassName('secretariaModalBtn'),
    rows_per_page_input: document.getElementsByClassName('js-change-rows'),
    change_page_input: document.getElementsByClassName('js-change-page'),
    delete_indicador_button: document.getElementsByClassName('js-delete-secretaria'),
    search_input: document.getElementsByClassName('js-search'),
    sort: document.getElementsByClassName('sort'),
    confirm_secretaria_btn: document.getElementById('js-confirm-secretaria'),
    guardar_indicador_btn: document.getElementById('js-guardar-secretaria'),

    repl: new Repl(),
    repl_result: null,
    restart_popovers: restart_popovers,
    validate_form: () => {
        $("#secretariaForm").validate({
            rules: {
                nombre: { required: true, minlength: 4 },
                descripcion: { required: true, minlength: 4 },
                medio_verificacion: { required: true, minlength: 4 },
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
                medio_verificacion: {
                    required: 'El medio de verificación es requerido',
                    minlength: 'Debe tener al menos 4 caracteres'
                },
            }
        });
    }
}

async function start_datatable() {
    try {
        state.rows_events_set = false; // los rows cambian, por lo que los eventos también
        state.footer_events_set = false; // los rows cambian, por lo que los eventos también
        state.columns_events_set = false; // los rows cambian, por lo que los eventos también
        state.is_table_loading = true;
        const { data, error } = await get_rows(state);
        const html = data;
        if (error) {
            throw new Error(error);
        }
        state.is_table_loading = false;
        state.secretarias_table_container.innerHTML = html;
        set_table_header_events();
        await set_modal_trigger_evts();
        set_table_footer_events();
        state.restart_popovers();
    } catch (error) {
        state.is_table_loading = false;
        state.secretarias_table_container.innerHTML = `
        <h1 class="text-center">
            <p colspan="5"
             class="text-center">${error}
            </p>
        </h1>`;
        console.error(error);
    }
}
start_view().then(() => {
    state.secretaria_view.on('hidden.bs.modal', function() {
        state.repl_result = null;
    });
}).catch((error) => {
    console.log(error);
})


// EVENT HANDLERS

function set_table_footer_events() {
    if (state.footer_events_set) {
        return;
    }
    for (let row_per_page of state.rows_per_page_input) {
        row_per_page.addEventListener('change', function() {
            change_page_limit(this.value);
        });
    }
    for (let change_page_input of state.change_page_input) {
        change_page_input.addEventListener('click', function() {
            const page = this.dataset.page;
            change_page(page);
        });
    }
    state.footer_events_set = true;
}
async function open_secretaria_form_evt(modal_open_btn, load_set_formula_window = false) {
    modal_open_btn.addEventListener('click', async function() {
        const id = this.dataset.id;
        await render_secretaria_form(id, load_set_formula_window);
        state.secretaria_view.modal('show');
        state.validate_form();
        state.secretaria_form.onsubmit = async (e) => {
            e.preventDefault();
            const form_data = new FormData(state.secretaria_form);
            state.secretaria_id = id;
            // agregamos los campos si hay un repl_result, es decir, si se ha evaluado la fórmula
            const response_json = await post_secretaria(form_data, state);
            if (!response_json.error && response_json?.data.statusCode === 200) {
                state.secretaria_view.modal('hide');
                await start_datatable();
                createToast('Administración de Secretarias',
                    `Se guardó correctamente la información.
                    <a href="${state.WEB_URL}/${response_json.data}"
                    class="btn btn-success btn-sm">Ver</a>`,
                    true);
                // reinitialize repl_result
            } else {
                if (response_json.data.statusCode === 422) {
                    return;
                }
                createToast('Administración de Secretarias',
                    `Ocurrió un error al guardar la información.
                ${response_json.error}
`,
                    false);
            }
        }
    });
}
/**
 * Binds header events once, like sorting
 */
function set_table_header_events() {
    if (state.columns_events_set) {
        return;
    }
    // SORT EVENTS
    const elements = state.sort
    for (let element of elements) {
        element.addEventListener('click', (_) => {
            if (element.dataset.sort === state.table_req.sort) {
                state.table_req.order = state.table_req.order === 'asc' ? 'desc' : 'asc';
                changeSort(element.dataset.sort, state.table_req.order);
            } else {
                changeSort(element.dataset.sort, 'asc');
            }

        });
    }
    const search_input = state.search_input[0];
    search_input.addEventListener('input', debounce(function() {
        searchTable(search_input.value);
    }, 1000));
    state.columns_events_set = true;
}
function delete_secretaria_evt(delete_button) {
    delete_button.addEventListener('click', async function() {
        const id = delete_button.dataset.id;
        const delete_secretaria_confirmed = await show_confirm_action();
        if (delete_secretaria_confirmed) {
            const response_json = await delete_secretaria(id, state);
            if (!response_json.error) {
                createToast('Administración de Secretarias',
                    `Se eliminó correctamente la información.`,
                    true);
                await start_datatable();
            } else {
                createToast('Administración de Secretarias',
                    `Ocurrió un error al eliminar la información.`,
                    false);
            }
        }
    });

}
async function set_modal_trigger_evts() {
    if (state.rows_events_set) {
        return;
    }
    for (let modal_open_btn of state.modal_open_buttons) {
        await open_secretaria_form_evt(modal_open_btn);
    }
    for (let delete_button of state.delete_indicador_button) {
        delete_secretaria_evt(delete_button);
    }

    state.rows_events_set = true;
}
//get fields for secretaria
async function render_secretaria_form(id) {
    const { data, error } = await load_secretaria_form(id, state);
    if (error) {
        state.secretarias_field_container.innerHTML = error;
        return;
    }
    state.secretarias_field_container.innerHTML = data;
}

async function start_view() {
    await start_datatable();
}

// TABLE EVENTS
function change_page(page) {
    state.table_req.page = page;
    start_datatable().then(() => {
        console.log('Page changed');
    }).catch((error) => {
        console.log(error);
    })
}
function change_page_limit(limit) {
    state.table_req.limit = limit;
    state.table_req.page = 1;
    start_datatable().then(() => {
        console.log('Limit changed');
    }).catch((error) => {
        console.log(error);
    })
}
function changeSort(sort, order) {
    state.table_req.sort = sort;
    state.table_req.order = order;

    start_datatable().then(() => {
        console.log('Sort changed');
    }).catch((error) => {
        console.log(error);
    });
}
function searchTable(search) {
    state.table_req.search = search;
    start_datatable().then(() => {
        console.log('Search changed');
    }).catch((error) => {
        console.log(error);
    })
}
