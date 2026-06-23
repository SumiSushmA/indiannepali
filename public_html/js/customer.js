(function () {
  const header = document.getElementById('cust-header');
  const isHome = document.body.dataset.page === 'home';

  function updateHeader() {
    if (!header) return;
    const solid = window.scrollY > 30 || !isHome;
    header.classList.toggle('solid', solid);
  }

  window.addEventListener('scroll', updateHeader, { passive: true });
  updateHeader();

  const burger = document.getElementById('cust-burger');
  const scrim = document.getElementById('cust-mobile-scrim');
  const closeMobile = document.getElementById('cust-mobile-close');

  function openMobile() {
    scrim?.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeMobileNav() {
    scrim?.classList.remove('open');
    document.body.style.overflow = '';
  }

  burger?.addEventListener('click', openMobile);
  closeMobile?.addEventListener('click', closeMobileNav);
  scrim?.addEventListener('click', (e) => {
    if (e.target === scrim) closeMobileNav();
  });

  const cartDrawer = document.getElementById('cart-drawer');
  const cartOpenBtn = document.getElementById('cust-cart-btn');
  const cartCloseBtn = document.getElementById('cart-drawer-close');
  const cartBackdrop = document.getElementById('cart-drawer-backdrop');

  function openCart() {
    cartDrawer?.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeCart() {
    cartDrawer?.classList.remove('open');
    document.body.style.overflow = '';
  }

  cartOpenBtn?.addEventListener('click', openCart);
  cartCloseBtn?.addEventListener('click', closeCart);
  cartBackdrop?.addEventListener('click', closeCart);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeCart();
      closeMobileNav();
    }
  });

  if (document.body.dataset.openCart === '1') {
    openCart();
  }

  const flash = document.querySelector('.cust-flash');
  if (flash) {
    setTimeout(() => {
      flash.style.opacity = '0';
      flash.style.transition = 'opacity .4s';
      setTimeout(() => flash.remove(), 400);
    }, 3200);
  }

  const reviewTrack = document.querySelector('[data-rev-track]');
  const prevBtn = document.querySelector('[data-rev-nav="prev"]');
  const nextBtn = document.querySelector('[data-rev-nav="next"]');
  if (reviewTrack && prevBtn && nextBtn) {
    const slide = () => {
      const card = reviewTrack.querySelector('.cust-review-card');
      if (!card) return Math.max(320, reviewTrack.clientWidth * 0.85);
      const gap = 24;
      return card.getBoundingClientRect().width + gap;
    };
    prevBtn.addEventListener('click', () => reviewTrack.scrollBy({ left: -slide(), behavior: 'smooth' }));
    nextBtn.addEventListener('click', () => reviewTrack.scrollBy({ left: slide(), behavior: 'smooth' }));
  }
})();
