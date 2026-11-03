<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - Stockify</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            padding: 24px;
        }

        .container {
            max-width: 650px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 16px;
            font-weight: bold;
        }

        input,
        select {
            box-sizing: border-box;
            width: 100%;
            padding: 11px;
            margin-top: 7px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
        }

        button,
        .button {
            display: inline-block;
            margin-top: 18px;
            padding: 11px 16px;
            border: 0;
            border-radius: 7px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .danger {
            background: #dc2626;
        }

        .error {
            color: #dc2626;
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
        <p><a href="{{ route('users.index') }}">← Kembali ke daftar pengguna</a></p>
        <h1>Edit Pengguna</h1>

        @if ($errors->any())
        <div class="card error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <label for="name">Nama</label>
                <input id="name" name="name" value="{{ old('name', $user->name) }}" required>

                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>

                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                </select>

                <button type="submit">Simpan Perubahan</button>
            </form>
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