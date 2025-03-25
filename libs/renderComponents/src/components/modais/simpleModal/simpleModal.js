function openModal(modalId, windowClick = true) {
    var modal = document.getElementById(modalId);
    modal.style.display = 'block';
    modal.classList.remove("fadeOut");
    modal.classList.add("fadeIn");
    modal.querySelector(".simple-modal-content").classList.remove("slideOut");
    modal.querySelector(".simple-modal-content").classList.add("slideIn");

    if (windowClick) {
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal(modalId);
            }
        }
    }
}

function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    modal.classList.remove("fadeIn");
    modal.classList.add("fadeOut");
    modal.querySelector(".simple-modal-content").classList.remove("slideIn");
    modal.querySelector(".simple-modal-content").classList.add("slideOut");
    setTimeout(function () {
        modal.style.display = 'none';
    }, 300);
}
