<?php
require_once __DIR__ . '/../classes/organization.class.php';

$db = new Database();
$conn = $db->connect();

// Initialize Organization class
$organization = new Organization();
$members = $organization->getOrganizations();

session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
$admin_role = $_SESSION['admin_role'] ?? null;

// Redirect if not logged in or not a superadmin
if (!isset($admin_id) || $admin_role != 'superadmin') {
    header('location:../admin/admin_login.php');
    exit();
}

// Initialize messages array
$message = [];

// Handle Delete Member
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_member'])) {
    try {
        $org_id = $_POST['org_id'] ?? null;

        if ($org_id) {
            $organization->deleteOrganization($org_id);
            $_SESSION['message'] = 'Member removed successfully.';
        } else {
            $_SESSION['message'] = 'Invalid member ID.';
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (Exception $e) {
        $_SESSION['message'] = 'Error deleting member: ' . $e->getMessage();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle Delete Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    try {
        $category_id = $_POST['category_id'] ?? null;

        if ($category_id) {
            $organization->deleteCategory($category_id);
            $_SESSION['message'] = 'Category removed successfully.';
        } else {
            $_SESSION['message'] = 'Invalid category ID.';
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (Exception $e) {
        $_SESSION['message'] = 'Error deleting category: ' . $e->getMessage();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Display session message if it exists
if (isset($_SESSION['message'])) {
    $message[] = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Fetch organization members
try {
    $org_members = $organization->getOrganizations(); // <-- Important
} catch (Exception $e) {
    $message[] = 'Error fetching organization members: ' . $e->getMessage();
    $org_members = [];
}

// Separate current and past members
$current_members = [];
$past_members = [];

foreach ($org_members as $member) {
    if (empty($member['date_ended'])) {
        $current_members[] = $member;
    } else {
        $past_members[] = $member;
    }
}

// Fetch categories for the dropdown
try {
    $categories = $organization->getCategories();
} catch (Exception $e) {
    $message[] = 'Error fetching categories: ' . $e->getMessage();
    $categories = [];
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
<body>

<?php include '../components/superadmin_header.php' ?>

<section class="org-chart-controls">
    <h1 class="heading">Organizational Chart Management</h1>



    <!-- Trigger Buttons -->
    <div class="flex-btn">
        <button class="btn" onclick="openModal('addMemberModal')">+ Add Member</button>
        <button class="btn" onclick="openDeleteCategoryModal()">Manage/Delete Categories</button>
        <div id="delete-category-container"></div>
    </div>

    <div id="formContainer"></div>
</section>




<h1 class="heading">Organizational Members</h1>

<!-- Tabs -->
<div class="tab-buttons">
    <button class="tab-btn active" onclick="showTab('current')">Current Members</button>
    <button class="tab-btn" onclick="showTab('past')">Past Members</button>
</div>

<!-- Filter Members -->

<div class="filters-minimal">
        <select id="filterCategory">
            <option value="all">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['category_name']) ?>">
                    <?= htmlspecialchars($cat['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select id="filterPosition">
            <option value="all">All Positions</option>
            <?php
            $positions = array_unique(array_column($org_members, 'position'));
            foreach ($positions as $pos):
            ?>
                <option value="<?= htmlspecialchars($pos) ?>"><?= htmlspecialchars($pos) ?></option>
            <?php endforeach; ?>
        </select>

        

    </div>




<!-- Current Members -->
<div id="current" class="tab-content show-org-members active">

    <div class="box-container">
        <?php if (count($current_members) > 0): ?>
            <?php foreach ($current_members as $member): ?>
                <div class="box">
                    <h3><?= htmlspecialchars($member['name']); ?></h3>
                    <?php if (!empty($member['image'])): ?>
                        <img src="../<?= htmlspecialchars($member['image']); ?>" alt="<?= htmlspecialchars($member['name']); ?>">
                    <?php endif; ?>
                    <p><?= htmlspecialchars($member['position']); ?></p>
                    <p>Category: <?= htmlspecialchars($member['category_name'] ?? 'Uncategorized'); ?></p>
                    <p>Appointed: <?= htmlspecialchars($member['date_appointed']); ?></p>
                    <div class="flex-btn">
                        <form action="" method="post" onsubmit="return confirm('Remove this member?');">
                            <input type="hidden" name="org_id" value="<?= $member['org_id']; ?>">
                            <button type="submit" name="delete_member" class="delete-btn">Remove</button>
                        </form>

                        <button class="btn edit-btn" onclick="editMember(<?= $member['org_id']; ?>)">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                    </div>

                </div>
                
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No current members found!</p>
        <?php endif; ?>
    </div>
</div>

<!-- Past Members -->
<div id="past" class="tab-content show-org-members">
    <div class="box-container">
        <?php if (count($past_members) > 0): ?>
            <?php foreach ($past_members as $member): ?>
                <div class="box">
                    <h3><?= htmlspecialchars($member['name']); ?></h3>
                    <?php if (!empty($member['image'])): ?>
                        <img src="../<?= htmlspecialchars($member['image']); ?>" alt="<?= htmlspecialchars($member['name']); ?>">
                    <?php endif; ?>
                    <p><?= htmlspecialchars($member['position']); ?></p>
                    <p>Category: <?= htmlspecialchars($member['category_name'] ?? 'Uncategorized'); ?></p>
                    <p>Appointed: <?= htmlspecialchars($member['date_appointed']); ?></p>
                    <p>Ended: <?= htmlspecialchars($member['date_ended']); ?></p>
                    <div class="flex-btn">
                        <form action="" method="post" onsubmit="return confirm('Remove this member?');">
                            <input type="hidden" name="org_id" value="<?= $member['org_id']; ?>">
                            <button type="submit" name="delete_member" class="delete-btn">Remove</button>
                        </form>

                        <button class="btn edit-btn" onclick="editMember(<?= $member['org_id']; ?>)">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No past members found!</p>
        <?php endif; ?>
    </div>
</div>

<div id="category-container">
    <!-- delete_category_modal.php content will load here via AJAX -->
</div>

<!-- Modals -->
<?php
include('../modals/add_member_modal.php');
include('../modals/add_category_modal.php');
?>


<script src="../js/org_chart.js"></script>
</body>
</html>
