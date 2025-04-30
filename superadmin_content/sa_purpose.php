<?php
require_once __DIR__ . '/../components/connect.php';

$db = new Database();
$conn = $db->connect();

session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
$admin_role = $_SESSION['admin_role'] ?? null;

if (!isset($admin_id) || $admin_role !== 'superadmin') {
    header('Location: ../access_denied.php');
    exit();
}

$message = [];

// Handle update
if (isset($_POST['update_purpose'])) {
    $purpose_id = $_POST['purpose_id'];
    $purpose_text = filter_var($_POST['purpose_text'], FILTER_SANITIZE_STRING);
    $school_name = filter_var($_POST['school_name'], FILTER_SANITIZE_STRING);
    $campus_name = filter_var($_POST['campus_name'], FILTER_SANITIZE_STRING);
    $established_year = filter_var($_POST['established_year'], FILTER_SANITIZE_STRING);
    $operating_hours = filter_var($_POST['operating_hours'], FILTER_SANITIZE_STRING);

    $update_stmt = $conn->prepare("UPDATE purpose_card SET purpose_text=?, school_name=?, campus_name=?, established_year=?, operating_hours=?, updated_at=NOW() WHERE purpose_id=?");
    $update_stmt->execute([$purpose_text, $school_name, $campus_name, $established_year, $operating_hours, $purpose_id]);

    $message[] = "Purpose card updated successfully!";
}

// Fetch all purpose cards
$select_purpose = $conn->prepare("SELECT * FROM purpose_card ORDER BY purpose_id DESC");
$select_purpose->execute();
$purpose_cards = $select_purpose->fetchAll(PDO::FETCH_ASSOC);

// Fetch for editing
$edit_id = $_GET['edit'] ?? null;
$edit_purpose = null;

if ($edit_id) {
    $select_edit = $conn->prepare("SELECT * FROM purpose_card WHERE purpose_id = ?");
    $select_edit->execute([$edit_id]);
    if ($select_edit->rowCount() > 0) {
        $edit_purpose = $select_edit->fetch(PDO::FETCH_ASSOC);
    } else {
        $message[] = 'Purpose not found!';
        $edit_id = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Purpose Card - Superadmin</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="../css/purpose.css">
       <!-- Font Awesome CDN Link -->
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

        <!-- Admin CSS File Link -->
        <link rel="stylesheet" href="../css/admin_style.css">
        <link rel="stylesheet" href="../css/sa_carousel.css">

        <!-- Carousel CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .purpose-card {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
    
</head>
<body>

<?php include __DIR__ . '/../components/superadmin_header.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4">Manage Purpose Card</h1>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info">
            <?php foreach ($message as $msg): ?>
                <p><?= htmlspecialchars($msg) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <?php if (count($purpose_cards) > 0): ?>
                <?php foreach ($purpose_cards as $card): ?>
                    <div class="purpose-card">
                        <h4><?= htmlspecialchars($card['school_name']) ?> - <?= htmlspecialchars($card['campus_name']) ?></h4>
                        <p><strong>Purpose:</strong> <?= nl2br(htmlspecialchars($card['purpose_text'])) ?></p>
                        <p><strong>Established:</strong> <?= htmlspecialchars($card['established_year']) ?></p>
                        <p><strong>Operating Hours:</strong> <?= htmlspecialchars($card['operating_hours']) ?></p>
                        <a href="?edit=<?= $card['purpose_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No purpose cards available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($edit_purpose): ?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-warning">
            <h5 class="mb-0">Edit Purpose Card</h5>
        </div>
        <div class="card-body">
            <form action="sa_purpose.php" method="POST">
                <input type="hidden" name="purpose_id" value="<?= $edit_purpose['purpose_id'] ?>">

                <div class="form-group">
                    <label for="purpose_text">Purpose Text</label>
                    <textarea name="purpose_text" id="purpose_text" class="form-control" rows="4" required><?= htmlspecialchars($edit_purpose['purpose_text']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="school_name">School Name</label>
                    <input type="text" name="school_name" id="school_name" class="form-control" value="<?= htmlspecialchars($edit_purpose['school_name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="campus_name">Campus Name</label>
                    <input type="text" name="campus_name" id="campus_name" class="form-control" value="<?= htmlspecialchars($edit_purpose['campus_name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="established_year">Established Year</label>
                    <input type="text" name="established_year" id="established_year" class="form-control" value="<?= htmlspecialchars($edit_purpose['established_year']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="operating_hours">Operating Hours</label>
                    <input type="text" name="operating_hours" id="operating_hours" class="form-control" value="<?= htmlspecialchars($edit_purpose['operating_hours']) ?>" required>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" name="update_purpose" class="btn btn-primary">Save Changes</button>
                    <a href="sa_purpose.php" class="btn btn-secondary ml-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
