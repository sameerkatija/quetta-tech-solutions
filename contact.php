<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Contact Us Page (contact.php)
 * ==============================================================================
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$errors = [];
$formData = [
    'name'    => '',
    'email'   => '',
    'phone'   => '',
    'subject' => '',
    'message' => ''
];

// Pre-fill subject if passed via GET parameter (e.g. from service booking)
if (!empty($_GET['service'])) {
    $formData['subject'] = 'Inquiry regarding ' . sanitize($_GET['service']);
}

// Process POST form submission BEFORE sending any HTML output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $errors[] = 'Security token invalid or expired. Please refresh and resubmit.';
    } else {
        $formData['name']    = sanitize($_POST['name'] ?? '');
        $formData['email']   = sanitize($_POST['email'] ?? '');
        $formData['phone']   = sanitize($_POST['phone'] ?? '');
        $formData['subject'] = sanitize($_POST['subject'] ?? '');
        $formData['message'] = sanitize($_POST['message'] ?? '');

        // Validation rules
        if (empty($formData['name']) || mb_strlen($formData['name']) < 3) {
            $errors[] = 'Please enter your valid full name (at least 3 characters).';
        }

        if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please provide a valid email address.';
        }

        if (empty($formData['subject']) || mb_strlen($formData['subject']) < 4) {
            $errors[] = 'Please provide a descriptive subject for your inquiry.';
        }

        if (empty($formData['message']) || mb_strlen($formData['message']) < 10) {
            $errors[] = 'Please enter your message or problem description (at least 10 characters).';
        }

        // If no validation errors, insert into MySQL database using prepared statement
        if (empty($errors)) {
            try {
                $pdo = getDBConnection();
                $stmt = $pdo->prepare("
                    INSERT INTO `contact_messages` (`name`, `email`, `phone`, `subject`, `message`, `created_at`) 
                    VALUES (:name, :email, :phone, :subject, :message, NOW())
                ");
                $stmt->execute([
                    ':name'    => $formData['name'],
                    ':email'   => $formData['email'],
                    ':phone'   => $formData['phone'],
                    ':subject' => $formData['subject'],
                    ':message' => $formData['message']
                ]);

                set_flash('success', 'Thank you! Your message has been received. Our IT support team in Quetta will get back to you shortly.');
                header('Location: ' . BASE_URL . 'contact.php');
                exit;
            } catch (PDOException $e) {
                error_log('Contact Form DB Error: ' . $e->getMessage());
                $errors[] = 'An error occurred while saving your message. Please try again or call us directly.';
            }
        }
    }
}

// Set page metadata and include view header
$pageTitle = 'Contact Us';
$pageDescription = 'Get in touch with Quetta Tech Solutions for laptop repairs, desktop servicing, networking, and IT security in Quetta.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header / Hero -->
<section class="page-hero">
    <div class="container text-center">
        <span class="badge bg-info text-dark fw-bold text-uppercase px-3 py-1 rounded-pill mb-2">Get In Touch</span>
        <h1 class="fw-bold mb-2">Contact Quetta Tech Solutions</h1>
        <p class="text-white-50 lead max-w-700 mx-auto mb-0">
            Have a question about a computer repair, hardware upgrade, or corporate networking project? We are here to help.
        </p>
    </div>
</section>

