
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
    const diffMonts = months_of_difference(fecha_inicio, fecha_fin);
    const diff_bigger_than_periodicidad = diff > periodicidades[periodicidad];
    const diff_month_bigger_than_periodicidad = diffMonts >= periodicidades_mes[periodicidad];
    console.log("periodicidad", periodicidad);
    console.log("diffMonts", diffMonts);
    console.log("meses que debe tener", periodicidades_mes[periodicidad])
    console.log("result", diff_month_bigger_than_periodicidad);
    const residuo = periodicidades_mes[periodicidad] % diffMonts;
    console.log("residuo", residuo);
    return diffMonts === NaN ?
        {
            is_valid: false,
            message: 'El rango de fechas no es válido',
            addThisMore: residuo
        } : {
            is_valid: diff_month_bigger_than_periodicidad,
            message: errores_periodicidad[periodicidad],
            addThisMore: residuo
        }
}

function days_of_diference(fecha1, fecha2) {
    const milisegundosPorDia = 1000 * 60 * 60 * 24;
    const diferenciaMilisegundos = Math.abs(fecha1 - fecha2);
    return Math.floor(diferenciaMilisegundos / milisegundosPorDia);
}
function months_of_difference(fecha1, fecha2) {
    const date1 = new Date(fecha1);
    const date2 = new Date(fecha2);
    const diffMonts = date2.getMonth() - date1.getMonth();
    return diffMonts;
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
const redondeo_mes_reglas = {
    "mensual": {
        min: 29,
        max: 30
    },
    "bimestral": {
        min: 59,
        max: 60
    },
    "trimestral": {
        min: 89,
        max: 90
    },
    "semestral": {
        min: 179,
        max: 180
    },
    "anual": {
        min: 364,
        max: 365
    }
}
const periodicidades_mes = {
    "mensual": 1,
    "bimestral": 2,
    "trimestral": 3,
    "semestral": 6,
    "anual": 12
}
const calcula_fechas_captura = (fecha_inicio, fecha_fin, periodicidad) => {
    const dias_periodo = periodicidades[periodicidad];
    const fechas = [];
    let index = 0;
    const fechaInicio = new Date(fecha_inicio);
    // agregamos fecha de primer captura
    fechas.push({
        periodicidad,
        fecha_captura: fechaInicio
    })
    for (let i = fecha_inicio; i <= fecha_fin; i.setDate(i.getDate() + dias_periodo)) {
        if (index === 0) {
            // la primer captura ya se creo
            index++;
            continue;
        }
        // agregamos fecha intermedias de haber
        const date = new Date(i);
        fechas.push(
            {
                periodicidad: periodicidad,
                fecha_captura: date,
            }
        );
        index++;
    }
    // agregamos ultima fecha de captura
    fechas.push({
        periodicidad,
        fecha_captura: fecha_fin
    })
    return fechas;
}
export { check_date_validity_range, days_of_diference, calcula_fechas_captura };
