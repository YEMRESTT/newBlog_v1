<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Blog Yönetim Paneli')</title>
    <meta name="description" content="Modern Blog Yönetim Sistemi">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">


    <style>
        :root {
            --sidebar-width: 280px;
            --navbar-height: 60px;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Navbar Styles */
        .navbar-custom {
            height: var(--navbar-height);
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background-color: #ffffff;
            border-right: 1px solid #e9ecef;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .sidebar .nav-link {
            padding: 0.75rem 1.5rem;
            color: #495057;
            border-left: 3px solid transparent;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        /* Hover Animasyonu */
        .sidebar .nav-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            border-left-color: #764ba2;
            transform: translateX(8px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }

        /* Hover'da icon animasyonu */
        .sidebar .nav-link:hover i {
            transform: scale(1.15) rotate(5deg);
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
        }

        /* Aktif link stilleri */
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            border-left-color: #764ba2;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* Icon genel stiller */
        .sidebar .nav-link i {
            font-size: 1.25rem;
            width: 24px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Parlama efekti için pseudo element */
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        /* Hover'da parlama efekti */
        .sidebar .nav-link:hover::before {
            left: 100%;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 2rem;
            min-height: calc(100vh - var(--navbar-height));
        }

        /* User Info Card */
        .user-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }

        .user-info-card .user-name {
            font-weight: 600;
            font-size: 1rem;
        }

        .user-info-card .badge {
            background-color: rgba(255,255,255,0.25);
            color: white;
            padding: 0.35rem 0.75rem;
            font-weight: 500;
        }

        /* Content Card */
        .content-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .content-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-bottom: none;
        }

        .content-card-subtitle {
            background-color: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .content-card-body {
            padding: 2rem 1.5rem;
        }

        /* Footer */
        .footer {
            background-color: #ffffff;
            border-top: 1px solid #e9ecef;
            padding: 1.5rem 0;
            margin-top: 3rem;
        }

        /* Mobile Toggle Button */
        .sidebar-toggle {
            display: none;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .overlay {
                display: none;
                position: fixed;
                top: var(--navbar-height);
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0,0,0,0.5);
                z-index: 999;
            }

            .overlay.show {
                display: block;
            }
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container-fluid">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="btn btn-link text-white sidebar-toggle me-3" type="button" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Brand Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{route('home')}}">
            <i class="bi bi-journal-richtext me-2 fs-3"></i>
            Blog Yönetim
        </a>

        <!-- User Info in Navbar (Desktop) -->
        @if(Auth::check())
            <div class="ms-auto d-none d-lg-flex align-items-center">
                <div class="text-white me-3">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <strong>{{ Auth::user()->name }}</strong>
                </div>
                <span class="badge bg-light text-primary">
                    {{ Auth::user()->getRoleNames()->first() }}
                </span>
            </div>
        @endif
    </div>
</nav>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h6 class="text-muted text-uppercase mb-0">Menü</h6>
    </div>

    <nav class="nav flex-column">
        <!-- Admin Paneli Link -->
        @can('admin paneli görüntüle')
        <a href="{{route('posts.index')}}" class="nav-link">
            <i class="bi bi-speedometer2"></i>
            <span>Admin Paneli</span>
        </a>
        @endcan

        <!-- Paylaşımlar Link -->
        @can('paylaşımlar paneli görüntüle')
        <a href="{{route('home')}}" class="nav-link active">
            <i class="bi bi-file-earmark-text"></i>
            <span>Paylaşımlar</span>
        </a>
        @endcan

        <!-- Kullanıcı Listesi Link -->

        @can('kullanıcı listesi paneli görüntüle')
        <a href="{{route('admin.users')}}" class="nav-link">
            <i class="bi bi-people"></i>
            <span>Kullanıcı Listesi</span>
        </a>
        @endcan


        <!-- Rol Düzenle Link -->
        @can('rol düzenle')
        <a href="{{route('admin.permissions')}}" class="nav-link">
            <i class="bi bi-shield-lock"></i>
            <span>Rol Düzenle</span>
        </a>
        @endcan
    </nav>
</aside>

<!-- Overlay for Mobile Sidebar -->
<div class="overlay" id="overlay"></div>

<!-- Main Content Area -->
<main class="main-content">
    <div class="container-fluid">



        <!-- Content Card -->
        <div class="content-card">
            <!-- Main Title Header -->
            <div class="content-card-header">
                <h3 class="mb-0">@yield('main-title')</h3>
            </div>

            <!-- Subtitle Section -->
            @hasSection('subtitle')
                <div class="content-card-subtitle">
                    <h5 class="mb-0 text-muted">@yield('subtitle')</h5>
                </div>
            @endif

            <!-- Main Content Body -->
            <div class="content-card-body">
                @yield('content')
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="text-center text-muted">
                <p class="mb-0">&copy; {{ date('Y') }} Blog Yönetim Sistemi. Tüm hakları saklıdır.</p>
            </div>
        </footer>

    </div>
</main>

<!-- Bootstrap 5.3 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script>
    // Sidebar Toggle Functionality for Mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // Active Link Highlighting
    const currentLocation = window.location.href;
    const menuLinks = document.querySelectorAll('.sidebar .nav-link');

    menuLinks.forEach(link => {
        if (link.href === currentLocation) {
            menuLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        }
    });
</script>

</body>
</html>
