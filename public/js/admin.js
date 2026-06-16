function bootAdminUi() {
  if (window.__admUiBooted) return;
  window.__admUiBooted = true;

  initSegmentGroups();
  initTableSearch();
  initMenuFilters();
  initOrderDetails();
  initAdminNotifications();
  initPasswordToggles();
  initFileFields();
  initGalleryReplace();
  initUserPermissionRoles();
  autoDismissFlash();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', bootAdminUi, { once: true });
} else {
  bootAdminUi();
}

function initSegmentGroups() {
  document.querySelectorAll('[data-adm-segments]').forEach((group) => {
    const buttons = group.querySelectorAll('[data-adm-segment]');
    const target = group.dataset.admTarget;

    buttons.forEach((btn) => {
      btn.addEventListener('click', () => {
        const view = btn.dataset.admSegment;
        setSegmentActive(group, btn);

        if (target) {
          document.querySelectorAll(`[data-adm-view="${target}"]`).forEach((el) => {
            el.hidden = el.dataset.admPanel !== view;
          });
        }
      });
    });
  });
}

function setSegmentActive(group, activeBtn) {
  group.querySelectorAll('[data-adm-segment]').forEach((btn) => {
    const on = btn === activeBtn;
    btn.style.background = on ? 'var(--ink-600)' : 'transparent';
    btn.style.color = on ? 'var(--cream)' : 'var(--muted)';
  });
}

function initTableSearch() {
  document.querySelectorAll('[data-adm-search]').forEach((input) => {
    const selector = input.dataset.admSearch;
    const table = document.querySelector(selector);
    if (!table) return;

    const rows = () => table.querySelectorAll('tbody tr[data-adm-row]');

    input.addEventListener('input', () => {
      const q = input.value.trim().toLowerCase();
      rows().forEach((row) => {
        const hay = (row.dataset.admSearchText || row.textContent).toLowerCase();
        row.hidden = q.length > 0 && !hay.includes(q);
      });
    });

    if (input.form) {
      input.form.addEventListener('submit', (e) => e.preventDefault());
    }
  });
}

function initMenuFilters() {
  const table = document.querySelector('[data-adm-menu-table]');
  if (!table) return;

  const pills = document.querySelectorAll('[data-adm-menu-cat]');
  const rows = table.querySelectorAll('tbody tr[data-adm-row]');

  pills.forEach((pill) => {
    pill.addEventListener('click', () => {
      const cat = pill.dataset.admMenuCat;
      pills.forEach((p) => {
        const on = p === pill;
        p.style.background = on ? 'var(--gold-600)' : 'var(--ink-700)';
        p.style.color = on ? '#211405' : 'var(--cream-2)';
        p.style.borderColor = on ? 'var(--gold-600)' : 'var(--line)';
      });
      rows.forEach((row) => {
        row.hidden = cat !== 'all' && row.dataset.admCat !== cat;
      });
    });
  });
}

function initOrderDetails() {
  document.querySelectorAll('[data-adm-order-toggle]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.admOrderToggle;
      const row = document.querySelector(`[data-adm-order-detail="${id}"]`);
      if (row) row.hidden = !row.hidden;
    });
  });
}

