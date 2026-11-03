<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - Stockify</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            padding: 24px;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
        }

        label {
            display: block;
            margin-top: 16px;
            font-weight: bold;
        }

        input {
            box-sizing: border-box;
            width: 100%;
            padding: 11px;
            margin-top: 7px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
        }

        button {
            margin-top: 20px;
            padding: 11px 16px;
            background: #2563eb;
            color: white;
            border: 0;
            border-radius: 7px;
            cursor: pointer;
        }

        .success {
            color: #15803d;
        }

        .error {
            color: #dc2626;
        }

        .password-wrapper {
            position: relative;
            margin-top: 7px;
        }

        .password-wrapper input {
            width: 100%;
            box-sizing: border-box;
            padding-right: 48px;
            margin-top: 0;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 4px;
            margin: 0;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: #2563eb;
        }

        .toggle-password svg {
            width: 21px;
            height: 21px;
        }
    </style>
</head>

<body>
    <div class="container">
        <p><a href="{{ route('settings.index') }}">← Kembali ke Pengaturan</a></p>

        <h1>Ganti Password</h1>
        <p>Gunakan password baru yang kuat dan tidak mudah ditebak.</p>

        <div class="card">
            @if (session('success'))
            <p class="success">{{ session('success') }}</p>
            @endif

            @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <form method="POST" action="{{ route('settings.password.update') }}">
                @csrf
                @method('PUT')

                <label for="current_password">Password Lama</label>
                <div class="password-wrapper">
                    <input
                        id="current_password"
                        type="password"
                        name="current_password"
                        autocomplete="current-password"
                        required>
                    <button type="button" class="toggle-password"
                        aria-label="Tampilkan password lama"
                        aria-pressed="false"
                        onclick="togglePassword('current_password', this)">
                        <svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>

                <label for="password">Password Baru</label>
                <div class="password-wrapper">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        minlength="8"
                        autocomplete="new-password"
                        required>
                    <button type="button" class="toggle-password"
                        aria-label="Tampilkan password baru"
                        aria-pressed="false"
                        onclick="togglePassword('password', this)">
                        <svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>

                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <div class="password-wrapper">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        minlength="8"
                        autocomplete="new-password"
                        required>
                    <button type="button" class="toggle-password"
                        aria-label="Tampilkan konfirmasi password"
                        aria-pressed="false"
                        onclick="togglePassword('password_confirmation', this)">
                        <svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>

                <button type="submit">Simpan Password Baru</button>
            </form>
        </div>
    </div>


    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';

            button.setAttribute('aria-pressed', String(isHidden));
            button.setAttribute(
                'aria-label',
                isHidden ? 'Sembunyikan password' : 'Tampilkan password'
            );

            // Ikon mata terbuka atau mata dicoret
            button.innerHTML = isHidden ?
                `<svg viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round">
                   <path d="M3 3l18 18"/>
                   <path d="M10.6 10.6a2 2 0 002.8 2.8"/>
                   <path d="M9.9 5.2A11 11 0 0112 5
                       c6.4 0 10 7 10 7a16 16 0 01-4 4.8"/>
                   <path d="M6.2 6.2C3.5 8 2 12 2 12
                       s3.6 7 10 7a10 10 0 003.1-.5"/>
               </svg>` :
                `<svg viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round">
                   <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
                   <circle cx="12" cy="12" r="3"/>
               </svg>`;
        }
    </script>

</body>

</html>