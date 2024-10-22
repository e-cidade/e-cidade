function clearModaFieldsRenderComponents() {
    const campos = document.querySelectorAll('.form-modal-body-render-components input, .form-modal-body-render-components textarea, .form-modal-body-render-components select');

    campos.forEach(campo => {
        if (campo.type === 'checkbox' || campo.type === 'radio') {
            campo.checked = false;
        } else if (campo.tagName.toLowerCase() === 'select') {
            campo.selectedIndex = 0;
        } else {
            campo.value = '';
        }
    });
}