/* Customer app root — routing + cart state */
(function () {
  const { useState, useEffect, useCallback } = React;
  const h = React.createElement;

  function App() {
    const [view, setView] = useState(() => location.hash.replace('#', '') || 'home');
    const [cart, setCart] = useState([]);
    const [cartOpen, setCartOpen] = useState(false);

    const go = useCallback((v) => { setView(v); location.hash = v; window.scrollTo({ top: 0 }); }, []);
    useEffect(() => { const onHash = () => setView(location.hash.replace('#', '') || 'home'); window.addEventListener('hashchange', onHash); return () => window.removeEventListener('hashchange', onHash); }, []);

    const addItem = useCallback((it) => {
      setCart(c => { const ex = c.find(x => x.id === it.id); return ex ? c.map(x => x.id === it.id ? { ...x, qty: x.qty + 1 } : x) : [...c, { ...it, qty: 1 }]; });
      setCartOpen(true);
    }, []);
    const setQty = useCallback((id, q) => setCart(c => q <= 0 ? c.filter(x => x.id !== id) : c.map(x => x.id === id ? { ...x, qty: q } : x)), []);
    const removeItem = useCallback((id) => setCart(c => c.filter(x => x.id !== id)), []);
    const clearCart = useCallback(() => setCart([]), []);
    const subtotal = cart.reduce((n, i) => n + i.price * i.qty, 0);

    const ctx = { view, go, cart, addItem, setQty, removeItem, clearCart, subtotal, cartOpen, openCart: () => setCartOpen(true), closeCart: () => setCartOpen(false) };

    const pages = { home: window.Home, order: window.Order, checkout: window.Checkout, reserve: window.Reserve, catering: window.Catering, gallery: window.Gallery, promos: window.Promos, contact: window.Contact, about: window.About, giftcards: window.GiftCard };
    const Page = pages[view] || window.Home;

    return h(window.ShopContext.Provider, { value: ctx },
      h(window.Nav, null),
      h('main', { key: view, className: 'fade-up' }, h(Page, null)),
      view !== 'checkout' && h(window.Footer, null),
      h(window.CartDrawer, null));
  }

  ReactDOM.createRoot(document.getElementById('root')).render(h(App));
})();
