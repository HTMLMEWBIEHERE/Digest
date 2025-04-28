// Modal Scripts
function openModal() {
    document.getElementById('filterModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('filterModal').style.display = 'none';
}

// Close modal if user clicks outside the modal
window.onclick = function(event) {
    const modal = document.getElementById('filterModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}