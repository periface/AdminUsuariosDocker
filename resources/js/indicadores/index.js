import { Repl } from 'pochijs';
import { delete_indicador, get_rows, load_indicador_form, post_indicador } from './cruds.js';
import { debounce, createToast, show_confirm_action, assert, restart_popovers } from '../utils/helpers.js';
const state = {
    API_URL: "/api/v1/indicador",
    WEB_URL: "/indicador",
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

    columns_events_set: false,
    rows_events_set: false,
    footer_events_set: false,
    confirm_indicador_events_set: false,
    xcsrftoken: document.querySelector('meta[name="csrf-token"]').content || '',

    dimensionId: document.querySelector('meta[name="dimensionId"]').content || '',
    bearertoken: localStorage.getItem('token') || '',
    indicador_view: $("#indicadorModal"),
    indicadores_table_container: document.getElementById('table-container'),
    indicadores_form: document.getElementById('indicadorForm'),
    indicadores_field_container: document.getElementById('indicadorFields'),
    variables_container: document.getElementById('js-variables'),
    modal_open_buttons: document.getElementsByClassName('indicadorModalBtn'),
    rows_per_page_input: document.getElementsByClassName('js-change-rows'),
    change_page_input: document.getElementsByClassName('js-change-page'),
    set_formula_buttons: document.getElementsByClassName('js-set-formula'),
    delete_indicador_button: document.getElementsByClassName('js-delete-indicador'),
    search_input: document.getElementsByClassName('js-search'),
    sort: document.getElementsByClassName('sort'),
    metodo_calculo_input: document.getElementsByClassName('metodo_calculo'),
    confirm_indicador_btn: document.getElementById('js-confirm-indicador'),
    guardar_indicador_btn: document.getElementById('js-guardar-indicador'),

    repl: new Repl(),
    repl_result: null,
    restart_popovers: restart_popovers,
    validate_form: () => {
        $("#indicadorForm").validate({
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
        state.indicadores_table_container.innerHTML = html;
        set_table_header_events();
        await set_modal_trigger_evts();
        set_table_footer_events();
        state.restart_popovers();
    } catch (error) {
        state.is_table_loading = false;
        state.indicadores_table_container.innerHTML = `
        <h1 class="text-center">
            <p colspan="5"
             class="text-center">${error}
            </p>
        </h1>`;
        console.error(error);
    }
}
function update_metodo_calculo(formula_str) {
    state.repl.skippable_words = ['de', 'la', 'el', 'en', 'con', 'del', 'al', 'a', 'por']
    if (!formula_str) {
        return;
    }
    const pochi_result = state.repl.parse_formula(formula_str);
    if (pochi_result.error) {
        createToast('Administración de Indicadores',
            `Error al evaluar la fórmula: ${pochi_result.error}`,
            false);
        return;
    }
    const ilegals = pochi_result.tokens.filter(t => t.type === 'ILLEGAL');
    if (ilegals.length > 0) {
        createToast('Administración de Indicadores',
            `Error al evaluar la fórmula: ${ilegals.map(i => i.literal).join(', ')}`,
            false);
        return
    }
    state.repl_result = pochi_result;
    show_formula(pochi_result);
    state.restart_popovers();
}
function show_formula(pochi_result) {
    const samples = []
    const formula_container = document.getElementById('js-formula');
    const formula = pochi_result.non_evaluable_formula;
    const variables = pochi_result.variables;
    if (samples.length === 0) {
        pochi_result.variables.forEach(v => {
            const code = v.code;
            const sample = {
                code,
                value: Math.floor(Math.random() * 100)
            };
            samples.push(sample);
        });
    }

    const evaluate_result = pochi_result.evaluate_with(samples);
    if (evaluate_result.error) {
        createToast('Administración de Indicadores',
            `Error al evaluar la fórmula: ${evaluate_result.error}`,
            false);
        return;
    }
    const variables_html = samples.map(s => {
        const c = variables.find(v => v.code === s.code);
        return `<span class="badge bg-primary text-xs" tabindex="0" data-bs-toggle="tooltip" title="${c.literal}">${s.code} = ${s.value}</span>`;
    }).join(' ');
    const formula_html = `
    <p class="text-sm mt-2">
        <strong>Formula:</strong> ${formula} <br>
        <strong>Resultado:</strong> ${evaluate_result.replaced_formula} <br>
        <br>
    </p>
    `;
    formula_container.innerHTML = variables_html + formula_html;
}
start_view().then(() => {
    state.indicador_view.on('hidden.bs.modal', function() {
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
async function set_indicador_confirm_btn_evt() {

    const form_data = new FormData(state.indicadores_form);

    // print all form data values
    for (var pair of form_data.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }

    if (!form_data.get('metodo_calculo')) {
        createToast('Administración de Indicadores',
            `Falta definir el método de cálculo`,
            false);
        return;
    }
    const confirm_indicador = await show_confirm_action('Confirmar Indicador',
        '¿Está seguro de confirmar el indicador? Una vez confirmado no podrá editarlo');
    if (!confirm_indicador) return;
    if (state.repl_result) {
        form_data.append('evaluable_formula', state.repl_result.evaluable_formula);
        form_data.append('non_evaluable_formula', state.repl_result.non_evaluable_formula);
        form_data.append('indicador_confirmado', 1);
        form_data.append("status", 1);
        const variables = state.repl_result.variables.map(v => {
            return {
                code: v.code,
                literal: v.literal
            }
        });
        form_data.append('variables', JSON.stringify(variables));
    }

    const { data, error } = await post_indicador(form_data, state);
    if (error) {
        createToast('Administración de Indicadores',
            `Ocurrió un error al confirmar el indicador.
${error}
`,
            false);
        update_metodo_calculo(form_data.get('metodo_calculo'));
        return;
    }
    const response_json = data;
    if (!response_json.error) {
        state.indicador_view.modal('hide');
        await start_datatable();
        createToast('Administración de Indicadores',
            `Se confirmó correctamente el indicador.
                    <a href="${state.WEB_URL}/${response_json.data}"
                    class="btn btn-success btn-sm">Ver</a>`,
            true);
    } else {
        createToast('Administración de Indicadores',
            `Ocurrió un error al confirmar el indicador.
            ${response_json.error}
`,
            false);
        update_metodo_calculo(form_data.get('metodo_calculo'));
    }
}
async function bind_confirm_indicador_evt() {
    const is_indicador_confirmado = document.getElementById('indicador_confirmado')?.value === '1';
    const metodo_calculo_input_exists = state.metodo_calculo_input.length > 0;
    if (!metodo_calculo_input_exists) {
        state.confirm_indicador_btn.hidden = true;
        return;
    }
    if (is_indicador_confirmado) {
        state.confirm_indicador_btn.hidden = true;
        return;
    }
    else {
        state.confirm_indicador_btn.hidden = false;
    }
    if (!state.confirm_indicador_events_set) {
        state.confirm_indicador_btn.addEventListener('click', async function() {
            await set_indicador_confirm_btn_evt();
        });
        state.confirm_indicador_events_set = true;
    }
}
function is_number(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}
async function open_indicador_form_evt(modal_open_btn, load_set_formula_window = false) {
    modal_open_btn.addEventListener('click', async function() {
        const id = this.dataset.id;
        await render_indicador_form(id, load_set_formula_window);
        state.indicador_view.modal('show');
        await bind_confirm_indicador_evt();
        state.validate_form();
        state.indicadores_form.onsubmit = async (e) => {
            e.preventDefault();
            const form_data = new FormData(state.indicadores_form);
            const status = form_data.get('status');
            if (!is_number(status)) {
                status === 'on' ? form_data.set('status', 1) : form_data.set('status', 0);
            }
            const requiere_anexo = form_data.get('requiere_anexo');
            requiere_anexo === 'on' ? form_data.set('requiere_anexo', 1) : form_data.set('requiere_anexo', 0);
            // agregamos los campos si hay un repl_result, es decir, si se ha evaluado la fórmula
            if (state.repl_result) {
                form_data.append('evaluable_formula', state.repl_result.evaluable_formula);
                form_data.append('non_evaluable_formula', state.repl_result.non_evaluable_formula);
                const variables = state.repl_result.variables.map(v => {
                    return {
                        code: v.code,
                        literal: v.literal
                    }
                });
                form_data.append('variables', JSON.stringify(variables));
            }
            const response_json = await post_indicador(form_data, state);
            if (!response_json.error && response_json?.data.statusCode === 200) {
                state.indicador_view.modal('hide');
                await start_datatable();
                createToast('Administración de Dimensiones',
                    `Se guardó correctamente la información.
                    <a href="${state.WEB_URL}/${response_json.data}"
                    class="btn btn-success btn-sm">Ver</a>`,
                    true);
                // reinitialize repl_result
            } else {
                if (response_json.data.statusCode === 422) {
                    return;
                }
                createToast('Administración de Dimensiones',
                    `Ocurrió un error al guardar la información.
                ${response_json.error}
`,
                    false);
                update_metodo_calculo(form_data.get('metodo_calculo'));
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
function set_modal_loaded_evts() {
    for (let metodo_calculo_input of state.metodo_calculo_input) {
        metodo_calculo_input.addEventListener('selectionchange',
            debounce(function() {
                metodo_calculo_input.addEventListener('change', function() {
                    if (this.value) {
                        state.confirm_indicador_btn.disabled = false;
                    } else {
                        state.confirm_indicador_btn.disabled = true;
                    }
                });
                update_metodo_calculo(metodo_calculo_input.value);
            }, 500)
        );
    }
}
function delete_indcador_evt(delete_button) {
    delete_button.addEventListener('click', async function() {
        const id = delete_button.dataset.id;
        const delete_indicador_confirmed = await show_confirm_action();
        if (delete_indicador_confirmed) {
            const response_json = await delete_indicador(id, state);
            if (!response_json.error) {
                createToast('Administración de Dimensiones',
                    `Se eliminó correctamente la información.`,
                    true);
                await start_datatable();
            } else {
                createToast('Administración de Dimensiones',
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
        await open_indicador_form_evt(modal_open_btn);
    }
    for (let set_formula_button of state.set_formula_buttons) {
        await open_indicador_form_evt(set_formula_button, true);
    }
    for (let delete_button of state.delete_indicador_button) {
        delete_indcador_evt(delete_button);
    }

    state.rows_events_set = true;
}
//get fields for indicador
async function render_indicador_form(id, load_set_formula_window = false) {
    const { data, error } = await load_indicador_form(id, load_set_formula_window, state);
    if (error) {
        state.indicadores_field_container.innerHTML = error;
        return;
    }
    state.indicadores_field_container.innerHTML = data;
    // after loading the fields, we need to parse the formula for now
    const formula = document.getElementById('metodo_calculo');
    if (formula && load_set_formula_window) {
        set_modal_loaded_evts();
        update_metodo_calculo(formula.value);
    }
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
