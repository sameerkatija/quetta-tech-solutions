<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - About Us Page (about.php)
 * ==============================================================================
 */

$pageTitle = 'About Us';
$pageDescription = 'Learn more about Quetta Tech Solutions, our mission, certified IT engineering team, and our commitment to dependable technology services in Balochistan.';

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header / Hero -->
<section class="page-hero">
    <div class="container text-center">
        <span class="badge bg-info text-dark fw-bold text-uppercase px-3 py-1 rounded-pill mb-2">Who We Are</span>
        <h1 class="fw-bold mb-2">About Quetta Tech Solutions</h1>
        <p class="text-white-50 lead max-w-700 mx-auto mb-0">
            Empowering businesses, institutions, and individuals across Balochistan with enterprise-grade computer repairs, networking, and digital solutions.
        </p>
    </div>
</section>

<!-- Company Origin Story -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded-pill mb-2">Our Story</span>
                <h2 class="fw-bold mb-3">Pioneering Reliable IT Support in the Heart of Quetta</h2>
                <p class="text-muted lh-base mb-3">
                    Founded in 2018 at Zarghoon Road, <strong>Quetta Tech Solutions</strong> started with a straightforward vision: to bring honest, high-caliber, and certified computer hardware engineering to Quetta. In a market often hindered by temporary fixes and ambiguous pricing, we set out to establish a gold standard of technical transparency.
                </p>
                <p class="text-muted lh-base mb-4">
                    Over the past 6+ years, our workshop has expanded into a full-service IT infrastructure solutions provider. We have successfully repaired over 1,800 computers, deployed enterprise networks for educational campuses and commercial plazas, and developed custom software applications for emerging local businesses.
                </p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="border-start border-3 border-info ps-3">
                            <h4 class="fw-bold mb-1 text-dark">6+ Years</h4>
                            <p class="text-muted small mb-0">Local Industry Experience</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border-start border-3 border-success ps-3">
                            <h4 class="fw-bold mb-1 text-dark">100% Genuine</h4>
                            <p class="text-muted small mb-0">Tested Replacement Parts</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <img src="<?= BASE_URL ?>assets/images/about_lab.jpg" 
                             onerror="this.src='https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80'" 
                             alt="Engineering Bench" 
                             class="img-fluid rounded-3 shadow-sm w-100 h-100 object-fit-cover">
                    </div>
                    <div class="col-6">
                        <img src="<?= BASE_URL ?>assets/images/about_server.jpg" 
                             onerror="this.src='https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=600&q=80'" 
                             alt="Network Infrastructure" 
                             class="img-fluid rounded-3 shadow-sm w-100 h-100 object-fit-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission, Vision & Core Values -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase px-3 py-1 rounded-pill">Guiding Principles</span>
            <h2 class="fw-bold mt-2">Mission, Vision & Core Values</h2>
            <p class="text-muted">The core philosophy that guides every repair, network deployment, and customer interaction.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                    <div class="feature-icon-box bg-primary-subtle text-primary mb-3">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Our Mission</h5>
                    <p class="text-muted small mb-0 lh-base">
                        To provide dependable, cost-effective, and rapid IT hardware repair, networking, and software services to businesses and residents in Quetta, ensuring uninterrupted digital productivity.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                    <div class="feature-icon-box bg-info-subtle text-info mb-3">
                        <i class="bi bi-eye"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Our Vision</h5>
                    <p class="text-muted small mb-0 lh-base">
                        To become Balochistan’s foremost center of excellence in computer hardware engineering, enterprise networking, and modern business technology consulting.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                    <div class="feature-icon-box bg-success-subtle text-success mb-3">
                        <i class="bi bi-gem"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Core Values</h5>
                    <ul class="list-unstyled text-muted small mb-0 lh-lg">
                        <li><i class="bi bi-check2 text-success me-1"></i> <strong>Integrity:</strong> Upfront diagnostic reports and transparent billing.</li>
                        <li><i class="bi bi-check2 text-success me-1"></i> <strong>Quality:</strong> Strict quality control & component warranty.</li>
                        <li><i class="bi bi-check2 text-success me-1"></i> <strong>Speed:</strong> Fast turnaround times without compromising accuracy.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase px-3 py-1 rounded-pill">Our Experts</span>
            <h2 class="fw-bold mt-2">Meet Our Certified Engineering Team</h2>
            <p class="text-muted">Dedicated IT professionals with specialized experience in micro-soldering, fiber optics, and system architecture.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Team Member 1 -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 text-center overflow-hidden h-100">
                    <div class="pt-4 px-4">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80" 
                             alt="Engr. Samiullah Khan" 
                             class="rounded-circle img-fluid mx-auto shadow-sm" 
                             style="width: 130px; height: 130px; object-fit: cover;">
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">Engr. Samiullah Khan</h5>
                        <p class="text-cyan small fw-semibold mb-2">Lead Hardware Specialist</p>
                        <p class="text-muted small">8+ years specializing in laptop motherboard chip-level repair and data recovery.</p>
                    </div>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 text-center overflow-hidden h-100">
                    <div class="pt-4 px-4">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80" 
                             alt="Bilal Mengal" 
                             class="rounded-circle img-fluid mx-auto shadow-sm" 
                             style="width: 130px; height: 130px; object-fit: cover;">
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">Bilal Mengal</h5>
                        <p class="text-cyan small fw-semibold mb-2">Network Infrastructure Architect</p>
                        <p class="text-muted small">Cisco & MikroTik certified expert in enterprise LAN/WAN and optical cabling.</p>
                    </div>
                </div>
            </div>

            <!-- Team Member 3 -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 text-center overflow-hidden h-100">
                    <div class="pt-4 px-4">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80" 
                             alt="Zubair Kasi" 
                             class="rounded-circle img-fluid mx-auto shadow-sm" 
                             style="width: 130px; height: 130px; object-fit: cover;">
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">Zubair Kasi</h5>
                        <p class="text-cyan small fw-semibold mb-2">Surveillance & CCTV Engineer</p>
                        <p class="text-muted small">Specialist in commercial IP camera security grids and cloud backup storage.</p>
                    </div>
                </div>
            </div>

            <!-- Team Member 4 -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 text-center overflow-hidden h-100">
                    <div class="pt-4 px-4">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80" 
                             alt="Ayesha Rind" 
                             class="rounded-circle img-fluid mx-auto shadow-sm" 
                             style="width: 130px; height: 130px; object-fit: cover;">
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">Ayesha Rind</h5>
                        <p class="text-cyan small fw-semibold mb-2">Senior Full-Stack Developer</p>
                        <p class="text-muted small">Building modern, responsive database web applications and business portals.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5 bg-dark-navy text-white text-center">
    <div class="container">
        <h3 class="fw-bold mb-3">Looking for Professional IT Consultation in Quetta?</h3>
        <p class="text-white-50 max-w-700 mx-auto mb-4">
            Visit our repair center on Zarghoon Road or contact us online to speak with our senior hardware engineer.
        </p>
        <a href="<?= BASE_URL ?>contact.php" class="btn btn-cyan px-5 py-3 fw-bold rounded-pill">
            Get In Touch Today <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