<!-- Contact Form & Info Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        
        <!-- Display Flash Message -->
        <?= display_flash() ?>

        <!-- Display Validation Errors -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Please correct the following errors:</strong>
                <ul class="mb-0 mt-2 small">
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-5">
            <!-- Left Column: Contact Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    <h3 class="fw-bold mb-2">Send Us a Message</h3>
                    <p class="text-muted small mb-4">Fill in the details below and an IT technician will contact you within business hours.</p>

                    <form action="<?= BASE_URL ?>contact.php" method="POST" id="contactForm" novalidate>
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           placeholder="e.g. Ahmed Khan" 
                                           value="<?= e($formData['name']) ?>" required minlength="3">
                                </div>
                                <div class="invalid-feedback small">Please enter your name.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="name@example.com" 
                                           value="<?= e($formData['email']) ?>" required>
                                </div>
                                <div class="invalid-feedback small">Please enter a valid email.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold small">Phone / WhatsApp Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           placeholder="0333-1234567" 
                                           value="<?= e($formData['phone']) ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="subject" class="form-label fw-semibold small">Inquiry Subject <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-tag"></i></span>
                                    <input type="text" class="form-control" id="subject" name="subject" 
                                           placeholder="e.g. Laptop Screen Replacement" 
                                           value="<?= e($formData['subject']) ?>" required minlength="4">
                                </div>
                                <div class="invalid-feedback small">Please enter a subject.</div>
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label fw-semibold small">Message / Problem Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" rows="5" 
                                          placeholder="Describe the issue with your computer or details regarding your networking project..." 
                                          required minlength="10"><?= e($formData['message']) ?></textarea>
                                <div class="invalid-feedback small">Please enter a detailed message (min 10 characters).</div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-cyan text-dark fw-bold px-5 py-3 rounded-pill w-100 shadow-sm">
                                    <i class="bi bi-send-fill me-2"></i> Submit Inquiry
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Business Info & Working Hours -->
            <div class="col-lg-5">
                <div class="d-flex flex-column gap-3">
                    
                    <!-- Address Card -->
                    <div class="contact-info-card">
                        <div class="d-flex align-items-start gap-3">
                            <div class="feature-icon-box bg-primary-subtle text-primary mb-0">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Our Quetta Office</h6>
                                <p class="text-muted small mb-0"><?= e(APP_ADDRESS) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Phone Card -->
                    <div class="contact-info-card">
                        <div class="d-flex align-items-start gap-3">
                            <div class="feature-icon-box bg-success-subtle text-success mb-0">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Phone & WhatsApp</h6>
                                <p class="text-muted small mb-1">Direct Support: <a href="tel:<?= urlencode(APP_PHONE) ?>" class="text-decoration-none text-dark fw-bold"><?= e(APP_PHONE) ?></a></p>
                                <a href="https://wa.me/923337891234" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 mt-1">
                                    <i class="bi bi-whatsapp me-1"></i> WhatsApp Us
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Email Card -->
                    <div class="contact-info-card">
                        <div class="d-flex align-items-start gap-3">
                            <div class="feature-icon-box bg-info-subtle text-info mb-0">
                                <i class="bi bi-envelope-at-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Email Inquiries</h6>
                                <p class="text-muted small mb-0"><a href="mailto:<?= e(APP_EMAIL) ?>" class="text-decoration-none text-dark"><?= e(APP_EMAIL) ?></a></p>
                                <span class="badge bg-light text-muted small border mt-2">Typical reply time: 2 hours</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hours Card -->
                    <div class="contact-info-card">
                        <div class="d-flex align-items-start gap-3">
                            <div class="feature-icon-box bg-warning-subtle text-warning mb-0">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Service Center Hours</h6>
                                <p class="text-muted small mb-1"><strong>Monday - Saturday:</strong> 9:00 AM - 9:00 PM</p>
                                <p class="text-muted small mb-0"><strong>Sunday:</strong> 11:00 AM - 6:00 PM (Emergency on-call)</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Embedded Map Location -->
        <div class="mt-5 pt-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white py-3 px-4 d-flex justify-content-between align-items-center">
                    <span class="fw-semibold small"><i class="bi bi-map-fill text-cyan me-2"></i> Service Center Map Location (Zarghoon Road, Quetta)</span>
                    <span class="badge bg-success">Open For Walk-ins</span>
                </div>
                <div style="height: 320px; width: 100%;">
                    <iframe 
                        title="Quetta Tech Solutions Location Map"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d110196.88339846618!2d66.92003884335936!3d30.184139900000007!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ed2df5417316719%3A0xb355d49655f46401!2sZarghoon%20Rd%2C%20Quetta%2C%20Balochistan!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
