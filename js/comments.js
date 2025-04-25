document.addEventListener('DOMContentLoaded', function() {
    // Handle comment form submission
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const postId = this.querySelector('input[name="post_id"]').value;
            const commentText = this.querySelector('textarea[name="comment"]').value;
            
            if (!commentText.trim()) {
                showMessage('Please enter a comment');
                return;
            }
            
            const formData = new FormData(this);
            formData.append('action', 'add_comment'); // Add missing action parameter
            
            fetch('../ajax_handlers/comment_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Clear the form
                    this.reset();
                    
                    // Refresh comments section or add the new comment to DOM
                    location.reload(); // Simple approach - reload the page
                } else {
                    showMessage(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.');
            });
        });
    }
    
    // Edit comment functionality
    document.addEventListener('click', function(e) {
        // Check if edit button was clicked
        if (e.target && e.target.name === 'open_edit_box') {
            e.preventDefault();
            
            const form = e.target.closest('form');
            const commentId = form.querySelector('input[name="comment_id"]').value;
            
            // Get comment text
            const commentBox = e.target.closest('.show-comments').querySelector('.comment-box');
            const commentText = commentBox.textContent.trim();
            
            // Create edit form dynamically
            const editForm = document.createElement('div');
            editForm.className = 'comment-edit-form';
            editForm.innerHTML = `
                <p>Edit Your Comment</p>
                <textarea id="edit-comment-${commentId}" required cols="30" rows="5">${commentText}</textarea>
                <button type="button" class="inline-btn save-edit" data-comment-id="${commentId}">Save Changes</button>
                <button type="button" class="inline-option-btn cancel-edit">Cancel</button>
            `;
            
            // Insert edit form after comment box
            commentBox.style.display = 'none';
            commentBox.insertAdjacentElement('afterend', editForm);
            
            // Hide edit/delete buttons while editing
            const actionButtons = form.querySelectorAll('button');
            actionButtons.forEach(btn => btn.style.display = 'none');
        }
    });
    
    // Handle edit submission and cancellation
    document.addEventListener('click', function(e) {
        // Save edited comment
        if (e.target && e.target.classList.contains('save-edit')) {
            const commentId = e.target.getAttribute('data-comment-id');
            const editedText = document.getElementById(`edit-comment-${commentId}`).value;
            const commentContainer = e.target.closest('.show-comments');
            
            if (editedText.trim() === '') {
                showMessage('Comment cannot be empty!');
                return;
            }
            
            // Send AJAX request to update comment
            const formData = new FormData();
            formData.append('comment_id', commentId);
            formData.append('comment', editedText);
            formData.append('action', 'edit_comment');
            
            fetch('../ajax_handlers/comment_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update comment text in DOM
                    const commentBox = commentContainer.querySelector('.comment-box');
                    commentBox.textContent = editedText;
                    commentBox.style.display = 'block';
                    
                    // Remove edit form
                    commentContainer.querySelector('.comment-edit-form').remove();
                    
                    // Show action buttons again
                    const actionButtons = commentContainer.querySelectorAll('form button');
                    actionButtons.forEach(btn => btn.style.display = 'inline-block');
                    
                    // Success message removed
                } else {
                    showMessage(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.');
            });
        }
        
        // Cancel comment editing
        if (e.target && e.target.classList.contains('cancel-edit')) {
            const commentContainer = e.target.closest('.show-comments');
            const commentBox = commentContainer.querySelector('.comment-box');
            
            // Remove edit form
            commentContainer.querySelector('.comment-edit-form').remove();
            
            // Show comment text again
            commentBox.style.display = 'block';
            
            // Show action buttons again
            const actionButtons = commentContainer.querySelectorAll('form button');
            actionButtons.forEach(btn => btn.style.display = 'inline-block');
        }
    });
    
    // For delete comment functionality
    document.addEventListener('click', function(e) {
        if (e.target && e.target.name === 'delete_comment') {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this comment?')) {
                const form = e.target.closest('form');
                const commentId = form.querySelector('input[name="comment_id"]').value;
                
                const formData = new FormData();
                formData.append('comment_id', commentId);
                formData.append('action', 'delete_comment'); // Add missing action parameter
                
                fetch('../ajax_handlers/comment_handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Remove comment from DOM
                        const commentContainer = e.target.closest('.show-comments');
                        commentContainer.remove();
                        
                        // Update comment count
                        const commentCountElement = document.querySelector('.fas.fa-comment + span');
                        const currentCount = parseInt(commentCountElement.textContent.replace(/[()]/g, ''));
                        commentCountElement.textContent = `(${currentCount - 1})`;
                        
                        // Success message removed
                    } else {
                        showMessage(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An error occurred. Please try again.');
                });
            }
        }
    });
    
    // Helper function to show messages (keep this for error messages)
    function showMessage(msg) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message';
        messageDiv.innerHTML = `<span>${msg}</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i>`;
        
        document.body.prepend(messageDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
});