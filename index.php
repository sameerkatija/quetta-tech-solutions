<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Home Page (index.php)
 * ==============================================================================
 */

$pageTitle = 'Home';
$pageDescription = 'Leading Computer & IT Services provider in Quetta. Hardware repair, laptop diagnostics, CCTV, network cabling, and software development.';

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

// Fetch up to 6 featured services dynamically from MySQL using PDO
$pdo = getDBConnection();
$stmt = $pdo->query("SELECT * FROM `services` ORDER BY `id` ASC LIMIT 6");
$featuredServices = $stmt->fetchAll();

// Fetch sample gallery items dynamically
$galleryStmt = $pdo->query("
    SELECT g.*, s.title AS service_title 
    FROM `gallery` g 
    LEFT JOIN `services` s ON g.service_id = s.id 
    ORDER BY g.id DESC LIMIT 4
");
$recentGallery = $galleryStmt->fetchAll();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="hero-badge mb-3">
                    <i class="bi bi-shield-check me-2"></i> Trusted IT Partner in Quetta Since 2018
                </div>
                <h1 class="hero-title mb-3">
                    Fast, Reliable <span class="text-cyan">Computer & IT Services</span> for Home & Business.
                </h1>
                <p class="lead text-white-50 mb-4 lh-base">
                    From complex chip-level laptop repairs and high-speed enterprise networking to modern custom web applications, Quetta Tech Solutions delivers certified technical expertise with transparent pricing.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>services.php" class="btn btn-cyan btn-lg px-4 fw-semibold rounded-pill">
                        <i class="bi bi-grid-fill me-2"></i> Explore Services
                    </a>
                    <a href="<?= BASE_URL ?>contact.php" class="btn btn-outline-cyan btn-lg px-4 fw-semibold rounded-pill">
                        <i class="bi bi-headset me-2"></i> Get Free Quote
                    </a>
                </div>

                <!-- Trust Points -->
                <div class="d-flex flex-wrap gap-4 mt-5 pt-3 border-top border-white border-opacity-10 text-white-50 small">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-cyan"></i>
                        <span>Same-Day Diagnostics</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-cyan"></i>
                        <span>30-Day Service Warranty</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-cyan"></i>
                        <span>Certified Hardware Engineers</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hero-img-card">
                    <img src="<?= BASE_URL ?>assets/images/hero_tech.jpg" 
                         onerror="this.src='https://images.unsplash.com/photo-1588508065123-287b28e013da?auto=format&fit=crop&w=800&q=80'" 
                         alt="IT Hardware Engineering" 
                         class="img-fluid w-100 rounded-3" 
                         style="min-height: 380px; object-fit: cover;">
                    <div class="hero-floating-stat text-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="brand-icon-box bg-info text-dark">
                                <i class="bi bi-tools fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">1,800+ Repairs Completed</h6>
                                <span class="small text-white-50">99.4% Client Satisfaction</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Flash Message Display (if redirected with message) -->
<div class="container mt-4">
    <?= display_flash() ?>
</div>

<!-- Business Stats Counter Section -->
<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="stat-box h-100">
                    <div class="stat-number" data-count="1850" data-suffix="+">1,850+</div>
                    <p class="text-muted small fw-semibold mb-0 mt-1">Computers Repaired</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box h-100">
                    <div class="stat-number" data-count="120" data-suffix="+">120+</div>
                    <p class="text-muted small fw-semibold mb-0 mt-1">Corporate Networks</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box h-100">
                    <div class="stat-number" data-count="98" data-suffix="%">98%</div>
                    <p class="text-muted small fw-semibold mb-0 mt-1">First-Time Fix Rate</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box h-100">
                    <div class="stat-number" data-count="6" data-suffix="+">6+</div>
                    <p class="text-muted small fw-semibold mb-0 mt-1">Years in Quetta</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Dynamic Services Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase px-3 py-2 rounded-pill">What We Do</span>
            <h2 class="fw-bold mt-2 mb-3">Our Core IT Services</h2>
            <p class="text-muted">
                From emergency hardware troubleshooting to end-to-end enterprise digital infrastructure, we offer comprehensive IT services tailored for individuals and businesses in Quetta.
            </p>
        </div>

        <div class="row g-4">
            <?php if (!empty($featuredServices)): ?>
                <?php foreach ($featuredServices as $service): ?>
                    <?php 
                        $serviceImg = !empty($service['image']) 
                            ? BASE_URL . 'uploads/services/' . e($service['image']) 
                            : 'https://images.unsplash.com/photo-1597733336794-12d05021d510?auto=format&fit=crop&w=600&q=80';
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-img-wrapper">
                                <img src="<?= $serviceImg ?>" 
                                     onerror="this.src='https://images.unsplash.com/photo-1597733336794-12d05021d510?auto=format&fit=crop&w=600&q=80'" 
                                     alt="<?= e($service['title']) ?>">
                                <span class="service-price-badge">
                                    Starting <?= format_price($service['price']) ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark mb-2"><?= e($service['title']) ?></h5>
                                <p class="card-text text-muted small flex-grow-1">
                                    <?= e(mb_strimwidth($service['description'], 0, 130, '...')) ?>
                                </p>
                                <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                                    <a href="<?= BASE_URL ?>services.php" class="text-cyan fw-semibold text-decoration-none small">
                                        View Details <i class="bi bi-arrow-right"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>contact.php?service=<?= urlencode($service['title']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No services added yet. Please check back soon.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-5">
            <a href="<?= BASE_URL ?>services.php" class="btn btn-outline-dark px-4 py-2 rounded-pill fw-semibold">
                Browse All Services & Transparent Pricing <i class="bi bi-chevron-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-success-subtle text-success fw-bold text-uppercase px-3 py-2 rounded-pill mb-2">Our Advantage</span>
                <h2 class="fw-bold mb-3">Why Quetta Tech Solutions Is The Preferred Choice</h2>
                <p class="text-muted mb-4 lh-base">
                    We understand how critical computers and networking are to your everyday productivity and business operations. Our engineering lab is equipped with modern diagnostic tools to ensure rapid, durable results.
                </p>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="feature-card p-3">
                            <div class="feature-icon-box">
                                <i class="bi bi-cpu"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Certified Technicians</h6>
                            <p class="text-muted small mb-0">Hardware-level specialists trained in motherboard diagnostics & chip replacement.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-card p-3">
                            <div class="feature-icon-box">
                                <i class="bi bi-lightning-charge"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Fast Turnaround</h6>
                            <p class="text-muted small mb-0">Most routine laptop screen and OS repairs completed within 24 to 48 hours.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-card p-3">
                            <div class="feature-icon-box">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Honest Pricing</h6>
                            <p class="text-muted small mb-0">No hidden charges or surprise diagnostics fees. Upfront quotes before we work.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-card p-3">
                            <div class="feature-icon-box">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Service Warranty</h6>
                            <p class="text-muted small mb-0">Full 30-day parts and labor warranty on all replacements and repair services.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bg-dark-navy text-white p-4 p-md-5 rounded-4 shadow-lg position-relative">
                    <h3 class="fw-bold mb-3">Our Work Process</h3>
                    <p class="text-white-50 small mb-4">A structured, hassle-free 4-step repair and deployment workflow.</p>
                    
                    <div class="d-flex gap-3 mb-4">
                        <div class="brand-icon-box bg-info text-dark fw-bold">1</div>
                        <div>
                            <h6 class="fw-bold mb-1 text-white">Free Initial Diagnostics</h6>
                            <p class="text-white-50 small mb-0">Bring your device to our Zarghoon Road center or schedule a technician site visit.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-4">
                        <div class="brand-icon-box bg-info text-dark fw-bold">2</div>
                        <div>
                            <h6 class="fw-bold mb-1 text-white">Transparent Quotation</h6>
                            <p class="text-white-50 small mb-0">We inspect the components and share a crystal-clear parts and labor price estimate.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-4">
                        <div class="brand-icon-box bg-info text-dark fw-bold">3</div>
                        <div>
                            <h6 class="fw-bold mb-1 text-white">Precision Engineering</h6>
                            <p class="text-white-50 small mb-0">Certified repairs using genuine parts, followed by thorough stress testing.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="brand-icon-box bg-info text-dark fw-bold">4</div>
                        <div>
                            <h6 class="fw-bold mb-1 text-white">Delivery with Warranty</h6>
                            <p class="text-white-50 small mb-0">Collect your fully restored computer along with a 30-day warranty slip.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="cta-box">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <span class="badge bg-info text-dark fw-bold px-3 py-1 rounded-pill mb-2">Need Immediate Assistance?</span>
                    <h3 class="fw-bold mb-2">Have a Broken Computer or Require Network Setup in Quetta?</h3>
                    <p class="text-white-50 mb-0">Speak directly with our chief technician today. Quick turnaround and free initial consultation.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="tel:<?= urlencode(APP_PHONE) ?>" class="btn btn-cyan btn-lg px-4 fw-bold rounded-pill mb-2 mb-sm-0 me-sm-2">
                        <i class="bi bi-telephone-fill me-1"></i> Call Now
                    </a>
                    <a href="<?= BASE_URL ?>contact.php" class="btn btn-outline-light btn-lg px-4 fw-bold rounded-pill">
                        Send Message
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
