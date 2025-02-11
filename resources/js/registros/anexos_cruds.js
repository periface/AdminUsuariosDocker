
import { toggle_loading, try_parse_html } from '../utils/helpers.js';
async function delete_anexo(id, state) {
    const url = "/anexos/" + id;
    try {
        toggle_loading(true);
        const response = await fetch(url, {
            method: 'DELETE',
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
async function get_anexos_rows(id, state) {
    const url = "/anexos/get_rows/"
    try {
        toggle_loading(true);
        const response = await fetch(url + id,
            {
                headers: {
                    'X-CSRF-TOKEN': state.xcsrftoken,
                    'Authorization': 'Bearer ' + state.bearertoken
                }
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
async function get_anexos(id, state) {
    const url = "/anexos/"
    try {
        toggle_loading(true);
        const response = await fetch(url + id,
            {
                headers: {
                    'X-CSRF-TOKEN': state.xcsrftoken,
                    'Authorization': 'Bearer ' + state.bearertoken
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
}

async function upload_file(file_form_data, id, state) {
    const url = "/anexos/upload/" + id
    try {
        toggle_loading(true);
        if (!file_form_data) {
            return {
                error: "No se ha seleccionado un archivo",
                data: null
            }
        }
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken,
                'Authorization': 'Bearer ' + state.bearertoken,
            },
            body: file_form_data,
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
export { get_anexos, upload_file, get_anexos_rows, delete_anexo };
