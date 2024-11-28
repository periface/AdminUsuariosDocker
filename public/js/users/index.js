let formAuth = document.getElementById('authLogin');

formAuth.onsubmit = async(e) => {
    e.preventDefault();

    let formData = new FormData(e.target);

    const response = await fetch('/api/login/', {
        method: "POST",
        body: formData
    });

    const responseJson = await response.json();

    console.log('Este es el response: ', responseJson);

}