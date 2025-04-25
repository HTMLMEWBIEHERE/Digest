// Expand/Collapse Description
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-desc-btn').forEach(button => {
      button.addEventListener('click', function () {
        const desc = this.previousElementSibling;
        desc.classList.toggle('collapsed');
        this.textContent = desc.classList.contains('collapsed') ? 'Show more' : 'Show less';
      });
    });
  
    // Auto-submit form on category change
    const categorySelect = document.querySelector('select[name="category"]');
    if (categorySelect) {
      categorySelect.addEventListener('change', function () {
        this.form.submit();
      });
    }
  
    // Auto-submit on search (after user stops typing for 500ms)
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
      let debounceTimer;
      searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
          this.form.submit();
        }, 500);
      });
    }
  });
  
  // Image Modal
  function openModal(image) {
    image.classList.add('modal-active');
    document.querySelector('.modal-overlay').style.display = 'block';
  }
  
  function closeModal() {
    const activeImage = document.querySelector('.modal-active');
    if (activeImage) {
      activeImage.classList.remove('modal-active');
    }
    document.querySelector('.modal-overlay').style.display = 'none';
  }
  
  window.openModal = openModal;
  window.closeModal = closeModal;
  