<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna - Stockify</title>
    <style>
        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 26px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
        }

        label {
            display: block;
            margin: 18px 0 8px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            margin-top: 24px;
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            cursor: pointer;
            font-size: 15px;
        }

        .error {
            margin-top: 6px;
            color: #dc2626;
            font-size: 13px;
        }

        .password-wrapper {
            position: relative;
            margin-top: 7px;
            margin-bottom: 16px;
        }

        .password-wrapper input {
            width: 100%;
            box-sizing: border-box;
            padding: 11px 45px 11px 12px;
            margin-top: 0;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
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
    <div class="container">
        <p><a href="{{ route('users.index') }}">← Kembali ke Kelola Pengguna</a></p>

        <h1>Tambah Pengguna</h1>
        <p>Buat akun baru untuk Admin atau Staff.</p>

        <div class="card">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <label for="name">Nama Lengkap</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required>
                @error('name')
                <div class="error">{{ $message }}</div>
                @enderror

                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required>
                @error('email')
                <div class="error">{{ $message }}</div>
                @enderror


                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        minlength="8"
                        autocomplete="new-password"
                        required>

                    <button type="button" class="toggle-password"
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

                <label for="password_confirmation">Konfirmasi Password</label>
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
                            <path d="M3 3l18 18" />
                            <path d="M10.6 10.6a2 2 0 002.8 2.8" />
                            <path d="M9.9 5.2A11 11 0 0112 5
                c6.4 0 10 7 10 7a16 16 0 01-4 4.8" />
                            <path d="M6.2 6.2C3.5 8 2 12 2 12
                s3.6 7 10 7a10 10 0 003.1-.5" />
                        </svg>
                    </button>
                </div>

                <label for="role">Role Pengguna</label>
                <select id="role" name="role" required>
                    <option value="staff" {{ old('role', 'staff') === 'staff' ? 'selected' : '' }}>
                        Staff
                    </option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>
                </select>
                @error('role')
                <div class="error">{{ $message }}</div>
                @enderror

                <button type="submit">Simpan Pengguna</button>
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