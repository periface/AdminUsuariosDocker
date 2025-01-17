
const errores_periodicidad = {
    "diario": "El rango de fechas debe ser mayor a un día",
    "semanal": "El rango de fechas debe ser mayor a una semana",
    "mensual": "El rango de fechas debe ser mayor a un mes",
    "bimestral": "El rango de fechas debe ser mayor a dos meses",
    "trimestral": "El rango de fechas debe ser mayor a tres meses",
    "semestral": "El rango de fechas debe ser mayor a seis meses",
    "anual": "El rango de fechas debe ser mayor a un año"
}
function check_date_validity_range(fecha_inicio, fecha_fin, periodicidad) {

    if (fecha_inicio > fecha_fin) {
        return {
            is_valid: false,
            message: 'La fecha de inicio debe ser menor a la fecha de fin'
        }
    }
    if (fecha_inicio === fecha_fin) {
        return {
            is_valid: false,
            message: 'La fecha de inicio no puede ser igual a la fecha de fin'
        }
    }

    const diff = days_of_diference(fecha_inicio, fecha_fin);
    const diff_bigger_than_periodicidad = diff > periodicidades[periodicidad];
    return diff === NaN ?
        {
            is_valid: false,
            message: 'El rango de fechas no es válido'
        } : {
            is_valid: diff_bigger_than_periodicidad,
            message: errores_periodicidad[periodicidad]
        }
}

function days_of_diference(fecha1, fecha2) {
    const milisegundosPorDia = 1000 * 60 * 60 * 24;
    const diferenciaMilisegundos = Math.abs(fecha1 - fecha2);
    return Math.floor(diferenciaMilisegundos / milisegundosPorDia);
}

const periodicidades = {
    "diario": 1,
    "semanal": 7,
    "mensual": 30,
    "bimestral": 60,
    "trimestral": 90,
    "semestral": 180,
    "anual": 365
};
const calcula_fechas_captura = (fecha_inicio, fecha_fin, periodicidad) => {
    const dias_periodo = periodicidades[periodicidad];
    const fechas = [];
    for (let i = fecha_inicio; i <= fecha_fin; i.setDate(i.getDate() + dias_periodo)) {
        const date = new Date(i);
        fechas.push(
            {
                periodicidad: periodicidad,
                fecha_captura: date,
            }
        );
    }
    return fechas;
}
export { check_date_validity_range, days_of_diference, calcula_fechas_captura };
