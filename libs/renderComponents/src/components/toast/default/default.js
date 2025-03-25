function createToast(message, type = 'info', duration = 3000) {
    const toastContainer = document.getElementById('toast-container-98P6m');

    var icon = "";

    if(type == 'success') {
        icon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>`;
    } else if(type == 'danger') {
        icon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>`;
    } else if(type == 'warning') {
        icon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>`;
    } else if(type == 'info') {
        icon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>`;
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type} hide-98P6m`;
    toast.innerHTML = `
        <div id="toast-success-98P6m" class="toast-98P6m toast-${type}-98P6m" role="alert">
            <div class="icon-container-98P6m">
                ${icon}
            </div>
            <div class="text-98P6m">${message}</div>
            <button class="close-button-98P6m" data-dismiss="toast-success-98P6m" aria-label="Close">
                <svg class="close-icon-98P6m" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
    `;

    // Add to container
    toastContainer.appendChild(toast);

    
    // Add show animation
    setTimeout(() => {
        toast.classList.remove('hide-98P6m');
        toast.classList.add('show-98P6m');
    }, 100);

    // Close toast on button click
    toast.querySelector('.close-button-98P6m').addEventListener('click', () => removeToast(toast));

    // Auto-hide toast after duration
    setTimeout(() => removeToast(toast), duration);
}

function removeToast(toast) {
    // Add hide animation
    toast.classList.add('hide-98P6m');

    // Adicione um fallback caso o evento `transitionend` não seja disparado
    setTimeout(() => {
        if (toast.parentElement) {
            toast.parentElement.removeChild(toast);
        }
    }, 300); // 300ms é o mesmo tempo da transição CSS
}