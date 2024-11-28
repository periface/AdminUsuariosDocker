const eventListener = (className, functionName) => {
    const elements = document.querySelectorAll(className);
    if(elements.length > 0){
        elements.forEach(element => {
            element.addEventListener('click', (event) => {
                event.preventDefault();
                let id = element.getAttribute('id');
                functionName(id);

            });
        });
    }
}

export { eventListener };