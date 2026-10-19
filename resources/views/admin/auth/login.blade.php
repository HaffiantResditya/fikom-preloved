<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - FIKOM PRELOVED</title>
    <!-- Menghubungkan ke file CSS Terpisah -->
    <link rel="stylesheet" href="{{ asset('admin-assets/style/login.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('admin-assets/assets/Logo.png') }}">
    <style>
        .alert-error {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            padding: 0.65rem 0.85rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-status {
            background-color: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            padding: 0.65rem 0.85rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            margin-bottom: 1rem;
        }
        .input-icon svg {
            width: 14px;
            height: 14px;
            display: block;
        }
        .toggle-password {
            outline: none;
            display: flex;
            align-items: center;
        }
        .toggle-password svg {
            width: 15px;
            height: 15px;
        }
    </style>
</head>
<body>

    <main class="auth-card">
        <section class="auth-body">
            <!-- LOGO -->
            <figure class="logo-container">
                <a href="{{ route('admin.login') }}">
                    <img src="{{ asset('admin-assets/assets/Logo.png') }}" 
                         alt="Logo FIKOM PRELOVED Pasar Preloved Mahasiswa"
                         class="logo-img">
                </a>
            </figure>

            <!-- HEADER -->
            <header class="auth-header">
                <h1 class="auth-title">MASUK KE ADMIN</h1>
            </header>

            <!-- STATUS / ERROR NOTIFICATION -->
            @if (session('status'))
                <div class="alert-status">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert-error">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    <span>⚠️</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- FORMULIR -->
            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf

                <!-- Email Kampus -->
                <div class="form-group">
                    <label for="email-kampus" class="form-label">Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email" 
                               id="email-kampus" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="admin@mhs.udb.ac.id" 
                               required 
                               autofocus
                               class="form-input">
                    </div>
                </div>

                <!-- Kata Sandi -->
                <div class="form-group">
                    <label for="kata-sandi" class="form-label">Kata Sandi</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" 
                               id="kata-sandi" 
                               name="password" 
                               placeholder="Masukkan kata sandi" 
                               required 
                               class="form-input" 
                               style="padding-right: 2.25rem;">
                        <button type="button" class="toggle-password" id="togglePasswordBtn" aria-label="Lihat kata sandi">
                            <svg id="eyeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" class="btn-primary" style="margin-top: 1rem;">
                    Masuk
                </button>
            </form>

        </section>

        <!-- FOOTER CARD -->
        <footer class="auth-footer">
            &copy; 2026 FIKOM PRELOVED | All Rights Reserved
        </footer>
    </main>

    <script>
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('kata-sandi');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                toggleBtn.style.color = type === 'text' ? '#1e40af' : '#9ca3af';
            });
        }
    </script>

</body>
</html>
