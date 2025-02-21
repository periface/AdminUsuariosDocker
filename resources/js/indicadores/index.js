// Describe el flujo del codigo:
// 1. Se inicializa el estado de la aplicación
// 2. Se inicializa la vista de la tabla
// 3. Se inicializan los eventos de la tabla
// 4. Se inicializan los eventos de los botones de abrir modal
// 5. Se inicializan los eventos de los botones de eliminar
// 6. Se inicializan los eventos de los botones de cambiar página
// 7. Se inicializan los eventos de los botones de cambiar número de filas
// 8. Se inicializan los eventos de los botones de ordenar
// 9. Se inicializan los eventos de los botones de búsqueda
// 10. Se inicializan los eventos de los botones de confirmar indicador
// 11. Se inicializan los eventos de los botones de guardar indicador
// 12. Se inicializan los eventos de los botones de setear fórmula
// // Interesanteeeee

import papaparse from "papaparse";
import { Repl } from 'pochijs';
import { delete_indicador, get_rows, load_indicador_form, post_indicador, get_dimension_by_name } from './cruds.js';
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

    dimensionId: 0,
    bearertoken: localStorage.getItem('token') || '',
    indicador_view: $("#indicadorModal"),
    indicador_batch_view: $("#indicadorBatchModal"),
    indicadores_table_container: document.getElementById('table-container'),
    indicadores_form: document.getElementById('indicadorForm'),
    indicadores_batch_form: document.getElementById('indicadorBatchForm'),
    indicadores_field_container: document.getElementById('indicadorFields'),
    indicadores_batch_field_container: document.getElementById('indicadorBatchFields'),
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

    subirBtn: document.getElementById('subir'),
    fileInput: document.getElementById('file'),
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
    const formula_container = document.getElementById('js-formula');
    const formula = pochi_result.non_evaluable_formula;
    const variables = pochi_result.variables;
    const samples = []
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
    state.repl.skippable_words = ['de', 'la', 'el', 'en', 'con', 'del', 'al', 'a', 'por']
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
            form_data.append('clave', form_data.get('clave'));
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
            if (!response_json.error) {
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
            console.log(response_json);
            if (!response_json.error) {
                createToast('Administración de Dimensiones',
                    `Se eliminó correctamente la información.`,
                    true);
                await start_datatable();
            } else {
                createToast('Administración de Dimensiones',
                    `${response_json.error}`,
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
        // enable button
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
function set_select_dimension_evt() {
    const select = document.getElementById('dimensiones_select');
    select.addEventListener('change', (e) => {
        state.dimensionId = e.target.value;
        for (let modal_open_btn of state.modal_open_buttons) {
            modal_open_btn.disabled = false;
        }
        start_datatable();
    });
}
async function start_view() {

    set_select_dimension_evt();
    state.indicadores_table_container.innerHTML = `
    <h1 class="text-center">
        <p colspan="5"
         class="text-center p-5 text-2xl">Seleccione una dimensión para ver los indicadores
        </p>
    </h1>`;
    await start_datatable();
    await bind_upload_file();
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



async function bind_upload_file() {
    state.subirBtn.addEventListener('click', async function() {
        state.fileInput.click();
    });
    state.fileInput.addEventListener('change', async function() {
        const file = this.files[0];
        let data = await parse_json(file);
        data = data.filter(row => row["Nombre"] !== '');
        const dimensiones = group_dimensiones(data);
        const indicadores_db = make_indicadores_db(dimensiones);
        for (let dimension in indicadores_db) {
            if (indicadores_db[dimension].error) {
                createToast('Administración de Dimensiones',
                    `Error en la dimensión ${dimension}: ${indicadores_db[dimension].error}`,
                    false);
                continue;
            }
        }
        render_indicadores_db(indicadores_db);
        set_indicadores_form_evt(indicadores_db);
    });
}
function validate_indicador(indicador) {
    const required_fields = ['nombre',
        'descripcion',
        'metodo_calculo',
        'medio_verificacion',
        'unidad_medida',
        'dimension',
        'sentido',
        'categoria'
    ];
    for (let field of required_fields) {
        console.log(field);
        if (!indicador[field]) {
            return {
                data: null,
                error: `El campo ${field} es requerido`.toUpperCase()
            }
        }
    }
    return {
        data: indicador,
        error: null
    }
}
function set_indicadores_form_evt(indicadores_db) {
    state.indicadores_batch_form.onsubmit = async (e) => {
        e.preventDefault();
        const badgeProcessing = `<span class="badge bg-primary text-xs">Procesando</span>`;
        const badgeProcessed = `<span class="badge bg-success text-xs">Procesado</span>`;
        const badgeError = `<span class="badge bg-danger text-xs">Error</span>`;
        let i = 0;
        for await (let indicador of indicadores_db) {
            const estado = document.querySelector(`.js-estados-${i}`);
            estado.innerHTML = badgeProcessing;
            const dimension_response = await get_dimension_by_name(indicador.dimension, state);
            if (dimension_response.error) {
                estado.innerHTML = badgeError + ' <br>' + dimension_response.error;
                i++;
                continue;
            }
            if (dimension_response.data.length === 0) {
                estado.innerHTML = badgeError + ' <br> No se encontró la dimensión';
                i++;
                continue;
            }
            const dimensionId = dimension_response.data[0].id;

            const validate_response = validate_indicador(indicador);
            if (validate_response.error) {
                estado.innerHTML = badgeError + ' <br>' + validate_response.error;

                i++;
                continue;
            }
            const form_data = new FormData();
            form_data.append('clave', indicador.clave);
            form_data.append('nombre', indicador.nombre);
            form_data.append('descripcion', indicador.descripcion);
            form_data.append('status', indicador.status);
            form_data.append('unidad_medida', indicador.unidad_medida.toLowerCase() || '');
            form_data.append('metodo_calculo', indicador.metodo_calculo);
            form_data.append('evaluable_formula', indicador.evaluable_formula);
            form_data.append('non_evaluable_formula', indicador.non_evaluable_formula);
            form_data.append('indicador_confirmado', indicador.indicador_confirmado);
            form_data.append('sentido', indicador.sentido);
            form_data.append('dimensionId', dimensionId);
            form_data.append('requiere_anexo', indicador.requiere_anexo);
            form_data.append('medio_verificacion', indicador.medio_verificacion);
            form_data.append('categoria', indicador.categoria);

            const response_json = await post_indicador(form_data, state);
            if (!response_json.error) {
                estado.innerHTML = badgeProcessed;
                indicador.error = null;
            } else {
                indicador.error = response_json.error;
                estado.innerHTML = badgeError + ' <br>' + response_json.error;
            }
            i++;
        }
    }

}
function render_indicadores_db(indicadores_db) {
    const container = state.indicadores_batch_field_container;
    container.innerHTML = `
<table class="table" id="indicadoresTable">
    <thead class="small">
        <tr class="w-full">

            <th style="width: 20%" data-sort="nombre" data-order="asc" class="sort cursor-pointer">
                Clave
            </th>
            <th style="width: 20%" data-sort="nombre" data-order="asc" class="sort cursor-pointer">
                Estado
            </th>
            <th style="width: 20%" data-sort="nombre" data-order="asc" class="sort cursor-pointer">
                Nombre
            </th>

            <th style="width: 15%" data-sort="categoria" data-order="asc" class="sort cursor-pointer">
                Dimension            </th>

            <th style="width: 20%" data-sort="metodo_calculo" data-order="asc" class="sort cursor-pointer">
                Método de Cálculo
            </th>

            <th style="width: 30%" data-sort="metodo_calculo" data-order="asc" class="sort cursor-pointer">
                Ejemplo
            </th>

    </thead>
    <tbody>
        ${indicadores_db.map((indicador, index) => {
        return `
            <tr>
                <td class="text-xs">${indicador.clave}</td>
                <td class="text-xs js-estados-${index}">
                <span class="badge bg-primary text-xs">En espera</span>
            </td>
                <td class="text-xs">${indicador.nombre}</td>
                <td class="text-xs">${indicador.dimension}</td>
                <td class="text-xs">${indicador.metodo_calculo}</td>
                <td class="text-xs">
                ${indicador.non_evaluable_formula}<br>
                <strong>=></strong><br>
                ${indicador.evaluate_result.replaced_formula}<br>
                ${indicador.evaluate_result.error ? indicador.evaluate_result.error : ''}
            </td>
            </tr>
            `;
    }
    ).join('')}
    </tbody>
</table>`
    state.indicador_batch_view.modal('show');


}
function make_indicadores_db(dimensiones) {
    let indicadores_res = [];
    for (let dimension in dimensiones) {
        const indicadores = dimensiones[dimension].indicadores.map(indicador => {
            // just fill the blanks
            indicador["evaluable_formula"] = "";
            indicador["non_evaluable_formula"] = "";
            indicador["variables"] = [];
            indicador["dimension"] = dimension;
            const pochi_result = state.repl.parse_formula(indicador["metodo_calculo"]);
            if (pochi_result.error) {
                indicador["error"] = pochi_result.error;
                return indicador;
            }
            const ilegals = pochi_result.tokens.filter(t => t.type === 'ILLEGAL');
            if (ilegals.length > 0) {
                indicador["error"] = `Error al evaluar la fórmula: ${ilegals.map(i => i.literal).join(', ')}`;
                return indicador;
            }
            indicador["evaluable_formula"] = pochi_result.evaluable_formula;
            indicador["non_evaluable_formula"] = pochi_result.non_evaluable_formula;
            indicador["variables"] = pochi_result.variables.map(v => {
                return {
                    code: v.code,
                    literal: v.literal
                }
            });

            const samples = []
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
            indicador["samples"] = samples;

            const evaluate_result = pochi_result.evaluate_with(samples);
            indicador["evaluate_result"] = evaluate_result;
            return indicador;
        });
        indicadores_res = indicadores_res.concat(indicadores);

    }
    return indicadores_res;
}
function group_dimensiones(data) {
    const dimensiones = [];
    for (let row of data) {
        if (row["Dimensión"] === undefined) {
            continue;
        }

        if (dimensiones[row["Dimensión"]] !== undefined) {
            dimensiones[row["Dimensión"]].indicadores.push({
                clave: row["Clave"],
                categoria: row["Categoría"],
                descripcion: row["Descripción General"],
                status: row["Estado"] === 'Aprobado' ? 1 : 0,
                fecuencia_medicion: row["Frecuencia de medición"],
                medio_verificacion: row["Medio de Verificación"],
                metodo_calculo: row["Método de Cálculo"],
                nombre: row["Nombre"],
                sentido: row["Sentido"].toLowerCase(),
                unidad_medida: row["Unidad de medida"],
                area: row["Área que genera información"],
                indicador_confirmado: 0,
                dimensionId: row["dimensionId"],
                requiere_anexo: 0,
            });
        }
        else {
            dimensiones[row["Dimensión"]] = {
                indicadores: [{
                    clave: row["Clave"],
                    categoria: row["Categoría"],
                    descripcion: row["Descripción General"],
                    status: row["Estado"] === 'Aprobado' ? 1 : 0,
                    fecuencia_medicion: row["Frecuencia de medición"],
                    medio_verificacion: row["Medio de Verificación"],
                    metodo_calculo: row["Método de Cálculo"],
                    nombre: row["Nombre"],
                    sentido: row["Sentido"].toLowerCase(),
                    unidad_medida: row["Unidad de medida"],
                    area: row["Área que genera información"],
                    indicador_confirmado: 0,
                    dimensionId: row["dimensionId"],
                    requiere_anexo: 0,
                }]
            };
        }
    }
    return dimensiones;
}
async function parse_json(file) {
    return new Promise((resolve, reject) => {
        try {
            papaparse.parse(file, {
                header: true,
                complete: function(results) {
                    const data = results.data.map((row) => {
                        // remove properties starting with _
                        for (let key in row) {
                            if (key.startsWith('_')) {
                                delete row[key];
                            }
                        }
                        return row;
                    });
                    resolve(data);
                },
                error: function(error) {
                    reject(error);
                }
            });
        }
        catch (error) {
            reject(error);
        }
    });
}
