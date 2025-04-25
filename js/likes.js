document.addEventListener('DOMContentLoaded', function() {
    console.log('Document loaded');
    
    // Handle like button clicks
    document.addEventListener('click', function(e) {
        // Check if a like button was clicked
        if (e.target && (
            e.target.classList.contains('like-btn') || 
            e.target.closest('.like-btn')
        )) {
            e.preventDefault();
            
            // Get the like button (could be the clicked element or its parent)
            const likeBtn = e.target.classList.contains('like-btn') ? 
                            e.target : 
                            e.target.closest('.like-btn');
            
            // Get post ID from data attribute
            const postId = likeBtn.getAttribute('data-post-id');
            
            // If no post ID, exit
            if (!postId) {
                console.error('No post ID found');
                return;
            }
            
            // Get heart icon and likes count elements
            const heartIcon = likeBtn.querySelector('.fas.fa-heart');
            const likesCountElement = document.getElementById(`likes-count-${postId}`);
            
            if (!heartIcon || !likesCountElement) {
                console.error('Missing required elements:', { heartIcon, likesCountElement });
                return;
            }
            
            // Send AJAX request
            const formData = new FormData();
            formData.append('post_id', postId);
            
            fetch('../ajax_handlers/like_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update like count
                    likesCountElement.textContent = `(${data.likes})`;
                    
                    // Update heart icon color without showing messages
                    if (data.action === 'liked') {
                        heartIcon.style.color = 'var(--red)';
                    } else {
                        heartIcon.style.color = '';
                    }
                } else {
                    // Only show error messages
                    showMessage(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.');
            });
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