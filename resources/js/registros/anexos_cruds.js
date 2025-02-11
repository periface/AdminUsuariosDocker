
import { toggle_loading, try_parse_html } from '../utils/helpers.js';
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
async function upload_file(file, id, state) {
    const url = "/anexos/upload/" + id
    try {
        toggle_loading(true);
        if(!file instanceof File){
            throw new Error("El archivo no es valido");
        }
        const form_data = new FormData();
        form_data.append('file', file);
        console.log([...form_data.entries()]);
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': state.xcsrftoken,
                'Authorization': 'Bearer ' + state.bearertoken,
            },
            body: form_data,
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
export { get_anexos, upload_file };
