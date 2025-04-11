// Open modal function
function openModal(id) {
    document.getElementById(id).style.display = 'block';
}

// Close modal function
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

// Handle the add category form submission
document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const categoryNameInput = document.getElementById('category_name');
    const categoryName = categoryNameInput.value.trim();

    if (categoryName === '') {
        alert('Please enter a valid category name.');
        return;
    }

    const formData = new FormData();
    formData.append('category_name', categoryName);

    fetch('../superadmin_content/add_category.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Reset input and close modal
            categoryNameInput.value = '';
            closeModal('addCategoryModal');
            loadCategories(); // Reload categories instead of manually appending
        } else {
            alert(data.message || 'Failed to add category.');
        }
    })
    .catch(err => {
        console.error('AJAX Error:', err);
        alert('Something went wrong!');
    });
});

// Handle the add member form submission using AJAX
document.getElementById('addMemberForm').addEventListener('submit', function (e) {
    e.preventDefault();  // Prevent default form submission

    const form = document.getElementById('addMemberForm');
    const formData = new FormData(form);

    const categorySelect = document.getElementById('category_id');
    const selectedCategory = categorySelect.value;

    // If category is new
    if (selectedCategory === 'new') {
        formData.append('category', 'new');
        formData.append('category_id', '');
        formData.append('new_category', document.getElementById('new_category').value);
    } else {
        formData.append('category', 'existing');
        formData.append('category_id', selectedCategory);
    }

    fetch('../superadmin_content/add_member.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Member added!');
                closeModal('addMemberModal');
                form.reset();
                loadOrganizationList(); // Refresh member list via AJAX
            } else {
                alert(data.message || 'Something went wrong.');
            }
        })
        .catch(err => {
            console.error('AJAX Error:', err);
            alert('Something went wrong!');
        });
});




function loadOrganizationList() {
    fetch('../superadmin_content/get_organization_list.php')
    .then(response => response.json())
    .then(data => {
        // Assuming you're populating an element with the ID 'organization-list'
        const listContainer = document.getElementById('organization-list');
        listContainer.innerHTML = ''; // Clear existing list
        data.forEach(member => {
            const listItem = document.createElement('li');
            listItem.textContent = member.name; // Adjust according to your data structure
            listContainer.appendChild(listItem);
        });
    })
    .catch(err => {
        console.error('Error loading organization list:', err);
    });
}



// Load categories and display in deletion modal
function loadCategories() {
    fetch('../modals/delete_category_modal.php')
    .then(response => response.text())
    .then(html => {
        document.getElementById('category-container').innerHTML = html;
        bindDeleteButtons();
    })
    .catch(err => {
        console.error('Failed to load category list:', err);
    });
}

// Attach delete handlers to category delete buttons
function bindDeleteButtons() {
    document.querySelectorAll('.btn-delete-category').forEach(button => {
        button.addEventListener('click', function () {
            const categoryId = this.dataset.id;
            if (confirm("Are you sure you want to delete this category?")) {
                fetch('../superadmin_content/delete_category.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'category_id=' + encodeURIComponent(categoryId)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const elementToRemove = document.getElementById('category-' + categoryId);
                        if (elementToRemove) {
                            elementToRemove.remove();
                        }
                    } else {
                        alert(data.message || 'Failed to delete category.');
                    }
                })
                .catch(err => {
                    console.error('Delete error:', err);
                    alert('Something went wrong.');
                });
            }
        });
    });
}

// Optional: Open delete modal and load its content
function openDeleteCategoryModal() {
    fetch('../modals/delete_category_modal.php')
    .then(res => res.text())
    .then(html => {
        document.getElementById('delete-category-container').innerHTML = html;
        document.getElementById('deleteCategoryModal').style.display = 'block';
        bindDeleteButtons();
    })
    .catch(err => {
        console.error('Error loading modal:', err);
    });
}

function editMember(org_id) {
    fetch(`../superadmin_content/get_member_details.php?org_id=${org_id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Set form values
                document.getElementById('edit_org_id').value = data.member.org_id;
                document.getElementById('edit_name').value = data.member.name;
                document.getElementById('edit_position').value = data.member.position;
                document.getElementById('edit_date_appointed').value = data.member.date_appointed;
                document.getElementById('edit_date_ended').value = data.member.date_ended || '';
                document.getElementById('edit_existing_image').src = '../' + data.member.image;
                
                // Load categories
                loadCategoriesForEdit(data.member.category_id);
                
                // Show modal
                document.getElementById('editMemberModal').style.display = 'block';
            } else {
                alert('Error loading member details: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load member details');
        });
}

function loadCategoriesForEdit(selectedCategoryId) {
    fetch('../superadmin_content/get_categories.php')
        .then(response => response.json())
        .then(categories => {
            const select = document.getElementById('edit_category_id');
            select.innerHTML = '';
            
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.category_id;
                option.textContent = category.category_name;
                if (category.category_id == selectedCategoryId) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        });
}

$('#editMemberForm').submit(function(e) {
    e.preventDefault();
    
    let formData = new FormData(this);
    
    $.ajax({
        url: '../superadmin_content/edit_member.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json', // 👈 Tells jQuery to expect JSON response
        success: function(data) {
            if (data.status === 'success') {
                alert('Member updated successfully');
                closeModal('editMemberModal');
                loadOrganizationList(); // Refresh the list
            } else {
                alert('Error: ' + data.message);
            }
        },
        error: function(xhr) {
            alert('Error: ' + xhr.responseText);
        }
    });
});


// Function to close the modal
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Close modal when clicking outside it
window.onclick = function(event) {
    const modal = document.getElementById('deleteCategoryModal');
    if (event.target === modal) {
        modal.style.display = "none";
    }
};
