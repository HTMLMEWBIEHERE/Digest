<div id="addMemberModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addMemberModal')">&times;</span>
        <h2>Add Member</h2>
        <form id="addMemberForm" action="" method="POST">
            <!-- Form fields for adding a member -->
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="position">Position:</label>
            <input type="text" name="position" id="position" required>

            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id">
                <option value="existing">Select existing category</option>
                <!-- Add dynamic categories here -->
                <option value="new">Create new category</option>
            </select>

            <div id="new-category-box" style="display:none;">
                <label for="new_category">New Category:</label>
                <input type="text" name="new_category" id="new_category">
            </div>

            <label for="image">Image:</label>
            <input type="file" name="image" id="image" accept="image/*">

            <button type="submit" class="btn">Add Member</button>
        </form>
    </div>
</div>
