<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <img src="{{ asset('assets/img/Lambang_Kabupaten_Purworejo.png') }}" alt="Logo Purworejo" style="height:32px; width:32px; object-fit:contain; flex-shrink:0;">
            <div class="ms-2 app-brand-text-wrapper">
                <span class="app-brand-text demo menu-text fw-semibold d-block" style="line-height:1.2">SIMONDA</span>
                <span class="text-muted" style="font-size:.65rem; line-height:1">Kab. Purworejo</span>
            </div>
        </a>
        
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.47365 11.7183C8.11707 12.0749 8.11707 12.6531 8.47365 13.0097L12.071 16.607C12.4615 16.9975 12.4615 17.6305 12.071 18.021C11.6805 18.4115 11.0475 18.4115 10.657 18.021L5.83009 13.1941C5.37164 12.7356 5.37164 11.9924 5.83009 11.5339L10.657 6.707C11.0475 6.31653 11.6805 6.31653 12.071 6.707C12.4615 7.09747 12.4615 7.73053 12.071 8.121L8.47365 11.7183Z" fill-opacity="0.9" />
                <path d="M14.3584 11.8336C14.0654 12.1266 14.0654 12.6014 14.3584 12.8944L18.071 16.607C18.4615 16.9975 18.4615 17.6305 18.071 18.021C17.6805 18.4115 17.0475 18.4115 16.657 18.021L11.6819 13.0459C11.3053 12.6693 11.3053 12.0587 11.6819 11.6821L16.657 6.707C17.0475 6.31653 17.6805 6.31653 18.071 6.707C18.4615 7.09747 18.4615 7.73053 18.071 8.121L14.3584 11.8336Z" fill-opacity="0.4" />
            </svg>
        </a>
    </div>
    
    <div class="menu-inner-shadow"></div>
    
    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ri-home-smile-line"></i>
                <div>Dashboard</div>
            </a>
        </li>
        
        @if(auth()->user()->role === 'admin')
            <!-- Menu Admin -->
            <li class="menu-header mt-5">
                <span class="menu-header-text">Master Data</span>
            </li>
            
            <li class="menu-item {{ request()->routeIs('admin.kategori-usaha.*') ? 'active' : '' }}">
                <a href="{{ route('admin.kategori-usaha.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ri-list-check"></i>
                    <div>Kategori Usaha</div>
                </a>
            </li>
            
            <li class="menu-item {{ request()->routeIs('admin.kelas-usaha.*') ? 'active' : '' }}">
                <a href="{{ route('admin.kelas-usaha.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ri-stack-line"></i>
                    <div>Kelas Usaha</div>
                </a>
            </li>
            
            <li class="menu-item {{ request()->routeIs('admin.legalitas-usaha.*') ? 'active' : '' }}">
                <a href="{{ route('admin.legalitas-usaha.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ri-file-text-line"></i>
                    <div>Legalitas Usaha</div>
                </a>
            </li>
            
            <li class="menu-header mt-5">
                <span class="menu-header-text">Manajemen</span>
            </li>
            
            <li class="menu-item {{ request()->routeIs('admin.data-umkm.*') ? 'active' : '' }}">
                <a href="{{ route('admin.data-umkm.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ri-store-2-line"></i>
                    <div>Data UMKM</div>
                </a>
            </li>
            
            <li class="menu-item {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}">
                <a href="{{ route('admin.pengguna.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ri-user-line"></i>
                    <div>Pengguna</div>
                </a>
            </li>
            
            <li class="menu-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <a href="{{ route('admin.laporan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ri-file-chart-line"></i>
                    <div>Laporan</div>
                </a>
            </li>
        @endif
        
        @if(auth()->user()->role === 'pendamping')
            <!-- Menu Pendamping -->
            <li class="menu-header mt-5">
                <span class="menu-header-text">Manajemen</span>
            </li>
            
            <li class="menu-item {{ request()->routeIs('pendamping.data-umkm.*') ? 'active' : '' }}">
                <a href="{{ route('pendamping.data-umkm.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ri-store-2-line"></i>
                    <div>Data UMKM</div>
                </a>
            </li>
            
            <li class="menu-item {{ request()->routeIs('pendamping.laporan.*') ? 'active' : '' }}">
                <a href="{{ route('pendamping.laporan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ri-file-chart-line"></i>
                    <div>Laporan</div>
                </a>
            </li>
        @endif
        
        @if(auth()->user()->role === 'kepala_bagian')
            <!-- Menu Kepala Bagian -->
            <li class="menu-header mt-5">
                <span class="menu-header-text">Monitoring</span>
            </li>
            
            <li class="menu-item {{ request()->routeIs('kepala-bagian.data-umkm.*') ? 'active' : '' }}">
                <a href="{{ route('kepala-bagian.data-umkm.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ri-store-2-line"></i>
                    <div>Data UMKM</div>
                </a>
            </li>
            
            <li class="menu-item {{ request()->routeIs('kepala-bagian.laporan.*') ? 'active' : '' }}">
                <a href="{{ route('kepala-bagian.laporan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ri-file-chart-line"></i>
                    <div>Laporan</div>
                </a>
            </li>
        @endif
    </ul>
</aside>
