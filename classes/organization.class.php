<?php
require_once 'database.class.php';

class Organization {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Get all categories from org_categories table
    public function getCategories() {
        $query = "SELECT category_id, category_name FROM org_categories ORDER BY category_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new category
 // Add a new category
public function addCategory($category_name) {
    $query = "INSERT INTO org_categories (category_name) VALUES (?)";
    $stmt = $this->conn->prepare($query);
    if(!$stmt) {
        throw new Exception('Prepare failed: ' . implode(" ", $this->conn->errorInfo()));
    }
    
    if (!$stmt->execute([$category_name])) {
        $errorInfo = $stmt->errorInfo();
        throw new Exception('Failed to add category: ' . implode(" ", $errorInfo));
    }
    
    return $this->conn->lastInsertId();
}


    // Add Organization Member
    public function addOrganization($data) {
        // If a new category is provided, insert it first
        if (isset($data['category']) && $data['category'] === 'new' && !empty($data['new_category'])) {
            $data['category_id'] = $this->addCategory($data['new_category']);
        }

        // Check required fields
        if (empty($data['name']) || empty($data['position']) || empty($data['category_id']) || empty($data['date_appointed'])) {
            throw new Exception('Missing required member fields.');
        }

        // Prepare image upload
        $imagePath = $this->uploadImage($_FILES['image']);

        // Insert organization member
        $query = "INSERT INTO organizational_chart 
                  (name, image, position, category_id, date_appointed, date_ended, created_at, updated_at, is_deleted) 
                  VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW(), 0)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $data['name'],
            $imagePath,
            $data['position'],
            $data['category_id'],
            $data['date_appointed'],
            $data['date_ended'] ?? null
        ]);
        
        return 'Member Added Successfully!';
    }

    // Edit Organization Member
    public function editOrganization($org_id, $data) {
        // Validate required fields
        if (empty($org_id) || empty($data['name']) || empty($data['position']) || empty($data['category_id']) || empty($data['date_appointed'])) {
            throw new Exception('Missing required fields for editing.');
        }

        // Handle image upload if a new image is provided
        $imagePath = !empty($_FILES['image']['name']) 
            ? $this->uploadImage($_FILES['image']) 
            : $data['existing_image'];

        $query = "UPDATE organizational_chart 
                  SET name = ?, image = ?, position = ?, 
                      category_id = ?, date_appointed = ?, date_ended = ?, updated_at = NOW() 
                  WHERE org_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $data['name'],
            $imagePath,
            $data['position'],
            $data['category_id'],
            $data['date_appointed'],
            $data['date_ended'] ?? null,
            $org_id
        ]);

        return 'Member Updated Successfully!';
    }

    // Soft Delete Organization Member
    public function deleteOrganization($org_id) {
        $query = "UPDATE organizational_chart SET is_deleted = 1, updated_at = NOW() WHERE org_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$org_id]);
        return 'Member Deleted Successfully!';
    }

    // Get Organization Members
    public function getOrganizations() {
        $query = "SELECT oc.*, cat.category_name 
                  FROM organizational_chart oc
                  LEFT JOIN org_categories cat ON oc.category_id = cat.category_id
                  WHERE oc.is_deleted = 0 
                  ORDER BY oc.date_appointed DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Upload Image and Save Path
    private function uploadImage($image) {
        if ($image['error'] === UPLOAD_ERR_OK) {
            $targetDir = __DIR__ . '/../uploads/members/';
    
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
    
            // Check if file is a valid image
            $imageInfo = getimagesize($image['tmp_name']);
            if ($imageInfo === false) {
                throw new Exception('Uploaded file is not a valid image.');
            }
    
            $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $extension;
            $targetFilePath = $targetDir . $fileName;
    
            if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
                return 'uploads/members/' . $fileName;
            } else {
                throw new Exception('Image upload failed.');
            }
        }
    
        return null;
    }
    

    // Get a single organization member by ID
    public function getOrganizationById($org_id) {
        $query = "SELECT oc.*, cat.category_name 
                  FROM organizational_chart oc
                  LEFT JOIN org_categories cat ON oc.category_id = cat.category_id
                  WHERE oc.org_id = ? AND oc.is_deleted = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$org_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
