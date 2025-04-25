<?php
session_start();
require_once '../components/connect.php';

// Database connection
$db = new Database();
$conn = $db->connect();

// Check superadmin access
$admin_id = $_SESSION['admin_id'] ?? null;
$admin_role = $_SESSION['admin_role'] ?? null;
$is_superadmin = isset($admin_id) && ($admin_role === 'superadmin');

if (!$is_superadmin) {
    header('Location: ../access_denied.php');
    exit();
}

// Pagination setup
$records_per_page = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

try {
    // Comprehensive activity logs query
    $logs_query = $conn->prepare("
        (SELECT 
            'Post' as type, 
            p.title, 
            p.created_at as timestamp, 
            p.status, 
            a.firstname, 
            a.lastname,
            a.user_name
        FROM posts p
        JOIN accounts a ON p.created_by = a.account_id)
        
        UNION ALL
        
        (SELECT 
            'Article', 
            ar.title, 
            ar.created_at, 
            ar.status, 
            a.firstname, 
            a.lastname,
            a.user_name
        FROM articles ar
        JOIN accounts a ON ar.created_by = a.account_id)
        
        UNION ALL
        
        (SELECT 
            'Tejido', 
            t.title, 
            t.created_at, 
            t.status, 
            a.firstname, 
            a.lastname,
            a.user_name
        FROM tejido t
        JOIN accounts a ON t.created_by = a.account_id)
        
        UNION ALL
        
        (SELECT 
            'E-Magazine', 
            e.title, 
            e.created_at, 
            'published', 
            a.firstname, 
            a.lastname,
            a.user_name
        FROM e_magazines e
        JOIN accounts a ON e.created_by = a.account_id)
        
        UNION ALL
        
        (SELECT 
            'Announcement', 
            an.title, 
            an.created_at, 
            'published', 
            a.firstname, 
            a.lastname,
            a.user_name
        FROM announcements an
        JOIN accounts a ON an.created_by = a.account_id)
        
        UNION ALL
        
        (SELECT 
            'New Account Registration', 
            CONCAT(a.firstname, ' ', a.lastname), 
            a.created_at, 
            a.role, 
            NULL, 
            NULL,
            a.user_name
        FROM accounts a)
        
        UNION ALL
        
        (SELECT 
            'Comment', 
            p.title, 
            c.commented_at, 
            'commented', 
            a.firstname, 
            a.lastname,
            a.user_name
        FROM comments c
        JOIN posts p ON c.post_id = p.post_id
        JOIN accounts a ON c.commented_by = a.account_id)
        
        UNION ALL
        
        (SELECT 
            'Like', 
            p.title, 
            l.created_at, 
            'liked', 
            a.firstname, 
            a.lastname,
            a.user_name
        FROM likes l
        JOIN posts p ON l.post_id = p.post_id
        JOIN accounts a ON l.account_id = a.account_id)
        
        ORDER BY timestamp DESC
        LIMIT :limit OFFSET :offset
    ");
    
    $logs_query->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
    $logs_query->bindParam(':offset', $offset, PDO::PARAM_INT);
    $logs_query->execute();
    $activity_logs = $logs_query->fetchAll(PDO::FETCH_ASSOC);

    // Get total logs count for pagination
    $count_query = $conn->query("
        SELECT 
            (SELECT COUNT(*) FROM posts) +
            (SELECT COUNT(*) FROM articles) +
            (SELECT COUNT(*) FROM tejido) +
            (SELECT COUNT(*) FROM e_magazines) +
            (SELECT COUNT(*) FROM announcements) +
            (SELECT COUNT(*) FROM accounts) +
            (SELECT COUNT(*) FROM comments) +
            (SELECT COUNT(*) FROM likes) as total_logs
    ");
    $total_logs = $count_query->fetch(PDO::FETCH_ASSOC)['total_logs'];
    $total_pages = ceil($total_logs / $records_per_page);

} catch(PDOException $e) {
    $message[] = 'Error fetching activity logs: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs | Superadmin</title>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/superadmin_header.php'; ?>

<section class="activity-logs">
    <h1 class="heading">Activity Logs</h1>
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search activity logs...">
        <button onclick="searchLogs()">Search</button>
    </div>


    <table class="activity-logs-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Title/Name</th>
                <th>Created By</th>
                <th>Timestamp</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($activity_logs)): ?>
                <?php foreach ($activity_logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['type']) ?></td>
                        <td><?= htmlspecialchars($log['title']) ?></td>
                        <td>
                            <?php if ($log['firstname'] || $log['lastname']): ?>
                                <?= htmlspecialchars($log['firstname'] . ' ' . $log['lastname']) ?> 
                                (<?= htmlspecialchars($log['user_name']) ?>)
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars(date("F j, Y, g:i a", strtotime($log['timestamp']))) ?></td>
                        <td><?= htmlspecialchars($log['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No activity logs found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $page == $i ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</section>

<!-- Admin JS -->
<script src="../js/admin_script.js"></script>
</body>
</html>
