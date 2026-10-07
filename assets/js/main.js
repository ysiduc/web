/* ==========================================================================
   Main JavaScript – PNMEC Group  (shared across all pages)
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {

  /* Mobile Nav Drawer Toggle */
  const toggleBtn   = document.getElementById('menuToggle') || document.querySelector('.menu-toggle');
  const drawer      = document.getElementById('mobileDrawer');
  const backdrop    = document.getElementById('mobileDrawerBackdrop');
  const closeBtn    = document.getElementById('mobileDrawerClose');
  const legacyMenu  = document.querySelector('.nav-menu');

  function openDrawer() {
    if (drawer) {
      drawer.classList.add('active');
      drawer.setAttribute('aria-hidden', 'false');
    }
    if (backdrop) backdrop.classList.add('active');
    if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    if (drawer) {
      drawer.classList.remove('active');
      drawer.setAttribute('aria-hidden', 'true');
    }
    if (backdrop) backdrop.classList.remove('active');
    if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  if (toggleBtn) {
    toggleBtn.addEventListener('click', function () {
      if (drawer) {
        if (drawer.classList.contains('active')) {
          closeDrawer();
        } else {
          openDrawer();
        }
      } else if (legacyMenu) {
        legacyMenu.classList.toggle('show');
      }
    });
  }

  if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
  if (backdrop) backdrop.addEventListener('click', closeDrawer);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer && drawer.classList.contains('active')) {
      closeDrawer();
    }
  });

  // Close drawer when clicking any link inside it
  if (drawer) {
    drawer.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', closeDrawer);
    });
  }

  // Auto-close drawer if viewport resizes to desktop (>= 1024px)
  const desktopMediaQuery = window.matchMedia('(min-width: 1024px)');
  function handleDesktopChange(e) {
    if (e.matches && drawer && drawer.classList.contains('active')) {
      closeDrawer();
    }
  }
  if (desktopMediaQuery.addEventListener) {
    desktopMediaQuery.addEventListener('change', handleDesktopChange);
  } else if (desktopMediaQuery.addListener) {
    desktopMediaQuery.addListener(handleDesktopChange);
  }

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
