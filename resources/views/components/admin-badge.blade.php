{{-- Badge khusus admin: muncul di nama konten yang ditulis admin --}}
@if (($user->is_admin ?? false))
    <span title="Administrator Dolan Semarang" style="display:inline-flex;align-items:center;gap:3px;background:#2B2432;color:#FFFFFF;font-size:10px;font-weight:800;padding:2px 8px;border-radius:99px;letter-spacing:.4px;vertical-align:middle">
        <span class="material-icons" style="font-size:11px">shield</span>Admin
    </span>
@endif
