<div id="addMemberModal" class="modal" style="display:none;">
    <div class="modal-content">
        <!-- Close button for the modal -->
        <span class="close" onclick="closeModal('addMemberModal')">&times;</span>
        
        <!-- Header with Add Category button -->
        <h2>
            Add Member
            <button class="add-category-btn" onclick="openModal('addCategoryModal')">+ Add Category</button>
        </h2>
        
        <!-- Tab for categories -->
        <div id="categoryTab" class="category-tab">
            <h3>Categories</h3>
            <table id="categoryTable">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Categories will be dynamically loaded here -->
                    <!-- Example -->
                    <tr>
                        <td>Example Category</td>
                        <td><button class="delete-btn" onclick="deleteCategory(1)">Delete</button></td>
                    </tr>
                    <!-- More rows will be populated from the database -->
                </tbody>
            </table>
        </div>
        
        <!-- Form for adding a member -->
        <form id="addMemberForm" action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="position">Position:</label>
            <input type="text" name="position" id="position" required>

            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id">
                <option value="existing">Select existing category</option>
                <!-- Dynamic categories will be loaded here -->
            </select>

            <label for="date_appointed">Date Appointed:</label>
            <input type="date" name="date_appointed" id="date_appointed" required />

            <label for="image">Image:</label>
            <input type="file" name="image" id="image" accept="image/*">

            <button type="submit" class="btn">Add Member</button>
        </form>
    </div>
</div>
