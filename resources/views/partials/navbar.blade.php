<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="ri-menu-fill ri-22px"></i>
        </a>
    </div>
    
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0">
                <span class="d-none d-md-inline-block text-muted fw-normal">SIMONDA - Sistem Informasi Monitoring Data UMKM</span>
            </div>
        </div>
        <!-- /Search -->
        
        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- Tombol ke Landing Page -->
            <li class="nav-item me-2">
                <a href="{{ route('landing') }}" class="btn btn-outline-secondary btn-sm" title="Ke Beranda">
                    <i class="ri-home-2-line me-1"></i>
                    <span class="d-none d-md-inline">Beranda</span>
                </a>
            </li>

            <!-- Notifications (hanya admin) -->
            @if(auth()->user()->role === 'admin')
            <li class="nav-item dropdown me-3">
                <a class="nav-link position-relative p-2" href="javascript:void(0)" id="notifDropdown"
                   data-bs-toggle="dropdown" aria-expanded="false" onclick="loadNotifications()">
                    <i class="ri-notification-3-line ri-22px"></i>
                    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle d-none"
                          id="notifBadge" style="font-size:10px; min-width:18px; padding:2px 5px;"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0" style="width:340px; max-height:420px; overflow:hidden;"
                     aria-labelledby="notifDropdown">
                    <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                        <h6 class="mb-0 fw-semibold">Notifikasi</h6>
                        <small class="text-muted" id="notifSubtitle">Memuat...</small>
                    </div>
                    <div id="notifList" style="max-height:340px; overflow-y:auto;">
                        <div class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                        </div>
                    </div>
                </div>
            </li>
            @endif
            <!--/ Notifications -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
                    <li>
                        <a class="dropdown-item pb-2 mb-1" href="javascript:void(0);">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2 pe-1">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ auth()->user()->name ?? 'User' }}</h6>
                                    <small class="text-muted">{{ ucfirst(str_replace('_', ' ', auth()->user()->role ?? 'User')) }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="ri-user-3-line ri-22px me-2"></i>
                            <span class="align-middle">Profil Saya</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="ri-logout-box-r-line ri-22px me-2"></i>
                                <span class="align-middle">Keluar</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>

@if(auth()->user()->role === 'admin')
<script>
const NOTIF_COUNT_URL = '{{ route("admin.notifications.unread-count") }}';
const NOTIF_LIST_URL  = '{{ route("admin.notifications.index") }}';

function updateBadge(count) {
    const badge = document.getElementById('notifBadge');
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.classList.remove('d-none');
    } else {
        badge.classList.add('d-none');
    }
}

function loadNotifications() {
    const list = document.getElementById('notifList');
    list.innerHTML = '<div class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm" role="status"></div></div>';

    fetch(NOTIF_LIST_URL)
        .then(r => r.json())
        .then(res => {
            const data = res.data;
            document.getElementById('notifSubtitle').textContent = data.length + ' aktivitas';
            updateBadge(0); // sudah dibaca semua

            if (data.length === 0) {
                list.innerHTML = '<div class="text-center py-4 text-muted small">Belum ada aktivitas</div>';
                return;
            }

            list.innerHTML = data.map(n => `
                <div class="d-flex align-items-start px-3 py-2 border-bottom ${n.is_read ? '' : 'bg-light'}">
                    <div class="me-2 mt-1">
                        <i class="${n.icon} ri-20px"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-0 small">${n.description}</p>
                        <small class="text-muted">${n.time}</small>
                    </div>
                </div>
            `).join('');
        })
        .catch(() => {
            list.innerHTML = '<div class="text-center py-3 text-danger small">Gagal memuat notifikasi</div>';
        });
}

// Polling setiap 30 detik
function pollUnreadCount() {
    fetch(NOTIF_COUNT_URL)
        .then(r => r.json())
        .then(res => updateBadge(res.count))
        .catch(() => {});
}

pollUnreadCount();
setInterval(pollUnreadCount, 30000);
</script>
@endif
