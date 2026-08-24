<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Add Gallery Image (admin/gallery/create.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$pdo = getDBConnection();

$errors = [];
$caption = '';
$serviceId = '';

// Process POST request BEFORE sending any HTML output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $errors[] = 'Security token expired. Please refresh and try again.';
    } else {
        $caption   = sanitize($_POST['caption'] ?? '');
        $serviceId = !empty($_POST['service_id']) ? filter_var($_POST['service_id'], FILTER_VALIDATE_INT) : null;

        if (empty($_FILES['image']['name'])) {
            $errors[] = 'Please select an image file to upload.';
        } else {
            $uploadResult = upload_image($_FILES['image'], GALLERY_UPLOAD_PATH);
            if ($uploadResult['success']) {
                $imageFilename = $uploadResult['filename'];

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO `gallery` (`service_id`, `image`, `caption`, `created_at`) 
                        VALUES (:service_id, :image, :caption, NOW())
                    ");
                    $stmt->execute([
                        ':service_id' => $serviceId ?: null,
                        ':image'      => $imageFilename,
                        ':caption'    => $caption
                    ]);

                    set_flash('success', 'Gallery image uploaded successfully.');
                    header('Location: ' . BASE_URL . 'admin/gallery/index.php');
                    exit;
                } catch (PDOException $e) {
                    error_log('Gallery Create DB Error: ' . $e->getMessage());
                    delete_uploaded_file(GALLERY_UPLOAD_PATH . DIRECTORY_SEPARATOR . $imageFilename);
                    $errors[] = 'Database error: ' . $e->getMessage();
                }
            } else {
                $errors[] = 'Image Upload Error: ' . $uploadResult['error'];
            }
        }
    }
}

// Fetch all services for the dropdown association
$servicesList = $pdo->query("SELECT id, title FROM `services` ORDER BY title ASC")->fetchAll();

$adminPage  = 'gallery';
$adminTitle = 'Upload Gallery Image';
require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Upload Work Photo</h4>
        <p class="text-muted small mb-0">Add a project or repair photograph to the public portfolio gallery.</p>
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
    <form action="<?= BASE_URL ?>admin/gallery/create.php" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row g-4">
            <div class="col-md-7">
                <!-- Caption Field -->
                <div class="mb-3">
                    <label for="caption" class="form-label fw-semibold small text-dark">Image Caption / Short Description</label>
                    <input type="text" class="form-control" id="caption" name="caption" 
                           placeholder="e.g. CCTV installation at commercial bank in Quetta" 
                           value="<?= e($caption) ?>">
                    <div class="form-text small">A brief title or explanation shown beneath the photo.</div>
                </div>

                <!-- Service Association (Foreign Key) -->
                <div class="mb-3">
                    <label for="service_id" class="form-label fw-semibold small text-dark">Associated Service Category (Foreign Key)</label>
                    <select class="form-select" id="service_id" name="service_id">
                        <option value="">-- General / No Specific Service --</option>
                        <?php foreach ($servicesList as $svc): ?>
                            <option value="<?= e($svc['id']) ?>" <?= ($serviceId == $svc['id']) ? 'selected' : '' ?>>
                                <?= e($svc['title']) ?> (ID: #<?= e($svc['id']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text small">Connects this image to a service record via foreign key relationship.</div>
                </div>
            </div>

            <!-- Image Upload Column -->
            <div class="col-md-5">
                <label class="form-label fw-semibold small text-dark">Photo File <span class="text-danger">*</span></label>
                <div class="image-preview-container mb-3 text-center" style="max-width: 100%;">
                    <img id="galleryPreview" src="" alt="Preview" class="d-none">
                    <div id="previewPlaceholder" class="text-muted small p-3">
                        <i class="bi bi-card-image fs-1 d-block text-secondary mb-2"></i>
                        <span>Select a photo file below to preview</span>
                    </div>
                </div>

                <div class="mb-3">
                    <input type="file" class="form-control form-control-sm" id="image" name="image" 
                           accept="image/jpeg,image/png,image/webp" 
                           data-preview-target="#galleryPreview" required>
                    <div class="form-text small">Formats: JPG, JPEG, PNG, WEBP (Max 5MB).</div>
                </div>
            </div>

            <div class="col-12 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                    <i class="bi bi-cloud-arrow-up me-1"></i> Upload to Gallery
                </button>
                <a href="<?= BASE_URL ?>admin/gallery/index.php" class="btn btn-light border rounded-pill px-4">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
