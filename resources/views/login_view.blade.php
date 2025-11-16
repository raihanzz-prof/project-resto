<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login RestoRehan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: "Poppins", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #7979e6ff; /* polos, bisa kamu ganti */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
            padding: 32px 28px;
            width: 100%;
            max-width: 380px;
        }

        .login-title {
            font-weight: 700;
            color: #4b5cf2;
            margin-bottom: 8px;
            text-align: center;
        }

        .login-subtitle {
            font-size: 0.9rem;
            color: #6b7280;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #374151;
        }

        .form-control:focus {
            border-color: #4b5cf2;
            box-shadow: 0 0 0 0.15rem rgba(75, 92, 242, 0.25);
        }

        .btn-login {
            background: linear-gradient(90deg, #6f8df6, #4b5cf2);
            color: #ffffff;
            font-weight: 600;
            border: none;
            border-radius: 10px;
        }

        .btn-login:hover {
            filter: brightness(1.05);
        }

        .footer-text {
            margin-top: 18px;
            font-size: 0.8rem;
            color: #9ca3af;
            text-align: center;
        }

        .footer-text span {
            font-weight: 600;
            color: #4b5cf2;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2 class="login-title">🍽️ RestoRehan</h2>
        <div class="login-subtitle">Masuk ke dashboard restoran Anda</div>

        @if(session('error'))
            <div class="alert alert-danger text-center py-2">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="mt-2">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="Masukkan email Anda"
                    required
                    autofocus
                >
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Masukkan password Anda"
                    required
                >
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-login w-100 py-2 mt-2">
                Masuk
            </button>
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} <span>RestoRehan</span>
        </div>
    </div>

    {{-- Kalau nggak butuh komponen JS Bootstrap (modal, dropdown), ini boleh dihapus --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