function initAdminNotifications() {
  const wrap = document.getElementById('adm-notif');
  const btn = document.getElementById('adm-notif-btn');
  const panel = document.getElementById('adm-notif-panel');
  if (!wrap || !btn || !panel) return;

  const close = () => {
    panel.hidden = true;
    btn.setAttribute('aria-expanded', 'false');
  };

  btn.addEventListener('click', (e) => {
    e.stopPropagation();
    const open = panel.hidden;
    panel.hidden = !open;
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  document.addEventListener('click', (e) => {
    if (!wrap.contains(e.target)) close();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') close();
  });
}

function initPasswordToggles() {
  const eyePath = 'M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z';
  const eyeOffPath = 'M17.9 17.9A10 10 0 0 1 12 20C5 20 1 12 1 12a18.8 18.8 0 0 1 5.1-7.1M9.9 9.9a3 3 0 1 0 4.2 4.2M22 12s-3.5-7-10-7a10 10 0 0 0-5.3 1.5M3 3l18 18';

  document.querySelectorAll('#adm-main input[type="password"]').forEach((input) => {
    if (input.dataset.passReady === '1') return;
    input.dataset.passReady = '1';

    const wrap = document.createElement('div');
    wrap.className = 'adm-pass-wrap';
    input.parentNode.insertBefore(wrap, input);
    wrap.appendChild(input);

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'adm-pass-toggle';
    btn.setAttribute('aria-label', 'Show password');
    btn.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="${eyePath}"></path></svg>`;
    wrap.appendChild(btn);

    btn.addEventListener('click', () => {
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
      const path = btn.querySelector('path');
      if (path) path.setAttribute('d', show ? eyeOffPath : eyePath);
    });
  });
}

function initFileFields() {
  document.querySelectorAll('.adm-file-field input[type="file"]').forEach((input) => {
    if (input.dataset.fileReady === '1') return;
    input.dataset.fileReady = '1';

    const field = input.closest('.adm-file-field');
    const nameEl = field?.querySelector('[data-adm-file-name]');
    if (!nameEl) return;

    const sync = () => {
      const file = input.files?.[0];
      nameEl.textContent = file ? file.name : 'No file chosen';
      nameEl.style.color = file ? 'var(--cream)' : 'var(--muted)';
    };

    input.addEventListener('change', sync);
    sync();
  });
}

function initGalleryReplace() {
  const dialog = document.getElementById('gallery-replace-dialog');
  const form = document.getElementById('gallery-replace-form');
  const captionInput = document.getElementById('gallery-replace-caption');
  const nameEl = document.getElementById('gallery-replace-name');
  const preview = document.getElementById('gallery-replace-preview');
  const cancelBtn = document.getElementById('gallery-replace-cancel');

  if (!dialog || !form || form.dataset.ready === '1') return;
  form.dataset.ready = '1';

  document.querySelectorAll('[data-gallery-replace]').forEach((btn) => {
    btn.addEventListener('click', () => {
      form.action = btn.dataset.action || '';
      captionInput.value = btn.dataset.caption || '';

      if (nameEl) {
        nameEl.textContent = btn.dataset.caption ? `For: ${btn.dataset.caption}` : '';
      }

      if (preview) {
        const src = btn.dataset.src || '';
        preview.innerHTML = src
          ? `<img src="${src}" alt="" style="width:100%;height:100%;object-fit:cover;">`
          : '<div style="width:100%;height:100%;display:grid;place-items:center;color:var(--muted);font-size:13px;">No current photo</div>';
      }

      const fileInput = form.querySelector('input[type="file"]');
      const fileName = form.querySelector('[data-adm-file-name]');
      if (fileInput) {
        fileInput.value = '';
      }
      if (fileName) {
        fileName.textContent = 'No file chosen';
        fileName.style.color = 'var(--muted)';
      }

      dialog.showModal();
      initFileFields();
    });
  });

  cancelBtn?.addEventListener('click', () => dialog.close());
  dialog.addEventListener('cancel', (event) => {
    event.preventDefault();
    dialog.close();
  });
}

function initUserPermissionRoles() {
  document.querySelectorAll('[data-adm-user-role]').forEach((select) => {
    const sync = () => {
      const panel = select.closest('form')?.querySelector('[data-adm-user-permissions]');
      if (!panel) return;

      const isOwner = select.value === 'Owner';
      panel.style.opacity = isOwner ? '0.55' : '1';
      panel.querySelectorAll('input[type="checkbox"]').forEach((input) => {
        input.disabled = isOwner;
      });
    };

    select.addEventListener('change', sync);
    sync();
  });
}

function autoDismissFlash() {
  const flashes = document.querySelectorAll('[data-adm-flash]');
  if (!flashes.length) return;
  flashes.forEach((flash, index) => {
    setTimeout(() => {
      flash.style.opacity = '0';
      flash.style.transition = 'opacity .4s';
      setTimeout(() => flash.remove(), 400);
    }, 5000 + index * 250);
  });
}
