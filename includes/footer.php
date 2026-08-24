<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Public Footer Component
 * ==============================================================================
 */
?>
    </main>

    <!-- Footer Section -->
    <footer class="footer bg-dark-navy text-white pt-5 pb-3">
        <div class="container pt-3">
            <div class="row g-4">
                <!-- Column 1: Company Profile -->
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-icon-box">
                            <i class="bi bi-cpu-fill text-cyan"></i>
                        </div>
                        <div>
                            <span class="brand-title fw-bold fs-5">QUETTA<span class="text-cyan">TECH</span></span>
                            <span class="brand-subtitle d-block small">SOLUTIONS</span>
                        </div>
                    </div>
                    <p class="text-white-50 small mb-3 lh-base">
                        Quetta's trusted IT technology and hardware engineering hub. We specialize in fast computer repairs, enterprise network deployments, security surveillance, and business software development.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="#" class="social-btn" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-btn" aria-label="Twitter / X"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="social-btn" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="https://wa.me/923337891234" target="_blank" class="social-btn whatsapp" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white fw-bold text-uppercase letter-spacing mb-3">Navigation</h6>
                    <ul class="list-unstyled footer-links small">
                        <li class="mb-2"><a href="<?= BASE_URL ?>index.php"><i class="bi bi-chevron-right me-1 text-cyan"></i> Home</a></li>
                        <li class="mb-2"><a href="<?= BASE_URL ?>about.php"><i class="bi bi-chevron-right me-1 text-cyan"></i> About Us</a></li>
                        <li class="mb-2"><a href="<?= BASE_URL ?>services.php"><i class="bi bi-chevron-right me-1 text-cyan"></i> Our Services</a></li>
                        <li class="mb-2"><a href="<?= BASE_URL ?>contact.php"><i class="bi bi-chevron-right me-1 text-cyan"></i> Contact & Support</a></li>
                        <li class="mb-2"><a href="<?= BASE_URL ?>admin/login.php"><i class="bi bi-shield-lock me-1 text-cyan"></i> Staff Portal</a></li>
                    </ul>
                </div>

                <!-- Column 3: Featured Services -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold text-uppercase letter-spacing mb-3">Core Expertise</h6>
                    <ul class="list-unstyled footer-links small">
                        <li class="mb-2"><a href="<?= BASE_URL ?>services.php"><i class="bi bi-laptop me-1 text-cyan"></i> Laptop & Desktop Repairs</a></li>
                        <li class="mb-2"><a href="<?= BASE_URL ?>services.php"><i class="bi bi-router me-1 text-cyan"></i> Enterprise Networking</a></li>
                        <li class="mb-2"><a href="<?= BASE_URL ?>services.php"><i class="bi bi-camera-video me-1 text-cyan"></i> CCTV Security Installations</a></li>
                        <li class="mb-2"><a href="<?= BASE_URL ?>services.php"><i class="bi bi-code-slash me-1 text-cyan"></i> Web & Software Development</a></li>
                        <li class="mb-2"><a href="<?= BASE_URL ?>services.php"><i class="bi bi-hdd-network me-1 text-cyan"></i> Data Recovery & Backup</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact Info -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold text-uppercase letter-spacing mb-3">Quetta Service Center</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="bi bi-geo-alt-fill text-cyan mt-1"></i>
                            <span><?= e(APP_ADDRESS) ?></span>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-telephone-fill text-cyan"></i>
                            <span><?= e(APP_PHONE) ?></span>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-envelope-fill text-cyan"></i>
                            <span><?= e(APP_EMAIL) ?></span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock-fill text-cyan"></i>
                            <span><?= e(APP_HOURS) ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-secondary-subtle my-4">

            <div class="row align-items-center small text-white-50">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    &copy; <?= date('Y') ?> <strong><?= e(APP_NAME) ?></strong>. All rights reserved. Final Project Implementation.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <span>Designed with PHP 8, MySQL & Bootstrap 5</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Main JS -->
    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>
