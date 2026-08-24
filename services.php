<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Services & Pricing Catalog (services.php)
 * ==============================================================================
 */

$pageTitle = 'Services & Pricing';
$pageDescription = 'Browse all IT services offered by Quetta Tech Solutions including hardware repair, laptop servicing, network cabling, CCTV, and custom web software with transparent pricing.';

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Fetch all services from database
$stmt = $pdo->query("
    SELECT s.*, u.username AS added_by 
    FROM `services` s 
    LEFT JOIN `users` u ON s.user_id = u.id 
    ORDER BY s.id ASC
");
$services = $stmt->fetchAll();

// Fetch all gallery items with their related service title
$galleryStmt = $pdo->query("
    SELECT g.*, s.title AS service_title 
    FROM `gallery` g 
    LEFT JOIN `services` s ON g.service_id = s.id 
    ORDER BY g.id DESC
");
$galleryItems = $galleryStmt->fetchAll();
?>

<!-- Page Header / Hero -->
<section class="page-hero">
    <div class="container text-center">
        <span class="badge bg-info text-dark fw-bold text-uppercase px-3 py-1 rounded-pill mb-2">Our Capabilities</span>
        <h1 class="fw-bold mb-2">Computer & IT Services in Quetta</h1>
        <p class="text-white-50 lead max-w-700 mx-auto mb-0">
            Transparent pricing, certified hardware technicians, genuine parts, and standard 30-day warranty on all repairs.
        </p>
    </div>
</section>

<!-- Services Grid Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Active Service Catalog</h3>
                <p class="text-muted small mb-0">All prices shown are starting estimates subject to component replacement costs.</p>
            </div>
            <div>
                <span class="badge bg-dark text-cyan px-3 py-2 rounded-pill font-monospace">
                    <?= count($services) ?> Services Available
                </span>
            </div>
        </div>

        <div class="row g-4">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <?php 
                        $serviceImg = !empty($service['image']) 
                            ? BASE_URL . 'uploads/services/' . e($service['image']) 
                            : 'https://images.unsplash.com/photo-1597733336794-12d05021d510?auto=format&fit=crop&w=600&q=80';
                    ?>
                    <div class="col-lg-4 col-md-6" id="service-<?= e($service['id']) ?>">
                        <div class="service-card shadow-sm h-100">
                            <div class="service-img-wrapper">
                                <img src="<?= $serviceImg ?>" 
                                     onerror="this.src='https://images.unsplash.com/photo-1597733336794-12d05021d510?auto=format&fit=crop&w=600&q=80'" 
                                     alt="<?= e($service['title']) ?>">
                                <span class="service-price-badge">
                                    Starting <?= format_price($service['price']) ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold text-dark mb-0"><?= e($service['title']) ?></h5>
                                </div>
                                
                                <p class="card-text text-muted small flex-grow-1 lh-base">
                                    <?= nl2br(e($service['description'])) ?>
                                </p>

                                <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-3">
                                    <span class="text-muted small font-monospace">
                                        <i class="bi bi-shield-check text-success"></i> Warranty Included
                                    </span>
                                    <a href="<?= BASE_URL ?>contact.php?service=<?= urlencode($service['title']) ?>" class="btn btn-sm btn-cyan text-dark fw-bold rounded-pill px-3">
                                        <i class="bi bi-calendar2-check me-1"></i> Book Service
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="card p-5 border-0 shadow-sm rounded-4">
                        <i class="bi bi-exclamation-circle text-muted fs-1 mb-3"></i>
                        <h5>No Services Currently Listed</h5>
                        <p class="text-muted">Our service list is being updated. Please contact our team directly for custom quotes.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Project Work Gallery Showcase -->
<section class="py-5 bg-white border-top">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge bg-info-subtle text-info fw-bold text-uppercase px-3 py-1 rounded-pill">Visual Portfolio</span>
            <h2 class="fw-bold mt-2">Work In Progress & Completed Projects</h2>
            <p class="text-muted">Real photographs from our hardware workbench, server rack cabling, and CCTV camera deployments across Quetta.</p>
        </div>

        <div class="row g-4">
            <?php if (!empty($galleryItems)): ?>
                <?php foreach ($galleryItems as $item): ?>
                    <?php 
                        $galleryImg = !empty($item['image']) 
                            ? BASE_URL . 'uploads/gallery/' . e($item['image']) 
                            : 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80';
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="gallery-card">
                            <div class="gallery-img-container">
                                <img src="<?= $galleryImg ?>" 
                                     onerror="this.src='https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80'" 
                                     alt="<?= e($item['caption'] ?: 'Work Gallery') ?>">
                                <div class="gallery-overlay">
                                    <?php if (!empty($item['service_title'])): ?>
                                        <span class="badge bg-info text-dark mb-1 small fw-bold">
                                            <?= e($item['service_title']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <p class="mb-0 small text-white fw-semibold">
                                        <?= e($item['caption'] ?: 'Completed IT Project') ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-4">
                    <p>No gallery images uploaded yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5 bg-dark-navy text-white text-center">
    <div class="container">
        <h3 class="fw-bold mb-2">Need a Custom Hardware or Corporate IT Package?</h3>
        <p class="text-white-50 max-w-700 mx-auto mb-4">
            We provide on-site service level agreements (SLAs), enterprise fiber routing, and bulk desktop servicing for organizations in Balochistan.
        </p>
        <a href="<?= BASE_URL ?>contact.php" class="btn btn-cyan px-5 py-3 fw-bold rounded-pill">
            Request Business Proposal <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
