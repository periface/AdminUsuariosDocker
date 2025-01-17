import { load_registro_form, post_registros, set_status } from './cruds.js';
import { show_confirm_action } from '../utils/helpers.js';
import { Repl } from 'pochijs';
const state = {
    API_URL: "/api/v1/registro",
    WEB_URL: "/registro",
    registrar_btn: document.getElementsByClassName('js-registrar'),
    xcsrftoken: document.querySelector('meta[name="csrf-token"]').content || '',
    events_set: false,
    modal: document.getElementById('registroModal'),
    fields_container: document.getElementById('registroFields'),
    registro_form: document.getElementById("registroForm"),
    repl: new Repl(),
    bearertoken: localStorage.getItem('token') || '',
    fecha: '',
    validar_btns: document.getElementsByClassName('js-validar'),
    rechazar_btns: document.getElementsByClassName('js-rechazar')
}
const init_view = async () => {
    await set_modal_event_listener();
}

async function set_modal_event_listener() {
    if (state.events_set) return;
    for await (const validar_btn of state.validar_btns) {
        validar_btn.addEventListener('click', async (e) => {
            const response = await show_confirm_action(
                'Validar Evaluación',
                'Estas seguro de validar esta evaluación?',
                'info'
            );
            if (!response) {
                return;
            }
            const espacio = e.target.dataset.espacio;
            if (!espacio) {
                return;
            }
            const espacio_obj = JSON.parse(espacio);
            espacio_obj.status = 'validado';
            console.log(espacio_obj);
            await set_status(espacio_obj.id, 'aprobado', state);
        });

    }
    for await (const rechazar_btn of state.rechazar_btns) {
        rechazar_btn.addEventListener('click', async (e) => {
            const id = e.target.dataset.id;
            console.log(id);
            const response = await show_confirm_action(
                'Rechazar Evaluación',
                'Estas seguro de rechazar esta evaluación?',
                'warning'
            );
            if (!response) {
                return;
            }
            const espacio = e.target.dataset.espacio;
            if (!espacio) {
                return;
            }
            const espacio_obj = JSON.parse(espacio);
            espacio_obj.status = 'rechazado';
            console.log(espacio_obj);
            await set_status(espacio_obj.id, 'rechazado', state);
        });
    }
    for await (const btn of state.registrar_btn) {
        btn.addEventListener('click', async (e) => {
            const evaluacionId = e.target.dataset.id;
            const fecha = e.target.dataset.fecha
            if (!evaluacionId || !fecha) {
                console.error('evaluacionId o fecha no definidos');
                return;
            }
            state.fecha = fecha;
            const response = await load_registro_form(
                evaluacionId,
                fecha,
                state
            );
            console.log(response);
            if (response.error) {
                console.error(response.error);
                return;
            }
            state.fields_container.innerHTML = response.data;
            $(state.modal).modal('show');
            await set_after_modal_load_evts();
        });
    }
}
async function store_values(form_data) {
    for (let key of form_data.keys()) {
        console.log(key, form_data.get(key));
    }

    const response = await post_registros(form_data, state);
    console.log(response);

}
async function set_after_modal_load_evts() {
    state.registro_form.onsubmit = async function(e) {
        e.preventDefault();
        const fake_form_data = new FormData(state.registro_form)
        const real_form_data = new FormData();
        const registros = [];
        let registro = {}
        for (let key of fake_form_data.keys()) {
            let real_key = key.match(/^(.*)_(\d+)$/)
            if (!real_key || !real_key.length) {
                continue;
            }
            const name = real_key[1];
            switch (name) {
                case "registroId":
                    registro["id"] = fake_form_data.get(key)
                    break;
                case "variableId":
                    registro["variableId"] = fake_form_data.get(key);
                    break;
                case "code":
                    registro["code"] = fake_form_data.get(key);
                    break;
                case "registro":
                    registro["registro"] = fake_form_data.get(key);
                    registro["value"] = fake_form_data.get(key);
                    registros.push(registro);
                    registro = {}
                    break;
            }
        }
        real_form_data.append('registros', JSON.stringify(registros));
        real_form_data.append('evaluacionId', fake_form_data.get('evaluacionId'));
        real_form_data.append('evaluable_formula', fake_form_data.get('evaluable_formula'));
        real_form_data.append('fecha', state.fecha);
        const evaluable_formula = fake_form_data.get('evaluable_formula')
        const result = state.repl.run_with_variables(evaluable_formula, registros);
        console.log(result);
        if (result.error) {
            console.error(result.error);
            return;
        }
        real_form_data.append('result', result.data === NaN ? 0 : result.data);
        real_form_data.append('used_formula', result.replaced_formula);
        await store_values(real_form_data);
    }
}

init_view().then(() => {
})
    .catch((e) => {
        console.error(e);
    });
