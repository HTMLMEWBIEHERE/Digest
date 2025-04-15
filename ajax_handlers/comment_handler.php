<?php
// Include database connection
include '../components/connect.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['account_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please login to perform this action'
    ]);
    exit;
}

$user_id = $_SESSION['account_id'];
$db = new Database();
$conn = $db->connect();

// Handle different actions
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add_comment':
        addComment($conn, $user_id);
        break;
        
    case 'edit_comment':
        editComment($conn, $user_id);
        break;
        
    case 'delete_comment':
        deleteComment($conn, $user_id);
        break;
        
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid action'
        ]);
}

// Add comment function
function addComment($conn, $user_id) {
    $post_id = $_POST['post_id'] ?? '';
    $comment = $_POST['comment'] ?? '';
    
    // Validate data
    if (empty($post_id) || empty($comment)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required data'
        ]);
        exit;
    }
    
    // Sanitize data
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);
    
    // Verify the user exists
    $verify_user = $conn->prepare("SELECT * FROM `accounts` WHERE account_id = ?");
    $verify_user->execute([$user_id]);
    
    if ($verify_user->rowCount() == 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'User account not found. Please log out and log in again.'
        ]);
        exit;
    }
    
    // Check for duplicate comment
    $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ? AND commented_by = ? AND comment = ?");
    $verify_comment->execute([$post_id, $user_id, $comment]);
    
    if ($verify_comment->rowCount() > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Comment already added!'
        ]);
        exit;
    }
    
    // Insert comment
    $insert_comment = $conn->prepare("INSERT INTO `comments`(post_id, commented_by, comment) VALUES(?,?,?)");
    $insert_comment->execute([$post_id, $user_id, $comment]);
    
    if ($insert_comment->rowCount() > 0) {
        // Get user details for the response
        $select_user = $conn->prepare("SELECT * FROM `accounts` WHERE account_id = ?");
        $select_user->execute([$user_id]);
        $user_data = $select_user->fetch(PDO::FETCH_ASSOC);
        
        // Get the new comment ID
        $comment_id = $conn->lastInsertId();
        
        // Format HTML for the new comment
        $html = '
        <div class="show-comments" style="order:-1;">
            <div class="comment-user">
                <i class="fas fa-user"></i>
                <div>
                    <span>' . htmlspecialchars($user_data['user_name']) . '</span>
                    <div>' . date('M d, Y \a\t h:i A') . '</div>
                </div>
            </div>
            <div class="comment-box" style="color:var(--white); background:var(--black);">
                ' . htmlspecialchars($comment) . '
            </div>
            <form action="" method="POST">
                <input type="hidden" name="comment_id" value="' . $comment_id . '">
                <button type="submit" class="inline-option-btn" name="open_edit_box">Edit Comment</button>
                <button type="submit" class="inline-delete-btn" name="delete_comment">Delete Comment</button>
            </form>
        </div>';
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Comment added successfully!',
            'html' => $html
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add comment. Please try again.'
        ]);
    }
}

// Edit comment function
function editComment($conn, $user_id) {
    $comment_id = $_POST['comment_id'] ?? '';
    $comment = $_POST['comment'] ?? '';
    
    // Validate data
    if (empty($comment_id) || empty($comment)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required data'
        ]);
        exit;
    }
    
    // Sanitize data
    $comment = filter_var($comment, FILTER_SANITIZE_STRING);
    
    // Verify the comment exists and belongs to this user
    $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE comment_id = ? AND commented_by = ?");
    $verify_comment->execute([$comment_id, $user_id]);
    
    if ($verify_comment->rowCount() == 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Comment not found or you do not have permission to edit!'
        ]);
        exit;
    }
    
    // Update comment
    $update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE comment_id = ?");
    $update_comment->execute([$comment, $comment_id]);
    
    if ($update_comment->rowCount() > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Comment updated successfully!'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No changes made to the comment.'
        ]);
    }
}

// Delete comment function
function deleteComment($conn, $user_id) {
    $comment_id = $_POST['comment_id'] ?? '';
    
    // Validate data
    if (empty($comment_id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required data'
        ]);
        exit;
    }
    
    // Verify the comment exists and belongs to this user
    $verify_delete = $conn->prepare("SELECT * FROM `comments` WHERE comment_id = ? AND commented_by = ?");
    $verify_delete->execute([$comment_id, $user_id]);
    
    if ($verify_delete->rowCount() == 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Comment not found or you do not have permission to delete!'
        ]);
        exit;
    }
    
    // Delete comment
    $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE comment_id = ?");
    $delete_comment->execute([$comment_id]);
    
    if ($delete_comment->rowCount() > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Comment deleted successfully!'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to delete comment. Please try again.'
        ]);
    }
}
?>