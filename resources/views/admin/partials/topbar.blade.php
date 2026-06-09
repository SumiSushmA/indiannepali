<header style="position:sticky;top:0;z-index:80;height:70px;background:rgba(18,14,11,.85);backdrop-filter:blur(12px);border-bottom:1px solid var(--line);display:flex;align-items:center;gap:16px;padding:0 28px;">
    <button id="adm-burger" class="adm-burger" style="background:none;border:1px solid var(--line);border-radius:9px;width:40px;height:40px;cursor:pointer;color:var(--cream);">
        <x-icon name="menu" :size="20"/>
    </button>
    <div style="display:flex;align-items:center;gap:10px;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:0 14px;width:320px;max-width:40vw;">
        <x-icon name="search" :size="17" color="var(--muted)"/>
        <input placeholder="Search orders, guests, dishes…" style="flex:1;background:none;border:none;outline:none;color:var(--cream);font-size:14px;padding:11px 0;font-family:var(--sans);">
    </div>
    <div style="margin-left:auto;display:flex;align-items:center;gap:12px;">
        <a href="/" target="_blank" class="btn btn-ghost btn-sm" style="text-decoration:none;">
            <x-icon name="eye" :size="16"/> View site
        </a>
        <button style="position:relative;width:42px;height:42px;border-radius:10px;background:var(--ink-800);border:1px solid var(--line);color:var(--cream);cursor:pointer;display:grid;place-items:center;">
            <x-icon name="bell" :size="19"/>
            <span style="position:absolute;top:8px;right:9px;width:8px;height:8px;border-radius:999px;background:var(--spice-500);border:2px solid var(--ink-800);"></span>
        </button>
    </div>
</header>
