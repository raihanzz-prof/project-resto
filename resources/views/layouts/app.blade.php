<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'RestoRehan')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>

    <style>
        /* ====== Base Look & Feel (Versi Sederhana) ====== */
        :root{
            --brand-1: #7ea8f8;  /* calm blue */
            --brand-2: #705df2;  /* energetic purple */
            --ink-1: #2b2f38;    /* heading */
            --ink-2: #667085;    /* body */
        }

        html, body {
            height: 100%;
        }

        body{
            /* Background polos saja */
            background-color: #7979e6ff; /* bisa ganti ke warna lain */
            color: var(--ink-1);
        }

        /* ====== Navbar Sederhana ====== */
        .navbar{
            background-color: #1f2933 !important;
            padding: .75rem 0;
        }
        .navbar-brand{
            font-weight: 800;
            letter-spacing:.3px;
            color: #f9fafb !important;
        }
        .nav-link{
            color: #e7e9ff !important;
            opacity: .9;
        }
        .nav-link:hover{
            opacity: 1;
        }

        /* ====== Content Card ====== */
        .page-wrap{
            min-height: calc(100vh - 140px); /* viewport minus navbar+footer */
        }
        .content-card{
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .08);
        }
        .page-header{
            color: #334155;
        }

        /* ====== Forms / Buttons polish ====== */
        .form-control:focus{
            border-color: var(--brand-2);
            box-shadow: 0 0 0 .15rem rgba(112,93,242,.25);
        }
        .btn-gradient{
            background: linear-gradient(90deg, #6f8df6, #4b5cf2);
            border: none;
            color: #fff;
            box-shadow: 0 8px 18px rgba(75,92,242,.25);
        }
        .btn-gradient:hover{
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(75,92,242,.35);
        }

        /* ====== Footer ====== */
        footer{
            color: #4b5563;
            opacity: .9;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">RestoRehan</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="mainNav" class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    @auth
                        {{-- Contoh nav (opsional) --}}
                        {{-- <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard')?'active':'' }}" 
                               href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li> --}}
                        <li class="nav-item ms-lg-2">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button class="btn btn-sm btn-gradient px-3">Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- PAGE -->
    <main class="page-wrap py-4 py-md-5">
        <div class="container">
            {{-- Alert/flash message --}}
            @if(session('success'))
                <div class="alert alert-success content-card border-0 px-4 py-3 mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger content-card border-0 px-4 py-3 mb-4">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Satu card saja: header (title/subtitle) + content --}}
            <div class="content-card p-4 p-md-5 mb-4">
                @hasSection('page_title')
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h1 class="page-header h3 fw-bold m-0">@yield('page_title')</h1>

                            {{-- (Opsional) aksi cepat di header halaman --}}
                            @hasSection('page_actions')
                                <div class="d-flex gap-2">@yield('page_actions')</div>
                            @endif
                        </div>
                        @yield('page_subtitle')
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="text-center pb-3">
        <small>&copy; {{ date('Y') }} RestoRehan — crafted with taste.</small>
    </footer>

    <!-- Kalau mau benar-benar tanpa JS sama sekali, boleh hapus script di bawah -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
