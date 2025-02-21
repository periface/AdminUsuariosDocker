let WEB_URL = '/areas';
let API_URL = '/api/areas';

export const fetchAreas = async () => {
    try {

        const response = await fetch(`${WEB_URL}/fetchAreas`, {
            method: "GET",
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        });

        return response.text();

    } catch (error) {
        console.log('Ocurrió un error', error);
        return `<p>Ocurrió un error al obtener la información de las áreas</p>`;
    }
};