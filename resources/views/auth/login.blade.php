<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Stockify</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 32px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .logo {
            margin-bottom: 8px;
            color: #2563eb;
            font-size: 30px;
            font-weight: bold;
            text-align: center;
        }

        .subtitle {
            margin-bottom: 28px;
            color: #64748b;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
        }

        input:focus {
            outline: 2px solid #93c5fd;
            border-color: #2563eb;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: normal;
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .error {
            margin-bottom: 16px;
            padding: 12px;
            border-radius: 8px;
            background: #fee2e2;
            color: #b91c1c;
            font-size: 14px;
        }

        .success {
            margin-bottom: 16px;
            padding: 12px;
            border-radius: 8px;
            background: #dcfce7;
            color: #166534;
            font-size: 14px;
        }

        .password-wrapper {
            position: relative;
            margin-bottom: 18px;
        }

        .password-wrapper input[type="password"],
        .password-wrapper input[type="text"] {
            width: 100%;
            padding: 12px 45px 12px 12px;
            margin-bottom: 0;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            width: auto;
            padding: 4px;
            margin: 0;
            background: transparent;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            background: transparent;
            color: #2563eb;
        }

        .toggle-password svg {
            width: 21px;
            height: 21px;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="logo">Stockify</div>
        <p class="subtitle">Silakan login untuk mengelola stok barang.</p>

        @if (session('success'))
        <div class="success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email"
                autocomplete="username"
                required
                autofocus>


            <label for="password">Password</label>

            <div class="password-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required>

                <button
                    type="button"
                    class="toggle-password"
                    aria-label="Tampilkan password"
                    aria-pressed="false"
                    onclick="togglePassword('password', this)">

                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3l18 18" />
                        <path d="M10.6 10.6a2 2 0 002.8 2.8" />
                        <path d="M9.9 5.2A11 11 0 0112 5
                c6.4 0 10 7 10 7a16 16 0 01-4 4.8" />
                        <path d="M6.2 6.2C3.5 8 2 12 2 12
                s3.6 7 10 7a10 10 0 003.1-.5" />
                    </svg>
                </button>
            </div>


            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Ingat saya
            </label>

            <button type="submit">Login</button>
        </form>
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

            button.innerHTML = isHidden ?
                `<svg viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round">
                   <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
                   <circle cx="12" cy="12" r="3"/>
               </svg>` :
                `<svg viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round">
                   <path d="M3 3l18 18"/>
                   <path d="M10.6 10.6a2 2 0 002.8 2.8"/>
                   <path d="M9.9 5.2A11 11 0 0112 5
                       c6.4 0 10 7 10 7a16 16 0 01-4 4.8"/>
                   <path d="M6.2 6.2C3.5 8 2 12 2 12
                       s3.6 7 10 7a10 10 0 003.1-.5"/>
               </svg>`;
        }
    </script>
</body>

</html>