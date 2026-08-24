<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Edit Gallery Image (admin/gallery/edit.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$pdo = getDBConnection();
$galleryId = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);

if (!$galleryId) {
    set_flash('danger', 'Invalid gallery item ID provided.');
    header('Location: ' . BASE_URL . 'admin/gallery/index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM `gallery` WHERE `id` = :id LIMIT 1");
$stmt->execute([':id' => $galleryId]);
$item = $stmt->fetch();

if (!$item) {
    set_flash('danger', 'Gallery item not found in database.');
    header('Location: ' . BASE_URL . 'admin/gallery/index.php');
    exit;
}

$errors = [];
$caption      = $item['caption'];
$serviceId    = $item['service_id'];
$currentImage = $item['image'];

// Process POST request BEFORE sending any HTML output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $errors[] = 'Security token expired. Please refresh and try again.';
    } else {
        $caption   = sanitize($_POST['caption'] ?? '');
        $serviceId = !empty($_POST['service_id']) ? filter_var($_POST['service_id'], FILTER_VALIDATE_INT) : null;
        $newFilename = $currentImage;

        // Process replacement image if uploaded
        if (!empty($_FILES['image']['name'])) {
            $uploadResult = upload_image($_FILES['image'], GALLERY_UPLOAD_PATH);
            if ($uploadResult['success']) {
                $newFilename = $uploadResult['filename'];
                // Delete old image file
                if (!empty($currentImage)) {
                    delete_uploaded_file(GALLERY_UPLOAD_PATH . DIRECTORY_SEPARATOR . $currentImage);
                }
            } else {
                $errors[] = 'Image Upload Error: ' . $uploadResult['error'];
            }
        }

        if (empty($errors)) {
            try {
                $updateStmt = $pdo->prepare("
                    UPDATE `gallery` 
                    SET `service_id` = :service_id, `image` = :image, `caption` = :caption 
                    WHERE `id` = :id
                ");
                $updateStmt->execute([
                    ':service_id' => $serviceId ?: null,
                    ':image'      => $newFilename,
                    ':caption'    => $caption,
                    ':id'         => $galleryId
                ]);

                set_flash('success', 'Gallery item updated successfully.');
                header('Location: ' . BASE_URL . 'admin/gallery/index.php');
                exit;
            } catch (PDOException $e) {
                error_log('Update Gallery DB Error: ' . $e->getMessage());
                $errors[] = 'Database update error: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all services for dropdown
$servicesList = $pdo->query("SELECT id, title FROM `services` ORDER BY title ASC")->fetchAll();

$adminPage  = 'gallery';
$adminTitle = 'Edit Gallery Image';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Gallery Image #<?= e($item['id']) ?></h4>
        <p class="text-muted small mb-0">Modify photo caption or update service categorization.</p>
    </div>
    <a href="<?= BASE_URL ?>admin/gallery/index.php" class="btn btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Back to Gallery
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
    <form action="<?= BASE_URL ?>admin/gallery/edit.php?id=<?= e($galleryId) ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row g-4">
            <div class="col-md-7">
                <!-- Caption Field -->
                <div class="mb-3">
                    <label for="caption" class="form-label fw-semibold small text-dark">Image Caption</label>
                    <input type="text" class="form-control" id="caption" name="caption" 
                           value="<?= e($caption) ?>">
                </div>

                <!-- Service Association -->
                <div class="mb-3">
                    <label for="service_id" class="form-label fw-semibold small text-dark">Associated Service (Foreign Key)</label>
                    <select class="form-select" id="service_id" name="service_id">
                        <option value="">-- General / No Specific Service --</option>
                        <?php foreach ($servicesList as $svc): ?>
                            <option value="<?= e($svc['id']) ?>" <?= ($serviceId == $svc['id']) ? 'selected' : '' ?>>
                                <?= e($svc['title']) ?> (ID: #<?= e($svc['id']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Image Column -->
            <div class="col-md-5">
                <label class="form-label fw-semibold small text-dark">Current Image</label>
                
                <div class="image-preview-container mb-3 text-center" style="max-width: 100%;">
                    <?php 
                        $imgSrc = !empty($currentImage) 
                            ? BASE_URL . 'uploads/gallery/' . e($currentImage) 
                            : 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=300&q=80';
                    ?>
                    <img id="galleryPreview" src="<?= $imgSrc ?>" alt="Preview">
                </div>

                <div class="mb-3">
                    <input type="file" class="form-control form-control-sm" id="image" name="image" 
                           accept="image/jpeg,image/png,image/webp" 
                           data-preview-target="#galleryPreview">
                    <div class="form-text small">
                        Leave blank to keep current image. Uploading a new file will automatically delete the old file.
                    </div>
                </div>
            </div>

            <div class="col-12 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> Update Gallery Image
                </button>
                <a href="<?= BASE_URL ?>admin/gallery/index.php" class="btn btn-light border rounded-pill px-4">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
