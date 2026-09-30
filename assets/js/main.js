/* ==========================================================================
   Main JavaScript – PNMEC Group  (shared across all pages)
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {

  /* Mobile Nav Toggle */
  const toggle = document.querySelector('.menu-toggle');
  const menu   = document.querySelector('.nav-menu');
  if (toggle && menu) toggle.addEventListener('click', () => menu.classList.toggle('show'));

  /* Projects filter (projects.php) */
  const filterTabs  = document.querySelectorAll('.filter-tab');
  const projectItems = document.querySelectorAll('.project-card-item');
  if (filterTabs.length && projectItems.length) {
    filterTabs.forEach(tab => {
      tab.addEventListener('click', function () {
        filterTabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        const val = this.getAttribute('data-filter');
        projectItems.forEach(c => {
          c.style.display = (val === 'all' || c.dataset.category === val) ? 'flex' : 'none';
        });
      });
    });
  }

  /* Admin: image preview */
  const imgInput   = document.getElementById('project_image_input');
  const imgPreview = document.getElementById('project_image_preview');
  if (imgInput && imgPreview) {
    imgInput.addEventListener('change', function () {
      const file = this.files[0];
      if (file) {
        const r = new FileReader();
        r.onload = e => { imgPreview.src = e.target.result; imgPreview.style.display = 'block'; };
        r.readAsDataURL(file);
      }
    });
  }

  /* Admin: confirm delete */
  document.querySelectorAll('.btn-confirm-delete').forEach(btn => {
    btn.addEventListener('click', e => {
      if (!confirm('Bạn có chắc chắn muốn xóa bản ghi này không?')) e.preventDefault();
    });
  });

});
