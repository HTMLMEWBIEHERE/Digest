// Open modal function
function openModal(id) {
    document.getElementById(id).style.display = 'block';
}

// Close modal function
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

// Handle form submission for Add Category modal
document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission

    // Get category name from the form input
    const categoryName = document.getElementById('category_name').value;

    if (categoryName.trim() === '') {
        alert('Please enter a valid category name.');
        return;
    }

    // Get the category list where new categories will be added
    const categoryList = document.getElementById('categoryList');

    if (!categoryList) {
        alert('Category list element not found.');
        return;
    }

    // Create new list item for the category
    const newCategoryItem = document.createElement('li');
    newCategoryItem.textContent = categoryName;

    // Add the new category to the list
    categoryList.appendChild(newCategoryItem);

    // Close the modal
    closeModal('addCategoryModal');

    // Optionally, reset the form
    document.getElementById('category_name').value = '';
});
