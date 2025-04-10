<?php
header('Content-Type: application/json');

require_once '../classes/organization.class.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = trim($_POST['category_name'] ?? '');

    if ($categoryName === '') {
        echo json_encode([
            'success' => false,
            'message' => 'Category name cannot be empty.'
        ]);
        exit;
    }

    try {
        $organization = new Organization();
        $categoryId = $organization->addCategory($categoryName);

        echo json_encode([
            'success' => true,
            'message' => 'Category added successfully.',
            'category_id' => $categoryId,
            'category_name' => $categoryName
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
