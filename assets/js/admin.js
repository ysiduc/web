/* Admin Panel JS Logic */
document.addEventListener('DOMContentLoaded', function() {
    // Confirm Action Delete
    document.querySelectorAll('.btn-confirm-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Bạn có chắc chắn muốn xóa mục này không? Hành động này không thể hoàn tác.')) {
                e.preventDefault();
            }
        });
    });

    // Preview Upload Image
    const inputImg = document.getElementById('image_upload_input');
    const previewImg = document.getElementById('image_preview');
    if (inputImg && previewImg) {
        inputImg.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
