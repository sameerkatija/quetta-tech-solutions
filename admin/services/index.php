<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Admin Services Management (admin/services/index.php)
 * ==============================================================================
 */

$adminPage  = 'services';
$adminTitle = 'Manage Services';

require_once __DIR__ . '/../includes/admin_header.php';

$pdo = getDBConnection();
$stmt = $pdo->query("
    SELECT s.*, u.username AS added_by 
    FROM `services` s 
    LEFT JOIN `users` u ON s.user_id = u.id 
    ORDER BY s.id DESC
");
$services = $stmt->fetchAll();
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h4 class="fw-bold mb-1">Services Catalog</h4>
        <p class="text-muted small mb-0">Add, edit, and maintain all computer repair and IT service listings.</p>
    </div>
    <a href="<?= BASE_URL ?>admin/services/create.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
        <i class="bi bi-plus-circle-fill me-2"></i> Add New Service
    </a>
</div>

<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th style="width: 90px;">Image</th>
                    <th>Service Title</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Added By</th>
                    <th>Date</th>
                    <th class="text-end" style="width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($services)): ?>
                    <?php foreach ($services as $service): ?>
                        <?php 
                            $imgSrc = !empty($service['image']) 
                                ? BASE_URL . 'uploads/services/' . e($service['image']) 
                                : 'https://images.unsplash.com/photo-1597733336794-12d05021d510?auto=format&fit=crop&w=100&q=80';
                        ?>
                        <tr>
                            <td class="font-monospace text-muted small">#<?= e($service['id']) ?></td>
                            <td>
                                <img src="<?= $imgSrc ?>" 
                                     alt="<?= e($service['title']) ?>" 
                                     class="thumbnail-preview">
                            </td>
                            <td>
                                <strong class="text-dark d-block"><?= e($service['title']) ?></strong>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace fw-bold">
                                    <?= format_price($service['price']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="small text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                    <?= e($service['description']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="small badge bg-secondary-subtle text-secondary">
                                    <i class="bi bi-person me-1"></i><?= e($service['added_by'] ?? 'admin') ?>
                                </span>
                            </td>
                            <td class="small text-muted">
                                <?= date('M d, Y', strtotime($service['created_at'])) ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= BASE_URL ?>admin/services/edit.php?id=<?= e($service['id']) ?>" 
                                       class="btn btn-outline-primary" 
                                       title="Edit Service">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal<?= e($service['id']) ?>" 
                                            title="Delete Service">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade text-start" id="deleteModal<?= e($service['id']) ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-danger">
                                                    <i class="bi bi-exclamation-octagon me-2"></i> Confirm Deletion
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-4">
                                                <p class="mb-1">Are you sure you want to permanently delete this service?</p>
                                                <div class="p-3 bg-light rounded-3 my-2">
                                                    <strong class="text-dark"><?= e($service['title']) ?></strong>
                                                    <div class="small text-muted"><?= format_price($service['price']) ?></div>
                                                </div>
                                                <p class="small text-danger mb-0">
                                                    <i class="bi bi-info-circle me-1"></i> The uploaded cover image will be removed from disk.
                                                </p>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                                                <form action="<?= BASE_URL ?>admin/services/delete.php" method="POST" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="id" value="<?= e($service['id']) ?>">
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                                                        <i class="bi bi-trash me-1"></i> Delete Permanently
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            No services found in database. Click "Add New Service" above to create one.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
