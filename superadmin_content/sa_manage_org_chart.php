<?php
require_once __DIR__ . '/../classes/organization.class.php';

$db = new Database();
$conn = $db->connect();

// Initialize Organization class
$organization = new Organization();

session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
$admin_role = $_SESSION['admin_role'] ?? null;

// Redirect if not logged in or not a superadmin
if (!isset($admin_id) || $admin_role != 'superadmin') {
    header('location:../admin/admin_login.php');
    exit();
}

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

        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (Exception $e) {
        $_SESSION['message'] = 'Error deleting member: ' . $e->getMessage();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Initialize messages array
$message = [];

// Display session message if it exists
if (isset($_SESSION['message'])) {
    $message[] = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Handle Delete Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    try {
        $category_id = $_POST['category_id'] ?? null;

        if ($category_id) {
            $organization->deleteCategory($category_id);  // Assuming you have a deleteCategory method
            $_SESSION['message'] = 'Category removed successfully.';
        } else {
            $_SESSION['message'] = 'Invalid category ID.';
        }

        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (Exception $e) {
        $_SESSION['message'] = 'Error deleting category: ' . $e->getMessage();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Initialize messages array
$message = [];

// Display session message if it exists
if (isset($_SESSION['message'])) {
    $message[] = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Fetch org members
try {
    $org_members = $organization->getOrganizations();
} catch (Exception $e) {
    $message[] = 'Error fetching org members: ' . $e->getMessage();
}

// Fetch categories for the dropdown
try {
    $categories = $organization->getCategories();
} catch (Exception $e) {
    $message[] = 'Error fetching categories: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Organizational Chart</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/superadmin_header.php' ?>

<section class="org-chart-controls">
    <h1 class="heading">Organizational Chart Management</h1>

    <?php
    if (isset($message) && is_array($message)) {
        foreach ($message as $msg) {
            echo '<div class="message">
                <span>' . htmlspecialchars($msg) . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>';
        }
    }
    ?>

    <!-- Trigger Buttons -->
    <div class="flex-btn">
        <button class="btn" onclick="openModal('addMemberModal')">+ Add Member</button>
        <button class="btn" onclick="openDeleteCategoryModal()">Manage/Delete Categories</button>
        <div id="delete-category-container"></div>
    </div>

    <div id="formContainer"></div>
</section>


<ul id="membersList" style="display: none;"></ul>

<ul id="categoryList" style="display: none;">
    <!-- Categories will be appended here dynamically -->
</ul>

<section class="show-org-members">
    <h1 class="heading">Organizational Members</h1>
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
    <div class="box-container">
        <?php if (count($org_members) > 0): ?>
            <?php foreach ($org_members as $member): ?>
                <div class="box">
                    <h3><?= htmlspecialchars($member['name']); ?></h3>
                    <p><?= htmlspecialchars($member['position']); ?></p>
                    <p>Category: <?= htmlspecialchars($member['category_name'] ?? 'Uncategorized'); ?></p>
                    <?php if (!empty($member['image'])): ?>
                        <img src="../<?= htmlspecialchars($member['image']); ?>" alt="<?= htmlspecialchars($member['name']); ?>">
                    <?php endif; ?>
                    <p>Appointed: <?= htmlspecialchars($member['date_appointed']); ?></p>
                    <div class="flex-btn">
                        <!-- Remove Button -->
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
            <p class="empty">No org members found!</p>
        <?php endif; ?>
    </div>
</section>

<div id="category-container">
    <!-- delete_category_modal.php content will load here via AJAX -->
</div>

<!-- Modals -->
<?php
include('../modals/add_member_modal.php');
include('../modals/add_category_modal.php');
?>

<script>
    // Populate category dropdown
    const categories = <?php echo json_encode($categories); ?>;
    const categorySelect = document.getElementById('category_id');

    if (categorySelect && categories.length > 0) {
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.category_id;
            option.textContent = category.category_name;
            categorySelect.appendChild(option);
        });
    }

    // Toggle new category input
    if (categorySelect) {
        categorySelect.addEventListener('change', function () {
            const newCategoryBox = document.getElementById('new-category-box');
            if (this.value === 'new') {
                newCategoryBox.style.display = 'block';
            } else {
                newCategoryBox.style.display = 'none';
            }
        });
    }


    document.addEventListener('DOMContentLoaded', () => {
    const categoryFilter = document.getElementById('filterCategory');
    const positionFilter = document.getElementById('filterPosition');
    const memberBoxes = document.querySelectorAll('.box-container .box');

    function filterMembers() {
        const catVal = categoryFilter.value.toLowerCase();
        const posVal = positionFilter.value.toLowerCase();

        memberBoxes.forEach(box => {
            const categoryText = box.querySelector('p:nth-of-type(2)').textContent.toLowerCase();
            const positionText = box.querySelector('p:nth-of-type(1)').textContent.toLowerCase();

            const show = (catVal === 'all' || categoryText.includes(catVal)) &&
                         (posVal === 'all' || positionText.includes(posVal));
            box.style.display = show ? 'block' : 'none';
        });
    }

    categoryFilter.addEventListener('change', filterMembers);
    positionFilter.addEventListener('change', filterMembers);
});
</script>

<script src="../js/org_chart.js"></script>
</body>
</html>
