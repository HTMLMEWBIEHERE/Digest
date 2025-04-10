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
      <button class="btn" onclick="openModal('addCategoryModal')">+ Add Category</button>
      <button class="btn" onclick="openModal('showCategoriesModal')">Show All Categories</button>
   </div>

   <div id="formContainer"></div>
</section>

<section class="show-org-members">
   <h1 class="heading">Organizational Members</h1>

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
                  <form action="" method="post" onsubmit="return confirm('Remove this member?');">
                     <input type="hidden" name="org_id" value="<?= $member['org_id']; ?>">
                     <button type="submit" name="delete_member" class="delete-btn">Remove</button>
                  </form>
               </div>
            </div>
         <?php endforeach; ?>
      <?php else: ?>
         <p class="empty">No org members found!</p>
      <?php endif; ?>
   </div>
</section>

<!-- Modals -->
<?php
include('../modals/add_member_modal.php');
include('../modals/add_category_modal.php');
?>

<!-- Show Categories Modal -->
<div id="showCategoriesModal" class="modal" style="display:none;">
   <div class="modal-content">
      <span class="close" onclick="closeModal('showCategoriesModal')">&times;</span>
      <h2>All Categories</h2>
      <ul id="categoryList">
         <?php if (count($categories) > 0): ?>
            <?php foreach ($categories as $category): ?>
               <li>
                  <?= htmlspecialchars($category['category_name']); ?>
                  <form action="" method="post" style="display:inline;">
                     <input type="hidden" name="category_id" value="<?= $category['category_id']; ?>">
                     <button type="submit" name="delete_category" class="delete-btn" onclick="return confirm('Are you sure you want to delete this category?');">Delete</button>
                  </form>
               </li>
            <?php endforeach; ?>
         <?php else: ?>
            <p>No categories found!</p>
         <?php endif; ?>
      </ul>
   </div>
</div>

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
   
</script>

<script src="../js/org_chart.js"></script>
</body>
</html>
