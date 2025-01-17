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

async function set_status(id, status, state) {
    try {
        toggle_loading(true);

        const response = await fetch(state.API_URL + '/' + id + '/' + status, {
            method: 'GET',
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
export { load_registro_form, post_registros, set_status };
