<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Add New Service (admin/services/create.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

// Enforce authentication check first
require_login();

$errors = [];
$title = '';
$description = '';
$price = '';

// Process POST request BEFORE sending any HTML headers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $errors[] = 'Security token expired. Please refresh and try again.';
    } else {
        $title       = sanitize($_POST['title'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $price       = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_FLOAT);

        // Validation rules
        if (empty($title) || mb_strlen($title) < 3) {
            $errors[] = 'Service title is required (minimum 3 characters).';
        }

        if (empty($description) || mb_strlen($description) < 10) {
            $errors[] = 'Service description is required (minimum 10 characters).';
        }

        if ($price === false || $price < 0) {
            $errors[] = 'Please provide a valid non-negative price amount.';
        }

        $imageFilename = null;

        // Process file upload if selected
        if (!empty($_FILES['image']['name'])) {
            $uploadResult = upload_image($_FILES['image'], SERVICES_UPLOAD_PATH);
            if ($uploadResult['success']) {
                $imageFilename = $uploadResult['filename'];
            } else {
                $errors[] = 'Image Upload Error: ' . $uploadResult['error'];
            }
        }

        // Insert into database if no validation errors
        if (empty($errors)) {
            try {
                $pdo = getDBConnection();
                $userId = current_user()['id'];
                
                $stmt = $pdo->prepare("
                    INSERT INTO `services` (`user_id`, `title`, `description`, `price`, `image`, `created_at`) 
                    VALUES (:user_id, :title, :description, :price, :image, NOW())
                ");
                $stmt->execute([
                    ':user_id'     => $userId,
                    ':title'       => $title,
                    ':description' => $description,
                    ':price'       => $price,
                    ':image'       => $imageFilename
                ]);

                set_flash('success', 'Service "<strong>' . e($title) . '</strong>" has been created successfully.');
                header('Location: ' . BASE_URL . 'admin/services/index.php');
                exit;
            } catch (PDOException $e) {
                error_log('Create Service Error: ' . $e->getMessage());
                if ($imageFilename) {
                    delete_uploaded_file(SERVICES_UPLOAD_PATH . DIRECTORY_SEPARATOR . $imageFilename);
                }
                $errors[] = 'Database insertion error: ' . $e->getMessage();
            }
        }
    }
}

$adminPage  = 'services';
$adminTitle = 'Add New Service';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Add New Service</h4>
        <p class="text-muted small mb-0">Create a new IT service offering to showcase on the public website.</p>
    </div>
    <a href="<?= BASE_URL ?>admin/services/index.php" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Back to Services
    </a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Please correct the following:</strong>
        <ul class="mb-0 mt-2 small">
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">
    <form action="<?= BASE_URL ?>admin/services/create.php" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row g-4">
            <div class="col-md-8">
                <!-- Title Field -->
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold small text-dark">Service Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" 
                           placeholder="e.g. Laptop Motherboard Chip-Level Repair" 
                           value="<?= e($title) ?>" required>
                </div>

                <!-- Price Field -->
                <div class="mb-3">
                    <label for="price" class="form-label fw-semibold small text-dark">Starting Price (PKR) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold text-muted">PKR</span>
                        <input type="number" step="0.01" min="0" class="form-control font-monospace" id="price" name="price" 
                               placeholder="2500.00" 
                               value="<?= e($price) ?>" required>
                    </div>
                    <div class="form-text small">Enter estimated baseline service cost in Pakistani Rupees.</div>
                </div>

                <!-- Description Field -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold small text-dark">Detailed Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="6" 
                              placeholder="Provide clear details on what this service entails, troubleshooting steps, and customer benefits..." 
                              required><?= e($description) ?></textarea>
                </div>
            </div>

            <!-- Image Upload & Preview Column -->
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-dark">Service Cover Image</label>
                <div class="image-preview-container mb-3 text-center">
                    <img id="servicePreview" src="" alt="Preview" class="d-none">
                    <div id="previewPlaceholder" class="text-muted small p-3">
                        <i class="bi bi-cloud-arrow-up fs-1 d-block text-secondary mb-2"></i>
                        <span>Click browse below to preview image</span>
                    </div>
                </div>

                <div class="mb-3">
                    <input type="file" class="form-control form-control-sm" id="image" name="image" 
                           accept="image/jpeg,image/png,image/webp" 
                           data-preview-target="#servicePreview">
                    <div class="form-text small">
                        Formats: JPG, PNG, WEBP.<br>Max size: 5MB.
                    </div>
                </div>
            </div>

            <div class="col-12 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                    <i class="bi bi-check-lg me-1"></i> Save Service
                </button>
                <a href="<?= BASE_URL ?>admin/services/index.php" class="btn btn-light border rounded-pill px-4">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
