// Agregamos reglas de validación para el form del modal
$('#areaForm').validate({
    rules: {
        nombre: { required: true, minlength: 4 },
        responsable: { required: true }
    },
    messages: {
        nombre: {
            required: 'El nombre es requerido',
            minlength: 'Debe tener al menos 4 caracteres'
        },
        responsable: {
            required: 'El responsable es requerido',
            minlength: 'Debe tener al menos 4 caracteres'
        }
    }
});

// Creamos notificaciones

export const createToast = (title, message, isSuccess) => {
    const toastId = `toast-${Date.now()}`;
    const toastHTML = `
        <div  id="${toastId}"  class="toast align-items-center text-white ${isSuccess ? 'bg-success' : 'bg-danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">${title}</strong>
                <small></small>
                <button type="button" class="btn-close text-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
            ${message}
            </div>
        </div>
    `;

    $('.toast-container').append(toastHTML);

    const toastElement = new bootstrap.Toast(document.getElementById(toastId), {
        autohide: true,
        delay: 3000
    });

    toastElement.show();

    $(`#${toastId}`).on('hidde.vs.toast', function() {
        $(this).remove();
    });
};
export const debounce = (func, wait, immediate) => {
    let timeout;
    return function() {
        const context = this,
            args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

export const show_confirm_action = async (
    title,
    text,
    icon,
    confirmButtonText = 'Confirmar',
    config,
) => {

    if (config && config["title"]) {
        title = config["title"];
        icon = config["icon"];
        text = config["text"];
        confirmButtonText = config["confirmButtonText"];
    }
    else {
        title = title || 'Esta seguro(a)?';
        icon = icon || 'warning';
        text = text || 'Esta acción no se puede deshacer';
    }


    const response = await Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: "#ab0033",
        cancelButtonColor: "#54565a",
        confirmButtonText: confirmButtonText,
        cancelButtonText: "Cancelar"
    });
    return response.isConfirmed;
}
/**
 * Me ayuda a lanzar excepciones si una condición no se cumple
 * es util para validar argumentos de funciones que si o si deben cumplir una condición
 * por ejemplo que un argumento sea null o undefined cuando no debería serlo
 * uso: assert(id, 'El id es requerido'); // lanza una excepción si id es null o undefined
 * util para detectar posibles bugs en el código; en funciones con argumentos requeridos
 * @param {any} title
 * @param {string} message
 */
export const assert = (condition, message) => {
    if (!condition) {
        throw new Error(message);
    }
}
export const toggle_loading = (state = false, selector = 'loader') => {
    const loader = document.getElementById(selector);
    if (!loader) {
        return;
    }
    loader.hidden = state ? false : true;
}
export function try_parse_html(response_text) {
    try {
        return JSON.parse(response_text);
    }
    catch (error) {
        // check if error is unexpected token
        if (error instanceof SyntaxError) {
            return {
                data: response_text,
                error: null
            };
        }
        return {
            data: null,
            error: error
        };
    }

}

export function restart_popovers() {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
    // tooltips restarteda
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
}
