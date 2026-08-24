/**
 * ==============================================================================
 * Quetta Tech Solutions - Admin Dashboard JavaScript Interactions
 * ==============================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Sidebar toggle on mobile
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show-sidebar');
        });
    }

    // 2. Real-time Image File Preview for Forms
    const imageInputs = document.querySelectorAll('input[type="file"][data-preview-target]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function () {
            const targetSelector = this.getAttribute('data-preview-target');
            const previewEl = document.querySelector(targetSelector);
            
            if (previewEl && this.files && this.files[0]) {
                const file = this.files[0];
                
                // Validate file size on client side (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Selected file is larger than 5MB. Please choose a smaller image.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    previewEl.src = e.target.result;
                    previewEl.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // 3. Auto dismiss alert messages
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 5000);
    });
});
