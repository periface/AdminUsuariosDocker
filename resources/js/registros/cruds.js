import { toggle_loading, try_parse_html } from '../utils/helpers.js';
async function load_registro_form(evaluacionId, fecha, state) {
    try {
        toggle_loading(true);
        const response = await fetch(state.WEB_URL + '/get_registros_form/' + evaluacionId + '/' + fecha,
            {
                headers: {
                    'X-CSRF-TOKEN': state.xcsrftoken
                }
            });
        const json_response = try_parse_html(await response.text());
        return json_response;
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

};
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
async function get_rows(state) {
    try {
        toggle_loading(true);
        const response = await fetch(state.WEB_URL + '/get_table_rows/' + state.evaluacionId, {
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
async function post_registros(form_data, state) {
    try {
        toggle_loading(true);
        const response = await fetch(state.API_URL, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken,
                'Authorization': 'Bearer ' + state.bearertoken
            },
            body: form_data
        });
        const json_response = await response.json();
        return json_response;
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

async function set_status(id, status, form_data, state) {
    try {
        toggle_loading(true);

        const response = await fetch(state.API_URL + '/' + id + '/' + status, {
            method: 'POST',
            body: form_data,
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken,
                'Authorization': 'Bearer ' + state.bearertoken
            },
        });
        const json_response = await response.json();
        return json_response;
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
    get_rows,
    load_registro_form,
    post_registros,
    set_status,
    get_stats
};
