/**
 * Admin Panel JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Confirm delete actions
    document.querySelectorAll('[data-confirm]').forEach(element => {
        element.addEventListener('click', function(e) {
            const message = this.dataset.confirm || 'Are you sure?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-dismiss alerts
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
    // Image upload preview
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById(this.dataset.preview);
            
            if (previewContainer && files.length > 0) {
                previewContainer.innerHTML = '';
                
                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'image-preview-item';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview">
                            <button type="button" class="image-preview-remove" data-index="${index}">&times;</button>
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    });
    
    // Remove image from preview
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('image-preview-remove')) {
            e.target.closest('.image-preview-item').remove();
        }
    });
    
    // Toggle sidebar on mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    // Rich text editor initialization (if needed)
    const textareas = document.querySelectorAll('textarea[data-editor="rich"]');
    if (textareas.length > 0) {
        // You can integrate a rich text editor here like TinyMCE or CKEditor
        console.log('Rich text editor areas found:', textareas.length);
    }
    
    // Status update via AJAX
    document.querySelectorAll('[data-status-update]').forEach(select => {
        select.addEventListener('change', async function() {
            const url = this.dataset.statusUpdate;
            const status = this.value;
            const id = this.dataset.id;
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id, status })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('success', 'Status updated successfully');
                } else {
                    showAlert('error', data.message || 'Failed to update status');
                }
            } catch (error) {
                showAlert('error', 'An error occurred');
            }
        });
    });
    
    // Bulk actions
    const bulkActionForm = document.getElementById('bulkActionForm');
    if (bulkActionForm) {
        bulkActionForm.addEventListener('submit', function(e) {
            const action = this.querySelector('[name="bulk_action"]').value;
            const checkboxes = this.querySelectorAll('input[type="checkbox"]:checked');
            
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one item');
                return;
            }
            
            if (action === 'delete') {
                if (!confirm(`Are you sure you want to delete ${checkboxes.length} item(s)?`)) {
                    e.preventDefault();
                }
            }
        });
        
        // Select all checkbox
        const selectAll = bulkActionForm.querySelector('#selectAll');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                const checkboxes = bulkActionForm.querySelectorAll('input[type="checkbox"][name="ids[]"]');
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        }
    }
});

