const eventListener = (className, functionName) => {
    const elements = document.querySelectorAll(className);
    if(elements.length > 0){
        elements.forEach(element => {
            element.addEventListener('click', handleClick);
        });
    }

    function handleClick(event) {
        event.preventDefault();
        const id = this.getAttribute('id');
        functionName(id);
    }
}

export { eventListener };