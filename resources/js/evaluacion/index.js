import { delete_evaluacion, get_rows, cerrar_evaluacion, load_evaluacion_form, post_evaluacion, load_evaluacion_config } from './cruds.js';
import { debounce, createToast, show_confirm_action, toggle_loading } from '../utils/helpers.js';
import { check_date_validity_range, calcula_fechas_captura } from './helpers.js';

import Stepper from 'bs-stepper'
import { UNIDADES } from '../UNIDADES.js';
const state = {
    API_URL: "/api/v1/evaluacion",
    WEB_URL: "/evaluacion",
    is_table_loading: false,
    step_1_valid: false,
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
    indicador: null,
    area: null,
    meta: 0,
    columns_events_set: false,
    rows_events_set: false,
    footer_events_set: false,
    confirm_indicador_events_set: false,
    step_1_events_set: false,
    step_2_events_set: false,
    step_3_events_set: false,
    is_date_valid: false,
    fechas_captura: [],
    stepper: null,
    current_step: 1,
    last_date_error: 'Fechas no válidas',
    xcsrftoken: document.querySelector('meta[name="csrf-token"]').content || '',
    bearertoken: localStorage.getItem('token') || '',
    evaluacion_view: $("#evaluacionModal"),
    evaluacion_table_container: document.getElementById('table-container'),
    evaluacion_form: document.getElementById('evaluacionForm'),
    evaluacion_field_container: document.getElementById('evaluacionFields'),
    modal_open_buttons: document.getElementsByClassName('evaluacionModalBtn'),
    rows_per_page_input: document.getElementsByClassName('js-change-rows'),
    change_page_input: document.getElementsByClassName('js-change-page'),
    set_formula_buttons: document.getElementsByClassName('js-set-formula'),
    delete_evaluacion_button: document.getElementsByClassName('js-delete-evaluacion'),
    search_input: document.getElementsByClassName('js-search'),
    sort: document.getElementsByClassName('sort'),
    guardar_evaluacion_btn: document.getElementById('js-guardar-evaluacion'),
    step_btn: document.getElementById('js-step'),
    step_back_btn: document.getElementById('js-step-back'),
    btn_submit: document.getElementById('js-submit'),
    btn_cerrar_evaluacion: document.getElementsByClassName('js-cerrar-evaluacion'),
    view_registros_btn: document.getElementsByClassName('js-view-registros'),
    restart_popovers: () => {
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
        console.log('Popovers restarted', popoverList);
        // tooltips restarteda
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        console.log('Tooltips restarted', tooltipList);

    },
    validate_form: () => {
        $("#evaluacionForm").validate({
            rules: {
                fecha_inicio: {
                    required: true,
                    date: true
                },
                fecha_fin: {
                    required: true,
                    date: true
                },
                periodicidad: {
                    required: true
                },
                areaId: {
                    required: true
                },
                indicadorId: {
                    required: true
                },
                meta: {
                    required: true
                },
            },
            messages: {
                fecha_inicio: {
                    required: "Por favor ingrese la fecha de inicio",
                    date: "Por favor ingrese una fecha válida"
                },
                fecha_fin: {
                    required: "Por favor ingrese la fecha de fin",
                    date: "Por favor ingrese una fecha válida"
                },
                periodicidad: {
                    required: "Por favor seleccione la periodicidad"
                },
                areaId: {
                    required: "Por favor seleccione el área"
                },
                indicadorId: {
                    required: "Por favor seleccione el indicador"
                },
                meta: {
                    required: "Por favor ingrese la meta"
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
        if (error) {
            throw new Error(error);
        }
        state.is_table_loading = false;
        state.evaluacion_table_container.innerHTML = data;
        set_table_header_events();
        await set_modal_trigger_evts();
        set_table_footer_events();
        state.restart_popovers();
    } catch (error) {
        state.is_table_loading = false;
        state.evaluacion_table_container.innerHTML = `
        <h1 class="text-center">
            <p colspan="5"
             class="text-center">Ocurrió un error inesperado
             <br> ${error}
            </p>
        </h1>`;
        console.error(error);
    }
}
start_view().then(() => {
    state.evaluacion_view.on('hidden.bs.modal', function() {
        state.repl_result = null;
        state.current_step = 1;
        move_to_step(1);
        state.step_2_events_set = false;
        state.step_3_events_set = false;
    });
}).catch((error) => {
    console.log(error);
})


async function render_evaluacion_form(id) {
    const { data, error } = await load_evaluacion_form(id, state);
    if (error) {
        createToast('Administración de Evaluaciones',
            `Ocurrió un error al cargar la información.`,
            false);
        state.evaluacion_field_container.innerHTML = 'Error al cargar la información';
        return
    }
    state.evaluacion_field_container.innerHTML = data;
}
async function after_render_evaluacion_form() {
    let area_json = undefined;
    let indicador_json = undefined;
    const areaId = document.getElementById('areaId');
    const indicadorId = document.getElementById('indicadorId');
    init_stepper();
    areaId.addEventListener('change', async (e) => {
        e.preventDefault();
        const data = e.target.options[e.target.selectedIndex].dataset;
        area_json = data && data.json ? JSON.parse(data.json) : null;
        if (!area_json || !indicador_json) {
            console.error('No area_json provided');
            return;
        }
        await render_evaluacion_details(area_json, indicador_json);
    });
    indicadorId.addEventListener('change', async (e) => {
        e.preventDefault();
        const data = e.target.options[e.target.selectedIndex].dataset;
        indicador_json = data && data.json ? JSON.parse(data.json) : null;
        if (!indicador_json || !area_json) {
            console.error('No indicador_json provided');
            return;
        }
        await render_evaluacion_details(area_json, indicador_json);
    });
}
async function render_evaluacion_details(area, indicador) {
    const evaluacion_config = document.getElementById('evaluacion_details');
    const asignacion_details = document.getElementById('asignacion-details');
    const areaId = area?.id;
    const indicadorId = indicador?.id;

    state.step_1_valid = areaId && indicadorId;
    state.indicador = indicador;
    state.area = area;
    state.step_btn.disabled = false;
    const details_html = create_asignacion_details(area, indicador);
    asignacion_details.innerHTML = details_html;


    // eager load step 2
    evaluacion_config.innerHTML = 'Cargando...';
    const { data, error } = await load_evaluacion_config(areaId, indicadorId, state);
    if (error) {
        console.error(error);
        createToast('Administración de Evaluaciones',
            `Ocurrió un error al cargar la información.`,
            false);
        evaluacion_config.innerHTML = 'Error al cargar la información';
        return
    }
    evaluacion_config.innerHTML = data;
    bind_step2_events();
}

function init_stepper() {
    state.stepper = new Stepper(document.querySelector('.bs-stepper'));
    move_to_step(state.current_step);
}
function move_to_step(step) {
    state.stepper.to(step);
    state.current_step = step;
    state.step_btn.disabled = true;
    if (step === 1) {
        // run validation for step 1
        state.btn_submit.hidden = true;
        state.step_btn.hidden = false;
        if (state.step_1_valid) {
            state.step_btn.disabled = false;
        }
        state.step_btn.innerHTML = "Siguiente";
    }
    if (step === 2) {
        // run validation for step 2
        state.btn_submit.hidden = true;
        state.step_btn.hidden = false;
        if (state.fechas_captura.length > 0) {
            state.step_btn.disabled = false;
        }

        state.step_btn.innerHTML = "Siguiente";
    }
    if (step === 3) {
        // last step
        state.step_btn.hidden = true;
        state.btn_submit.hidden = false;
        state.btn_submit.disabled = false;
    }
}
function create_asignacion_details(area, indicador) {
    const responsableName = area["responsable"] ? area["responsable"]["name"] + " " + area["responsable"]["primer_apellido"] + " " + area["responsable"]["segundo_apellido"] : 'Sin responsable';
    return `
<div class="grid md:grid-cols-1 grid-cols-1 justify-center items-center content-evenly justify-items-center"
    style="grid-auto-columns: minmax(0, 1fr);">
    <div class="p-2">
        <div class="card shadow">
            <p class="m-0 text-pink-900">${area["nombre"]}</p>
            <p class="m-0 text-gray-900 text-sm">Responsable: ${responsableName}</p>
            </p>
        </div>
    </div>
    <div class="p-2">
        <div class="">

            <p class="m-0 text-pink-950 font-bold" >${indicador["nombre"]}:</p>
            <p class="m-0 text-yellow-950">${indicador["descripcion"]}</p>
            <hr/>
            <p class="m-0 text-red-950 text-sm uppercase"><span class="font-bold">Método de cálculo:</span> ${indicador["metodo_calculo"]}</p>
            <p class="m-0 text-red-950 text-sm uppercase"><span class="font-bold">Sentido:</span> ${indicador["sentido"]}</p>
            <p class="m-0 text-red-950 text-sm uppercase"><span class="font-bold">Unidad de medida:</span> ${indicador["unidad_medida"]}</p>
        </div>
    </div>
</div>
`
}
const periodicidades_text = {
    "diario": "Día",
    "semanal": "Semana",
    "mensual": "Mes",
    "bimestral": "Bimestre",
    "trimestral": "Trimestre",
    "semestral": "Semestre",
    "anual": "Año"
}

const number_txt = (number) => {
    let txt = number.toString();
    if (number === 1) {
        return txt + 'er';
    }
    if (number === 2) {
        return txt + 'do';
    }
    if (number === 3) {
        return txt + 'er';
    }
    if (number >= 4 && number <= 20) {
        return txt + 'to';
    }
    if (number > 20) {
        return txt + 'vo';
    }
}

function render_capturas_table() {
    const meta_esperada = state.meta / state.fechas_captura.length;
    return `
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Fecha de Captura</th>

                <th>Periodo</th>
            </tr>
        </thead>
        <tbody>
            ${state.fechas_captura.map((fecha, index) => {
        const fecha_for_input = fecha.fecha_captura.toISOString().split('T')[0];
        const real_index = index + 1;
        return `<tr>
<td>
<input type="hidden" value="${meta_esperada}" class="form-control js-meta">
<input type="date" value="${fecha_for_input}" class="form-control js-date"></td>
                    <td>${number_txt(real_index)} ${periodicidades_text[fecha.periodicidad]} </td>
                </tr>`
    }).join('')
        }
        <tr>
        <td>
       Periodos: <span id="periodos_total">${state.fechas_captura.length}</span>
</td>
        </tbody>
    </table>
    `
}

function after_render_capturas_table() {
    const metas_esperadas = document.getElementsByClassName('js-meta');
    const meta_total = document.getElementById('meta_total');
    state.step_btn.disabled = false;
    for (let meta of metas_esperadas) {
        meta.addEventListener('change', (_) => {
            const meta_total_float = parseFloat(meta_total.innerHTML);
            let total = 0;
            for (let meta of metas_esperadas) {
                total += parseFloat(meta.value);
            }
            if (total > meta_total_float) {
                console.log('Total mayor que meta');
                meta_total.classList.add('text-danger');
            }
            if (total < meta_total_float) {
                console.log('Total menor que meta');
                meta_total.classList.add('text-danger');
            }
            total === meta_total_float ? meta_total.classList.remove('text-danger') : null;
            meta_total.innerHTML = total;
        });
    }
}
function add_periodos(form_data) {
    const fechas = document.getElementsByClassName('js-date');
    const fechas_captura_values = []
    let index = 0;
    for (let fecha of fechas) {
        const fecha_value = fecha.value;
        const meta = document.getElementsByClassName('js-meta')[index]?.value || 0;
        fechas_captura_values.push({
            fecha_captura: fecha_value,
            meta: meta
        });
        index++;
    }
    form_data.append('fechas_captura', JSON.stringify(fechas_captura_values));
    return form_data;
}

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
async function open_evaluacion_form_evt(modal_open_btn) {
    if (state.step_1_events_set) {
        return
    }
    modal_open_btn.addEventListener('click', async function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        await render_evaluacion_form(id);
        await after_render_evaluacion_form();
        state.restart_popovers();
        state.evaluacion_view.modal('show');
        state.validate_form();
        state.evaluacion_form.onsubmit = async (e) => {
            e.preventDefault();
            if (!state.is_date_valid) {
                e.preventDefault();
                return;
            }
            let form_data = new FormData(state.evaluacion_form);

            form_data.append('finalizado', false);
            form_data.append('finalizado_por', null);
            form_data.append('finalizado_en', null);
            const unidad = UNIDADES.find((unidad) => unidad.nombre === state.indicador["unidad_medida"]);
            if (!unidad) {
                createToast('Administración de Evaluaciones',
                    `Ocurrió un error al guardar la información.`,
                    false);
                return;
            }
            const descripcion = `${unidad.simbolo} ${state.indicador["nombre"]}`;
            form_data.append('formula_literal', state.indicador["metodo_calculo"]);
            form_data.append('evaluable_formula', state.indicador["evaluable_formula"]);
            form_data.append('non_evaluable_formula', state.indicador["non_evaluable_formula"]);
            form_data.append('descripcion', descripcion);
            form_data = await add_periodos(form_data);
            const { data, error } = await post_evaluacion(form_data, state);
            if (error) {
                console.error(error);
                createToast('Administración de Evaluaciones',
                    `Ocurrió un error al guardar la información.`,
                    false);
            }
            const response_json = data;
            if (!response_json.error) {
                state.evaluacion_view.modal('hide');
                await start_datatable();
                createToast('Administración de Evaluaciones',
                    `Se guardó correctamente la información.
                    <a href="${state.WEB_URL}/${response_json.data}"
                    class="btn btn-success btn-sm">Ver</a>`,
                    true);
                // reinitialize repl_result
            } else {
                if (response_json.statusCode === 422) {
                    return;
                }
                createToast('Administración de Evaluaciones',
                    `Ocurrió un error al guardar la información.
                ${response_json.error}
`,
                    false);
            }
        }

        state.rows_events_set = true;
    });
}
/**
 * Binds header events once, like sorting
 */
function bind_step2_events() {
    if (state.step_2_events_set) {
        return;
    }
    const fecha_inicio = document.getElementById('fecha_inicio');
    const fecha_fin = document.getElementById('fecha_fin');
    const periodicidad = document.getElementById('periodicidad');
    const meta = document.getElementById('meta');

    // set fechas inicio to today and fin to today + 2 month
    const today = new Date();
    const today_plus_2 = new Date();
    today_plus_2.setMonth(today_plus_2.getMonth() + 2);
    fecha_inicio.value = today.toISOString().split('T')[0];
    fecha_fin.value = today_plus_2.toISOString().split('T')[0];


    fecha_inicio.addEventListener('change', (e) => {
        const fecha_inicio_value = e.target.value;
        const fecha_fin_value = fecha_fin.value;
        const periodicidad_value = periodicidad.value;
        state.meta = parseFloat(meta.value);
        handle_evaluacion_params(fecha_inicio_value,
            fecha_fin_value,
            periodicidad_value);
    });
    fecha_fin.addEventListener('change', (e) => {

        const fecha_inicio_value = fecha_inicio.value;
        const fecha_fin_value = e.target.value;
        const periodicidad_value = periodicidad.value;
        state.meta = parseFloat(meta.value);
        handle_evaluacion_params(fecha_inicio_value,
            fecha_fin_value,
            periodicidad_value);
    });
    periodicidad.addEventListener('change', (e) => {
        const fecha_inicio_value = fecha_inicio.value;
        const fecha_fin_value = fecha_fin.value;
        const periodicidad_value = e.target.value;
        state.meta = parseFloat(meta.value);
        handle_evaluacion_params(fecha_inicio_value,
            fecha_fin_value,
            periodicidad_value);

    })

    meta.addEventListener('change', (_) => {
        const fecha_inicio_value = fecha_inicio.value;
        const fecha_fin_value = fecha_fin.value;
        const periodicidad_value = periodicidad.value;
        state.meta = parseFloat(meta.value);
        const totalElm = document.getElementById('total');
        if (totalElm) {
            totalElm.innerHTML = state.meta + '%';
        }
        handle_evaluacion_params(fecha_inicio_value,
            fecha_fin_value,
            periodicidad_value);
    });


    state.step_2_events_set = true;
}
function set_table_header_events() {
    if (state.columns_events_set) {
        return;
    }
    // SORT EVENTS
    const elements = state.sort
    for (let element of elements) {
        element.addEventListener('click', (e) => {
            e.preventDefault();
            const is_equal = element.dataset.sort === state.table_req.sort;
            if (is_equal) {
                state.table_req.order = state.table_req.order === 'asc'
                    ? 'desc' : 'asc';
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
function delete_evaluacion_evt(delete_button) {
    delete_button.addEventListener('click', async function(e) {
        e.preventDefault();
        const id = delete_button.dataset.id;
        const delete_evaluacion_confirmed = await show_confirm_action();
        if (delete_evaluacion_confirmed) {
            const response_json = await delete_evaluacion(id, state);
            if (!response_json.error) {
                createToast('Administración de Evaluaciones',
                    `Se eliminó correctamente la información.`,
                    true);
                await start_datatable();
            } else {
                console.log(response_json);
                createToast('Administración de Evaluaciones',
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

    for (let view_registros_btn of state.view_registros_btn) {
        view_registros_btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            window.location.href = `${state.WEB_URL}/${id}/registros`;
        });
    }
    for (let modal_open_btn of state.modal_open_buttons) {
        await open_evaluacion_form_evt(modal_open_btn);
    }
    for (let delete_button of state.delete_evaluacion_button) {
        delete_evaluacion_evt(delete_button);
    }
    for (let cerrar_btn of state.btn_cerrar_evaluacion) {
        cerrar_btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            const cerrar_evaluacion_confirmed = await show_confirm_action();
            if (cerrar_evaluacion_confirmed) {
                const response_json = await cerrar_evaluacion(id, state);
                if (!response_json.error) {
                    createToast('Administración de Evaluaciones',
                        response_json.data.finalizado ?
                        `Se cerró correctamente la evaluación.` :
                        `Se abrió correctamente la evaluación.`,
                        true);
                    await start_datatable();
                } else {
                    createToast('Administración de Evaluaciones',
                        `Ocurrió un error al cerrar la evaluación.`,
                        false);
                }
            }
        });
    }
    state.rows_events_set = true;
}

function handle_evaluacion_params(fecha_inicio, fecha_fin, periodicidad) {
    if (!fecha_inicio || !fecha_fin || !periodicidad) {
        console.error('No fecha_inicio, fecha_fin or periodicidad provided');
        return;
    }
    fecha_inicio = fecha_inicio.split('-');
    fecha_inicio = new Date(fecha_inicio[0], fecha_inicio[1] - 1, fecha_inicio[2]);
    fecha_fin = fecha_fin.split('-');
    fecha_fin = new Date(fecha_fin[0], fecha_fin[1] - 1, fecha_fin[2]);
    const date_range_check = check_date_validity_range(fecha_inicio, fecha_fin, periodicidad);
    if (!date_range_check.is_valid) {
        // mensaje de errrosh
        $("#evaluacionForm").validate().showErrors({
            "fecha_inicio": date_range_check.message,
        });
        $("#evaluacionForm").validate().showErrors({
            "fecha_fin": date_range_check.message,
        });
        state.last_date_error = date_range_check.message; // para el form submit
        state.is_date_valid = false; // para el form submit
        return;
    }
    else {
        state.is_date_valid = true;
        $("#evaluacionForm").validate()
        $("#evaluacionForm").validate()
    }
    state.fechas_captura = calcula_fechas_captura(fecha_inicio, fecha_fin, periodicidad);
    if (state.fechas_captura.length > 0) {
        state.step_btn.disabled = false;
        const capturas_html = render_capturas_table();
        document.getElementById('capturas-table').innerHTML = capturas_html;
        after_render_capturas_table();
    }
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



// start view
async function start_view() {
    await start_datatable();
    state.step_btn.addEventListener('click', (e) => {
        e.preventDefault();
        move_to_step(state.current_step + 1);
    });
    state.step_back_btn.addEventListener('click', (e) => {
        e.preventDefault();
        move_to_step(state.current_step - 1);
    });
}
