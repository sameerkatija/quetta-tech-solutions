<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Admin Dashboard (admin/dashboard.php)
 * ==============================================================================
 */

$adminPage  = 'dashboard';
$adminTitle = 'Overview Dashboard';

require_once __DIR__ . '/includes/admin_header.php';

$pdo = getDBConnection();

// Fetch Metric Counters
$totalServices = (int)$pdo->query("SELECT COUNT(*) FROM `services`")->fetchColumn();
$totalGallery  = (int)$pdo->query("SELECT COUNT(*) FROM `gallery`")->fetchColumn();
$totalMessages = (int)$pdo->query("SELECT COUNT(*) FROM `contact_messages`")->fetchColumn();

// Fetch Recent Contact Messages (5 latest)
$recentMessagesStmt = $pdo->query("SELECT * FROM `contact_messages` ORDER BY `id` DESC LIMIT 5");
$recentMessages = $recentMessagesStmt->fetchAll();

// Fetch Recent Services (4 latest)
$recentServicesStmt = $pdo->query("SELECT * FROM `services` ORDER BY `id` DESC LIMIT 4");
$recentServices = $recentServicesStmt->fetchAll();
?>

<!-- Metric Summary Cards -->
<div class="row g-4 mb-4">
    <!-- Total Services Card -->
    <div class="col-sm-6 col-xl-4">
        <div class="metric-card d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold text-uppercase letter-spacing d-block mb-1">Total Services</span>
                <div class="metric-value"><?= number_format($totalServices) ?></div>
                <a href="<?= BASE_URL ?>admin/services/index.php" class="small text-decoration-none text-info fw-semibold mt-2 d-inline-block">
                    Manage Services <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="metric-icon-box bg-primary-subtle text-primary">
                <i class="bi bi-tools"></i>
            </div>
        </div>
    </div>

    <!-- Total Gallery Images Card -->
    <div class="col-sm-6 col-xl-4">
        <div class="metric-card d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold text-uppercase letter-spacing d-block mb-1">Gallery Images</span>
                <div class="metric-value"><?= number_format($totalGallery) ?></div>
                <a href="<?= BASE_URL ?>admin/gallery/index.php" class="small text-decoration-none text-info fw-semibold mt-2 d-inline-block">
                    Manage Gallery <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="metric-icon-box bg-success-subtle text-success">
                <i class="bi bi-images"></i>
            </div>
        </div>
    </div>

    <!-- Total Inquiries Card -->
    <div class="col-sm-6 col-xl-4">
        <div class="metric-card d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold text-uppercase letter-spacing d-block mb-1">Customer Inquiries</span>
                <div class="metric-value"><?= number_format($totalMessages) ?></div>
                <a href="<?= BASE_URL ?>admin/messages/index.php" class="small text-decoration-none text-info fw-semibold mt-2 d-inline-block">
                    View All Inquiries <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="metric-icon-box bg-warning-subtle text-warning">
                <i class="bi bi-envelope-paper"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Strip -->
<div class="card border-0 shadow-sm rounded-4 mb-4 p-3 bg-white">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-dark text-cyan p-2"><i class="bi bi-lightning-charge-fill"></i></span>
            <span class="fw-bold text-dark">Quick Administration Shortcuts:</span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= BASE_URL ?>admin/services/create.php" class="btn btn-sm btn-primary rounded-pill px-3">
                <i class="bi bi-plus-circle me-1"></i> Add New Service
            </a>
            <a href="<?= BASE_URL ?>admin/gallery/create.php" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                <i class="bi bi-upload me-1"></i> Upload Gallery Image
            </a>
            <a href="<?= BASE_URL ?>admin/messages/index.php" class="btn btn-sm btn-outline-info rounded-pill px-3">
                <i class="bi bi-inbox me-1"></i> View Messages (<?= $totalMessages ?>)
            </a>
            <a href="<?= BASE_URL ?>index.php" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3">
                <i class="bi bi-box-arrow-up-right me-1"></i> Preview Live Site
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Recent Messages -->
    <div class="col-xl-7">
        <div class="admin-table-card">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Recent Customer Inquiries</h6>
                    <span class="text-muted small">Latest inquiries submitted from the website contact form</span>
                </div>
                <a href="<?= BASE_URL ?>admin/messages/index.php" class="btn btn-sm btn-light border rounded-pill">
                    View All
                </a>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Subject</th>
                            <th>Received</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentMessages)): ?>
                            <?php foreach ($recentMessages as $msg): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?= e($msg['name']) ?></div>
                                        <span class="text-muted small"><?= e($msg['email']) ?></span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-truncate d-inline-block" style="max-width: 220px;">
                                            <?= e($msg['subject']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-muted border">
                                            <?= time_ago($msg['created_at']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= BASE_URL ?>admin/messages/index.php#msg-<?= e($msg['id']) ?>" class="btn btn-sm btn-light border px-2 py-1" title="View Detail">
                                            <i class="bi bi-eye text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No messages received yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Services & System Info -->
    <div class="col-xl-5">
        <!-- Recent Services Widget -->
        <div class="admin-table-card mb-4">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Active Services</h6>
                    <span class="text-muted small">Quick view of catalog pricing</span>
                </div>
                <a href="<?= BASE_URL ?>admin/services/create.php" class="btn btn-sm btn-primary rounded-pill">
                    <i class="bi bi-plus"></i> New
                </a>
            </div>

            <ul class="list-group list-group-flush">
                <?php if (!empty($recentServices)): ?>
                    <?php foreach ($recentServices as $svc): ?>
                        <li class="list-group-item p-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <?php 
                                    $thumb = !empty($svc['image']) ? BASE_URL . 'uploads/services/' . e($svc['image']) : 'https://images.unsplash.com/photo-1597733336794-12d05021d510?auto=format&fit=crop&w=100&q=80';
                                ?>
                                <img src="<?= $thumb ?>" alt="" class="thumbnail-preview">
                                <div>
                                    <span class="fw-bold text-dark d-block small"><?= e($svc['title']) ?></span>
                                    <span class="text-cyan fw-bold small"><?= format_price($svc['price']) ?></span>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>admin/services/edit.php?id=<?= e($svc['id']) ?>" class="btn btn-sm btn-light border" title="Edit Service">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item p-4 text-center text-muted">No services added yet.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- System & Environment Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-server text-info me-2"></i> System Diagnostics</h6>
            <div class="small">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">PHP Version:</span>
                    <span class="fw-semibold font-monospace"><?= PHP_VERSION ?></span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Database Server:</span>
                    <span class="fw-semibold font-monospace">MySQL (PDO)</span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Session Handler:</span>
                    <span class="badge bg-success-subtle text-success">Active & Encrypted</span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">File Uploads:</span>
                    <span class="badge bg-success-subtle text-success">Enabled (Max 5MB)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
