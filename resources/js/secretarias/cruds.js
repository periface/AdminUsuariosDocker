import { toggle_loading, try_parse_html } from '../utils/helpers.js';
async function delete_secretaria(id, state) {
    try {
        toggle_loading(true, 'loaderbd');
        const response = await fetch(state.API_URL + '/' + id, {
            method: 'DELETE',
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
        };
    }
    finally {
        toggle_loading(false, 'loaderbd');
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
                "Content-Type": "application/json",
                "Authorization": 'Bearer ' + state.bearertoken,
            },

            credentials: 'same-origin'
        });
        const html_rows = try_parse_html(await response.text());
        return html_rows; // already has data and error
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
async function post_secretaria(form_data, state) {
    try {
        toggle_loading(true, 'loaderbd');
        const id = form_data.get('id');
        const headers = {
            'X-CSRF-TOKEN': state.xcsrftoken,
            'Authorization': 'Bearer ' + state.bearertoken,
        }
        if (id) {
            headers['X-HTTP-Method-Override'] = 'PUT';
        }
        const response = await fetch(state.API_URL, {
            method: 'POST',
            body: form_data,
            headers
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
        toggle_loading(false, 'loaderbd');
    }
}
async function load_secretaria_form(id, state) {
    try {
        toggle_loading(true, 'loaderbd');
        let url = state.WEB_URL + '/get_secretaria_fields';
        if (id) {
            url += '?id=' + id;
        }
        const response = await fetch(url, {
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken
            }
        });
        const json_response = try_parse_html(await response.text());
        return json_response;
    }
    catch (error) {
        console.log('trigger' + error);
        return {
            error: error,
            data: null
        };
    }
    finally {
        toggle_loading(false, 'loaderbd');
    }
}
export { delete_secretaria, get_rows, post_secretaria, load_secretaria_form };
