<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Admin Gallery Management (admin/gallery/index.php)
 * ==============================================================================
 */

$adminPage  = 'gallery';
$adminTitle = 'Manage Gallery';

require_once __DIR__ . '/../includes/admin_header.php';

$pdo = getDBConnection();
$stmt = $pdo->query("
    SELECT g.*, s.title AS service_title 
    FROM `gallery` g 
    LEFT JOIN `services` s ON g.service_id = s.id 
    ORDER BY g.id DESC
");
$galleryItems = $stmt->fetchAll();
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h4 class="fw-bold mb-1">Work Gallery & Visual Portfolio</h4>
        <p class="text-muted small mb-0">Upload and curate photographs of repaired equipment, server racks, and CCTV installations.</p>
    </div>
    <a href="<?= BASE_URL ?>admin/gallery/create.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
        <i class="bi bi-cloud-upload-fill me-2"></i> Upload New Image
    </a>
</div>

<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th style="width: 100px;">Photo</th>
                    <th>Caption / Project Summary</th>
                    <th>Associated Service (FK)</th>
                    <th>Uploaded Date</th>
                    <th class="text-end" style="width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($galleryItems)): ?>
                    <?php foreach ($galleryItems as $item): ?>
                        <?php 
                            $imgSrc = !empty($item['image']) 
                                ? BASE_URL . 'uploads/gallery/' . e($item['image']) 
                                : 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=120&q=80';
                        ?>
                        <tr>
                            <td class="font-monospace text-muted small">#<?= e($item['id']) ?></td>
                            <td>
                                <img src="<?= $imgSrc ?>" 
                                     alt="<?= e($item['caption']) ?>" 
                                     class="thumbnail-preview" 
                                     style="width: 70px; height: 50px;">
                            </td>
                            <td>
                                <strong class="text-dark d-block"><?= e($item['caption'] ?: 'No caption provided') ?></strong>
                                <span class="small text-muted font-monospace"><?= e($item['image']) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($item['service_title'])): ?>
                                    <span class="badge bg-info-subtle text-dark border">
                                        <i class="bi bi-tag-fill text-info me-1"></i><?= e($item['service_title']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border">General / Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted">
                                <?= date('M d, Y', strtotime($item['created_at'])) ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= BASE_URL ?>admin/gallery/edit.php?id=<?= e($item['id']) ?>" 
                                       class="btn btn-outline-primary" 
                                       title="Edit Image Details">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteGalleryModal<?= e($item['id']) ?>" 
                                            title="Delete Image">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade text-start" id="deleteGalleryModal<?= e($item['id']) ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-danger">
                                                    <i class="bi bi-exclamation-octagon me-2"></i> Confirm Image Deletion
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-4">
                                                <p class="mb-2">Are you sure you want to permanently delete this gallery item?</p>
                                                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                                                    <img src="<?= $imgSrc ?>" alt="" style="width:60px; height:60px; object-fit:cover;" class="rounded border">
                                                    <div>
                                                        <strong class="text-dark d-block"><?= e($item['caption'] ?: 'Gallery Photo') ?></strong>
                                                        <span class="small text-muted"><?= e($item['service_title'] ?? 'General') ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                                                <form action="<?= BASE_URL ?>admin/gallery/delete.php" method="POST" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                                                        <i class="bi bi-trash me-1"></i> Delete File
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
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-images fs-2 d-block mb-2 text-secondary"></i>
                            No gallery images uploaded. Click "Upload New Image" above.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
