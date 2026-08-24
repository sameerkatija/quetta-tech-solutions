<?php
/**
 * ==============================================================================
 * Quetta Tech Solutions - Admin Footer Component
 * ==============================================================================
 */
?>
        </main>

        <!-- Admin Footer -->
        <footer class="bg-white border-top py-3 px-4 text-center text-muted small mt-auto">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <span>&copy; <?= date('Y') ?> <strong><?= e(APP_NAME) ?></strong> Admin Management System.</span>
                <span>PHP 8 &bull; MySQL PDO &bull; Bootstrap 5</span>
            </div>
        </footer>
    </div>
</div>

<!-- Bootstrap 5.3 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Admin JS -->
<script src="<?= BASE_URL ?>assets/js/admin.js"></script>
</body>
</html>
