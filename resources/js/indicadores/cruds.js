import { toggle_loading, try_parse_html } from '../utils/helpers.js';
async function delete_indicador(id, state) {
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
async function get_dimension_by_name(name, state) {
    try {
        toggle_loading(true);
        const response = await fetch("/api/v1/dimension/get_by_name/" + name, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken,
                "Accept": "application/json, text/plain, */*",
                "X-Requested-With": "XMLHttpRequest",
                "Content-Type": "application/json",
                "Authorization": 'Bearer ' + state.bearertoken,
            },
            credentials: 'same-origin'
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
async function get_rows(state) {

    try {
        toggle_loading(true);
        const response = await fetch(state.WEB_URL + '/get_table_rows/' + state.dimensionId, {
            method: 'POST',
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
async function post_indicador(form_data, state) {
    try {
        toggle_loading(true, 'loaderbd');
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
        };
    }
    finally {
        toggle_loading(false, 'loaderbd');
    }
}
async function load_indicador_form(id, set_formula, state) {
    try {
        toggle_loading(true, 'loaderbd');
        let url = state.WEB_URL + '/get_indicador_fields?dimensionId=' + state.dimensionId;
        if (id) {
            url += '&id=' + id;
        }
        if (set_formula) {
            url += '&set_formula=true';
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
export { delete_indicador, get_rows, post_indicador, load_indicador_form, get_dimension_by_name };
