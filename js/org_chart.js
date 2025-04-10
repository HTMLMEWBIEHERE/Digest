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
            // Append new category to category list
            const categoryList = document.getElementById('categoryList');
            const newItem = document.createElement('li');
            newItem.textContent = data.category_name;
            categoryList.appendChild(newItem);

            // Reset input field and close the modal
            categoryNameInput.value = '';
            closeModal('addCategoryModal');
        } else {
            alert(data.message || 'Failed to add category.');
        }
    })
    .catch(err => {
        console.error('AJAX Error:', err);
        alert('Something went wrong!');
    });
});

// Handle the add member form submission
document.getElementById('addMemberForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = document.getElementById('addMemberForm');
    const formData = new FormData(form);

    const categorySelect = document.getElementById('category_id');
    const selectedCategory = categorySelect.value;
    
    // If category is new, make sure to append category as 'new' and include new category name
    if (selectedCategory === 'new') {
        formData.append('category', 'new');
        formData.append('category_id', '');  // leave blank if category is new
        formData.append('new_category', document.getElementById('new_category').value);  // new category name
    } else {
        formData.append('category', 'existing');
        formData.append('category_id', selectedCategory);  // Use existing category id
    }

    // Send the form data to the backend via Fetch API
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
            loadOrganizationList(); // Call the refresh function to load the latest list
        } else {
            alert(data.message || 'Something went wrong.');
        }
    })
    .catch(err => {
        console.error('AJAX Error:', err);
        alert('Something went wrong!');
    });
});

// Helper function to load the organization list (refresh the UI)
function loadOrganizationList() {
    // Implement logic to refresh the organization list, e.g., by fetching the list again
    fetch('../superadmin_content/get_organization_list.php')
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Update the DOM with the new list of members
            const membersList = document.getElementById('membersList');
            membersList.innerHTML = ''; // Clear existing members
            data.members.forEach(member => {
                const memberItem = document.createElement('li');
                memberItem.textContent = `${member.name} - ${member.position}`;
                membersList.appendChild(memberItem);
            });
        } else {
            alert('Failed to load organization members.');
        }
    })
    .catch(err => {
        console.error('AJAX Error:', err);
        alert('Failed to load organization members.');
    });
}


