<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Edit Service (admin/services/edit.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$pdo = getDBConnection();
$serviceId = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);

if (!$serviceId) {
    set_flash('danger', 'Invalid service ID provided.');
    header('Location: ' . BASE_URL . 'admin/services/index.php');
    exit;
}

// Fetch existing record
$stmt = $pdo->prepare("SELECT * FROM `services` WHERE `id` = :id LIMIT 1");
$stmt->execute([':id' => $serviceId]);
$service = $stmt->fetch();

if (!$service) {
    set_flash('danger', 'The requested service was not found.');
    header('Location: ' . BASE_URL . 'admin/services/index.php');
    exit;
}

$errors = [];
$title        = $service['title'];
$description  = $service['description'];
$price        = $service['price'];
$currentImage = $service['image'];

// Process POST request BEFORE sending any HTML output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $errors[] = 'Security token expired. Please refresh and try again.';
    } else {
        $title       = sanitize($_POST['title'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $price       = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_FLOAT);

        if (empty($title) || mb_strlen($title) < 3) {
            $errors[] = 'Service title is required (minimum 3 characters).';
        }

        if (empty($description) || mb_strlen($description) < 10) {
            $errors[] = 'Service description is required (minimum 10 characters).';
        }

        if ($price === false || $price < 0) {
            $errors[] = 'Please provide a valid non-negative price.';
        }

        $newImageFilename = $currentImage;

        // Process replacement image if uploaded
        if (!empty($_FILES['image']['name'])) {
            $uploadResult = upload_image($_FILES['image'], SERVICES_UPLOAD_PATH);
            if ($uploadResult['success']) {
                $newImageFilename = $uploadResult['filename'];
                // Delete old image from disk if one existed
                if (!empty($currentImage)) {
                    delete_uploaded_file(SERVICES_UPLOAD_PATH . DIRECTORY_SEPARATOR . $currentImage);
                }
            } else {
                $errors[] = 'Image Upload Error: ' . $uploadResult['error'];
            }
        }

        if (empty($errors)) {
            try {
                $updateStmt = $pdo->prepare("
                    UPDATE `services` 
                    SET `title` = :title, `description` = :description, `price` = :price, `image` = :image 
                    WHERE `id` = :id
                ");
                $updateStmt->execute([
                    ':title'       => $title,
                    ':description' => $description,
                    ':price'       => $price,
                    ':image'       => $newImageFilename,
                    ':id'          => $serviceId
                ]);

                set_flash('success', 'Service "<strong>' . e($title) . '</strong>" updated successfully.');
                header('Location: ' . BASE_URL . 'admin/services/index.php');
                exit;
            } catch (PDOException $e) {
                error_log('Update Service Error: ' . $e->getMessage());
                $errors[] = 'Database update error: ' . $e->getMessage();
            }
        }
    }
}

$adminPage  = 'services';
$adminTitle = 'Edit Service';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Service #<?= e($service['id']) ?></h4>
        <p class="text-muted small mb-0">Update information and pricing for this IT service offering.</p>
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
    <form action="<?= BASE_URL ?>admin/services/edit.php?id=<?= e($serviceId) ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row g-4">
            <div class="col-md-8">
                <!-- Title Field -->
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold small text-dark">Service Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" 
                           value="<?= e($title) ?>" required>
                </div>

                <!-- Price Field -->
                <div class="mb-3">
                    <label for="price" class="form-label fw-semibold small text-dark">Starting Price (PKR) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold text-muted">PKR</span>
                        <input type="number" step="0.01" min="0" class="form-control font-monospace" id="price" name="price" 
                               value="<?= e($price) ?>" required>
                    </div>
                </div>

                <!-- Description Field -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold small text-dark">Detailed Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="6" required><?= e($description) ?></textarea>
                </div>
            </div>

            <!-- Image Column -->
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-dark">Service Cover Image</label>
                
                <div class="image-preview-container mb-3 text-center">
                    <?php 
                        $imgSrc = !empty($currentImage) 
                            ? BASE_URL . 'uploads/services/' . e($currentImage) 
                            : 'https://images.unsplash.com/photo-1597733336794-12d05021d510?auto=format&fit=crop&w=300&q=80';
                    ?>
                    <img id="servicePreview" src="<?= $imgSrc ?>" alt="Preview">
                </div>

                <div class="mb-3">
                    <input type="file" class="form-control form-control-sm" id="image" name="image" 
                           accept="image/jpeg,image/png,image/webp" 
                           data-preview-target="#servicePreview">
                    <div class="form-text small">
                        Leave blank to keep the current image.<br>Uploading a new image will automatically delete the old file.
                    </div>
                </div>
            </div>

            <div class="col-12 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> Update Service
                </button>
                <a href="<?= BASE_URL ?>admin/services/index.php" class="btn btn-light border rounded-pill px-4">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
