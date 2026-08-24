<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Contact Messages Management (admin/messages/index.php)
 * ==============================================================================
 */

$adminPage  = 'messages';
$adminTitle = 'Customer Inquiries & Messages';

require_once __DIR__ . '/../includes/admin_header.php';

$pdo = getDBConnection();
$stmt = $pdo->query("SELECT * FROM `contact_messages` ORDER BY `id` DESC");
$messages = $stmt->fetchAll();
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h4 class="fw-bold mb-1">Customer Inquiries</h4>
        <p class="text-muted small mb-0">Review and respond to messages submitted via the public contact page.</p>
    </div>
    <div>
        <span class="badge bg-primary px-3 py-2 rounded-pill font-monospace">
            <?= count($messages) ?> Total Inquiries
        </span>
    </div>
</div>

<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th>Customer Name</th>
                    <th>Contact Details</th>
                    <th>Subject</th>
                    <th>Message Snippet</th>
                    <th>Date Received</th>
                    <th class="text-end" style="width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $msg): ?>
                        <tr id="msg-<?= e($msg['id']) ?>">
                            <td class="font-monospace text-muted small">#<?= e($msg['id']) ?></td>
                            <td>
                                <strong class="text-dark d-block"><?= e($msg['name']) ?></strong>
                            </td>
                            <td>
                                <div class="small">
                                    <a href="mailto:<?= e($msg['email']) ?>" class="text-decoration-none text-dark d-block">
                                        <i class="bi bi-envelope-at text-muted me-1"></i><?= e($msg['email']) ?>
                                    </a>
                                    <?php if (!empty($msg['phone'])): ?>
                                        <a href="tel:<?= urlencode($msg['phone']) ?>" class="text-decoration-none text-muted">
                                            <i class="bi bi-telephone text-muted me-1"></i><?= e($msg['phone']) ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark"><?= e($msg['subject']) ?></span>
                            </td>
                            <td>
                                <span class="small text-muted text-truncate d-inline-block" style="max-width: 220px;">
                                    <?= e($msg['message']) ?>
                                </span>
                            </td>
                            <td class="small text-muted">
                                <div title="<?= e($msg['created_at']) ?>"><?= time_ago($msg['created_at']) ?></div>
                                <span style="font-size: 0.75rem; color: #94a3b8;"><?= date('M d, Y H:i', strtotime($msg['created_at'])) ?></span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <!-- View Message Modal Button -->
                                    <button type="button" 
                                            class="btn btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewMessageModal<?= e($msg['id']) ?>" 
                                            title="Read Full Message">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <!-- Delete Message Button -->
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteMessageModal<?= e($msg['id']) ?>" 
                                            title="Delete Message">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- View Full Message Modal -->
                                <div class="modal fade text-start" id="viewMessageModal<?= e($msg['id']) ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-dark">
                                                    <i class="bi bi-chat-left-text text-info me-2"></i> Inquiry from <?= e($msg['name']) ?>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                <div class="p-3 bg-light rounded-3 mb-3 small">
                                                    <div class="row g-2">
                                                        <div class="col-sm-6">
                                                            <strong>Email:</strong> <a href="mailto:<?= e($msg['email']) ?>"><?= e($msg['email']) ?></a>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <strong>Phone:</strong> <?= !empty($msg['phone']) ? e($msg['phone']) : 'Not provided' ?>
                                                        </div>
                                                        <div class="col-12">
                                                            <strong>Subject:</strong> <?= e($msg['subject']) ?>
                                                        </div>
                                                        <div class="col-12">
                                                            <strong>Received:</strong> <?= date('F j, Y, g:i a', strtotime($msg['created_at'])) ?> (<?= time_ago($msg['created_at']) ?>)
                                                        </div>
                                                    </div>
                                                </div>
                                                <h6 class="fw-bold small text-muted text-uppercase mb-2">Message Body:</h6>
                                                <div class="p-3 border rounded-3 bg-white text-dark small lh-base">
                                                    <?= nl2br(e($msg['message'])) ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <a href="mailto:<?= e($msg['email']) ?>?subject=Re: <?= urlencode($msg['subject']) ?>" class="btn btn-primary rounded-pill px-3">
                                                    <i class="bi bi-reply-fill me-1"></i> Reply via Email
                                                </a>
                                                <?php if (!empty($msg['phone'])): ?>
                                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $msg['phone']) ?>" target="_blank" class="btn btn-success rounded-pill px-3">
                                                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                                    </a>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade text-start" id="deleteMessageModal<?= e($msg['id']) ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-danger">
                                                    <i class="bi bi-exclamation-octagon me-2"></i> Delete Message
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-4">
                                                <p class="mb-0">Are you sure you want to delete the message from <strong><?= e($msg['name']) ?></strong> regarding "<em><?= e($msg['subject']) ?></em>"?</p>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                                                <form action="<?= BASE_URL ?>admin/messages/delete.php" method="POST" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="id" value="<?= e($msg['id']) ?>">
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                                                        <i class="bi bi-trash me-1"></i> Delete
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            No contact inquiries in your inbox.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
