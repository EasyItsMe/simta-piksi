<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMTA - Politeknik Piksi Input Serang</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --bs-primary: #2563EB;
            --bs-success: #16A34A;
            --bs-warning: #FACC15;
            --bs-danger: #DC2626;
            --sidebar-width: 260px;
            --surface: rgba(255, 255, 255, 0.95);
            --border-soft: rgba(148, 163, 184, 0.2);
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #eef4ff 100%);
            color: #0f172a;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 0.96rem;
            line-height: 1.6;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.25;
        }

        .text-muted {
            color: #64748b !important;
        }

        /* Responsive typography */
        html { font-size: clamp(14px, 1.2vw + 10px, 16px); }

        .page-title {
            font-size: clamp(1.4rem, 2.6vw, 2rem);
            color: var(--bs-primary);
            margin-bottom: 0.25rem;
        }

        /* Stat cards */
        .stat-card { padding: 0; overflow: hidden; }
        .stat-card .card-body { padding: 1.25rem 1.5rem; }
        .stat-value {
            font-size: clamp(1.25rem, 3.4vw, 2.25rem);
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        /* Ensure consistent card body spacing */
        .card .card-body { padding: 1.25rem 1.5rem; }

        /* Auth / login tweaks */
        .auth-card { max-width: 520px; min-width: 360px; width: 100%; margin-inline: auto; }
        .auth-heading { font-size: clamp(1rem, 1.4vw, 1.25rem); line-height: 1.1; }
        .auth-card .card-body { padding: 0.9rem 1rem; }
        .auth-icon { width:48px; height:48px; font-size:1.15rem; }
        .auth-form .mb-3 { margin-bottom: 0.6rem; }
        .auth-form .btn { padding: 0.6rem 1rem; }
        @media (max-width: 576px) {
            .auth-card { max-width: 92%; }
            .auth-heading { font-size: 1.1rem; }
        }

        /* Sidebar Styles */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--surface);
            backdrop-filter: blur(16px);
            box-shadow: 12px 0 32px rgba(15, 23, 42, 0.05);
            border-right: 1px solid var(--border-soft);
            z-index: 1000;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        #sidebar.active {
            margin-left: calc(var(--sidebar-width) * -1);
        }
        #sidebar .sidebar-header {
            padding: 24px 20px 20px;
            background: linear-gradient(135deg, var(--bs-primary), #3b82f6);
            color: white;
            text-align: center;
        }
        #sidebar .sidebar-header small {
            opacity: 0.9;
        }
        #sidebar ul.components {
            padding: 18px 12px;
        }
        #sidebar ul li a {
            padding: 12px 14px;
            margin: 4px 0;
            font-size: 0.98rem;
            display: flex;
            align-items: center;
            color: #475569;
            text-decoration: none;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: var(--bs-primary);
            background: linear-gradient(90deg, #eff6ff, #f8fbff);
            box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.08);
        }
        #sidebar ul li a i {
            margin-right: 10px;
            font-size: 1.1em;
            width: 18px;
            text-align: center;
        }

        /* Content Styles */
        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s ease;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(14px);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
            border-bottom: 1px solid var(--border-soft);
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .main-content {
            padding: 28px 30px 40px;
            flex-grow: 1;
        }

        .card {
            border: none;
            border-radius: 0;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }
        .card-header {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.06), rgba(14, 165, 233, 0.04));
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            padding: 16px 20px;
            font-weight: 600;
            color: #0f172a;
        }

        .form-control, .form-select, textarea.form-control {
            border-radius: 12px;
            border: 1px solid #dbe3f0;
            padding: 0.72rem 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }
        .form-control:focus, .form-select:focus, textarea.form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
            transform: translateY(-1px);
        }
        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.45rem;
        }

        .btn {
            border-radius: 999px;
            padding: 0.72rem 1.1rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--bs-primary), #3b82f6);
            border: none;
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.2);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.24);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .table {
            --bs-table-bg: transparent;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            letter-spacing: 0.01em;
            border-bottom: 1px solid #e2e8f0;
            padding-top: 0.95rem;
            padding-bottom: 0.95rem;
        }
        .table tbody td {
            padding-top: 0.9rem;
            padding-bottom: 0.9rem;
            vertical-align: middle;
        }
        .dataTables_wrapper .row { margin-bottom: 15px; }
        table.dataTable tbody tr { transition: all 0.2s ease; }
        table.dataTable tbody tr:hover { background-color: #f8fbff; }

        .dropdown-menu {
            border: none;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .badge {
            border-radius: 999px;
            padding: 0.4em 0.7em;
            font-weight: 600;
        }

        .alert {
            border: none;
            border-radius: 14px;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.04);
        }

        .pagination .page-link {
            border-radius: 999px;
            margin: 0 0.2rem;
            border: none;
            color: #475569;
            min-width: 2.4rem;
            text-align: center;
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--bs-primary), #3b82f6);
            color: white;
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.2);
        }

        .list-group-item {
            border-radius: 12px;
            margin-bottom: 0.35rem;
            border: 1px solid rgba(148, 163, 184, 0.16);
        }

        #loadingOverlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(248, 250, 252, 0.8);
            backdrop-filter: blur(6px);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px 16px 28px;
            }
            #sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
                position: fixed;
                height: 100vh;
            }
            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
    <style>
        body::before,
        body::after {
            content: '';
            position: fixed;
            inset: auto;
            border-radius: 999px;
            pointer-events: none;
            filter: blur(60px);
            opacity: 0.45;
            z-index: 0;
        }

        body::before {
            width: 320px;
            height: 320px;
            top: -80px;
            left: -80px;
            background: rgba(37, 99, 235, 0.16);
        }

        body::after {
            width: 280px;
            height: 280px;
            bottom: -80px;
            right: -60px;
            background: rgba(14, 165, 233, 0.14);
        }

        .page-shell {
            display: flex;
            flex-direction: column;
            gap: 1.15rem;
        }

        .page-hero {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 1rem;
            padding: 1.2rem 1.35rem;
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 22px;
            background: linear-gradient(120deg, rgba(255,255,255,0.95), rgba(239, 246, 255, 0.9));
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
            backdrop-filter: blur(10px);
        }

        .page-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.7rem;
            margin-bottom: 0.6rem;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.08);
            color: var(--bs-primary);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .page-intro {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        .page-section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.4rem;
        }

        .card {
            position: relative;
            z-index: 1;
        }

        #sidebar ul li a {
            position: relative;
        }

        #sidebar ul li a::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 50%;
            width: 4px;
            height: 4px;
            border-radius: 999px;
            background: transparent;
            transform: translateY(-50%);
            transition: all 0.2s ease;
        }

        #sidebar ul li a:hover::before,
        #sidebar ul li.active > a::before {
            background: var(--bs-primary);
            height: 16px;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #0f172a;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .table thead th {
            text-transform: uppercase;
            font-size: 0.78rem;
            letter-spacing: 0.04em;
        }

        .auth-card {
            border-radius: 0;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .page-hero {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Loading Overlay -->
    <div id="loadingOverlay">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
        <h5 class="mt-3 text-primary">Memproses...</h5>
    </div>

    @auth
    <div class="d-flex w-100 align-items-stretch">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h4 class="mb-0 fw-bold"><i class="bi bi-mortarboard-fill"></i> SIMTA</h4>
                <small>Piksi Input Serang</small>
            </div>
            <ul class="list-unstyled components">
                <li>
                    <a href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
                </li>
                
                @if(auth()->user()->isAdmin())
                    <li class="px-3 mt-3 mb-1 text-muted text-uppercase small fw-bold">Master Data</li>
                    <li><a href="{{ route('mahasiswa.index') }}"><i class="bi bi-people-fill"></i> Mahasiswa</a></li>
                    <li><a href="{{ route('dosen.index') }}"><i class="bi bi-person-badge-fill"></i> Dosen</a></li>
                    <li><a href="{{ route('admin.relasi') }}"><i class="bi bi-diagram-3-fill"></i> Relasi Dosen & Mahasiswa</a></li>
                    <li><a href="{{ route('admin.progress') }}"><i class="bi bi-clock-history"></i> Pantau Progress</a></li>
                    <li class="px-3 mt-3 mb-1 text-muted text-uppercase small fw-bold">Manajemen TA</li>
                    <li><a href="{{ route('admin.pendaftaran-ta.index') }}"><i class="bi bi-ui-checks"></i> Verifikasi Pendaftaran</a></li>
                @endif

                @if(auth()->user()->isMahasiswa())
                    <li class="px-3 mt-3 mb-1 text-muted text-uppercase small fw-bold">Manajemen TA</li>
                @endif

                @if(auth()->user()->isAdmin() || auth()->user()->isMahasiswa())
                    <li><a href="{{ route('pengajuan.index') }}"><i class="bi bi-file-earmark-text-fill"></i> Pengajuan Judul</a></li>
                @endif
                
                @if(!auth()->user()->isAdmin())
                    <li><a href="{{ route('bimbingan.index') }}"><i class="bi bi-chat-dots-fill"></i> Monitoring Progress</a></li>
                @endif
                <li><a href="{{ route('sidang.index') }}"><i class="bi bi-calendar-check-fill"></i> Jadwal Sidang</a></li>
                
                @if(auth()->user()->isAdmin())
                    <li><a href="{{ route('laporan.index') }}"><i class="bi bi-printer-fill"></i> Laporan</a></li>
                @endif
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <div class="top-navbar">
                <button type="button" id="sidebarCollapse" class="btn btn-primary d-md-none">
                    <i class="bi bi-list"></i>
                </button>
                <div class="ms-auto d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle border-0 shadow-sm rounded-pill px-3 py-2" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle text-primary fs-5 me-1"></i>
                            <span class="fw-semibold">{{ auth()->user()->name }}</span>
                            <span class="badge bg-primary ms-1">{{ auth()->user()->role->nama_role }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userMenu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal"><i class="bi bi-key me-2"></i> Ubah Password</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="main-content page-shell">
                @yield('content')
            </div>
        </div>
    </div>
    @else
        <!-- Guest Layout -->
        @yield('content')
    @endauth

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
          <div class="modal-header border-bottom-0">
            <h5 class="modal-title fw-bold" id="changePasswordModalLabel"><i class="bi bi-key-fill text-primary me-2"></i>Ubah Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="6">
                </div>
            </div>
            <div class="modal-footer border-top-0 bg-light rounded-bottom">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function () {
            // Sidebar Toggle
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar, #content').toggleClass('active');
            });

            // Initialize DataTables
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                }
            });

            // Global Form Submit Loading Spinner
            $('form').on('submit', function() {
                // Don't show loading on logout or specific forms if needed
                if(!$(this).hasClass('no-loading')) {
                    $('#loadingOverlay').css('display', 'flex');
                    $(this).find('button[type="submit"]').prop('disabled', true);
                }
            });
            
            // Highlight active menu
            let currentUrl = window.location.href;
            $('#sidebar ul li a').each(function() {
                if(this.href === currentUrl) {
                    $(this).parent().addClass('active');
                }
            });

            // SweetAlert for Session Messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: '<ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                });
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>