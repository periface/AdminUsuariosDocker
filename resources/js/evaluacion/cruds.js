import { toggle_loading } from '../utils/helpers.js';
async function delete_evaluacion(id, state) {
    try {
        toggle_loading(true);
        const response = await fetch(state.API_URL + '/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken,
                'Authorization': 'Bearer ' + state.bearertoken,
            }
        });
        const json_response = await response.json();
        toggle_loading(false);
        return {
            error: null,
            data: json_response
        }
    }
    catch (error) {
        console.error(error);
        return {
            error: error,
            data: null
        }
    }
    finally {
        toggle_loading(false);
    }
}
async function get_rows(state) {
    try {
        toggle_loading(true);
        const response = await fetch(state.WEB_URL + '/get_table_rows', {
            method: 'POST',
            body: JSON.stringify(state.table_req),
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken,
                "Accept": "application/json, text/plain, */*",
                "X-Requested-With": "XMLHttpRequest",
                "Content-Type": "application/json"
            },
            credentials: 'same-origin'
        });
        const html_rows = await response.text();
        return {
            error: null,
            data: html_rows
        };
    }
    catch (error) {
        console.error(error);
        return null;
    }
    finally {
        toggle_loading(false);
    }
}
async function post_evaluacion(form_data, state) {
    try {
        toggle_loading(true);
        const response = await fetch(state.API_URL, {
            method: 'POST',
            body: form_data,
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken,
                'Authorization': 'Bearer ' + state.bearertoken,
            }
        });
        const json_response = await response.json();
        return {
            error: null,
            data: json_response
        };
    }
    catch (error) {
        console.error(error);
        return {
            error: error,
            data: null
        }
    }
    finally {
        toggle_loading(false);
    }
}
async function load_evaluacion_form(id, state) {
    try {
        toggle_loading(true);
        let url = state.WEB_URL + '/get_evaluacion_fields';
        if (id) {
            url += '&id=' + id;
        }
        const response = await fetch(url, {
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken
            }
        });
        const html_fields = await response.text();
        return {
            error: null,
            data: html_fields
        };
    }
    catch (error) {
        console.error(error);
        return {
            error: error,
            data: null
        }
    }
    finally {
        toggle_loading(false);
    }
}
async function load_evaluacion_config(areaId, indicadorId, state) {
    try {
        toggle_loading(true);
        if (!areaId || !indicadorId) {
            return "<h5>Seleccione un área e indicador</h5>";
        }
        let url = state.WEB_URL + '/get_evaluacion_details';
        url += '?areaId=' + areaId;
        url += '&indicadorId=' + indicadorId;
        const response = await fetch(url, {
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken
            }
        });
        const html_details = await response.text();
        return {
            error: null,
            data: html_details
        };
    }
    catch (error) {
        console.error(error);
        return {
            error: error,
            data: null
        }
    }
    finally {
        toggle_loading(false);
    }
}

async function get_stats(id, state) {
    try {
        toggle_loading(true);
        const response = await fetch("/api/v1/evaluacion" + '/' + id + '/stats', {
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken,
                "Accept": "application/json, text/plain, */*",
                "X-Requested-With": "XMLHttpRequest",
                "Content-Type": "application/json",
                'Authorization': 'Bearer ' + state.bearertoken
            },
            credentials: 'same-origin'
        });
        const json_response = await response.json();

        return {
            error: null,
            data: json_response
        };
    }
    catch (error) {
        console.error(error);
        return {
            error: error,
            data: null
        };
    }
    finally {
        toggle_loading(false);
    }
}
export {
    delete_evaluacion,
    load_evaluacion_config,
    get_rows,
    post_evaluacion,
    load_evaluacion_form,
    get_stats
};
