document.addEventListener('DOMContentLoaded', function() {
    console.log('Document loaded');
    
    // Select all elements with class like-btn (buttons and divs)
    const likeButtons = document.querySelectorAll('.like-btn');
    console.log('Found like elements:', likeButtons.length);
    
    likeButtons.forEach(button => {
        // Skip if it's an anchor tag (for non-logged in users)
        if (button.tagName === 'A') return;
        
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Like clicked');
            
            // Get post ID from data attribute or closest form
            let postId;
            if (this.dataset.postId) {
                postId = this.dataset.postId;
            } else {
                const form = this.closest('form');
                if (!form) {
                    console.error('No form found');
                    return;
                }
                const postIdInput = form.querySelector('input[name="post_id"]');
                if (!postIdInput) {
                    console.error('No post_id input found');
                    return;
                }
                postId = postIdInput.value;
            }
            
            console.log('Liking post ID:', postId);
            
            const heartIcon = this.querySelector('i.fas.fa-heart');
            const isCurrentlyLiked = heartIcon.style.color === 'var(--red)' || 
                                     heartIcon.style.color === 'red';
            
            // Toggle heart color for immediate feedback
            heartIcon.style.color = isCurrentlyLiked ? '' : 'var(--red)';
            
            // Use fetch for the AJAX request
            fetch('components/like_post.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'post_id=' + postId
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.status === 'success') {
                    // Update like count 
                    const likeCount = this.querySelector('span');
                    likeCount.textContent = '(' + data.likes + ')';
                    
                    // Update heart color based on server response
                    if (data.action === 'liked') {
                        heartIcon.style.color = 'var(--red)';
                    } else {
                        heartIcon.style.color = '';
                    }
                } else {
                    console.error('Error response:', data.message);
                    heartIcon.style.color = isCurrentlyLiked ? 'var(--red)' : '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                heartIcon.style.color = isCurrentlyLiked ? 'var(--red)' : '';
            });
            
            return false;
        });
    });
});