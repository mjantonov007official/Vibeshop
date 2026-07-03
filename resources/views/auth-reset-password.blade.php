<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | THREADLAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,500;0,700;0,800;1,700;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <main class="customer-register-page">
        <section class="customer-register-visual">
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAfW8aakj1ITa020I4Wo6OfjLAD1M08JZOtbMNmSJJjZ9js9pS39TeE7wQhipMstE4WjpSTbb4UCJSEKM70bbdagvCwU_cNg-D0gtEgqJtGOVkeWwpfaruGMcKXZJcIYLR-6-5H-2FztLNIDtuo1attZ7u7PYLb9A4BRRW671tZh_XuFjE1a5OIe-R52kBsr0caRPsqKeFaM-VfLG6FKb0yKUXx2JpMR1c-jn2YxZamY1omIDCLKMV2oSrHdxMpHHVIuffWqi5EEtU" alt="Editorial streetwear model">
            <div class="customer-register-copy">
                <p>Secure Update</p>
                <h1>Choose A<br>New Password</h1>
                <div>
                    <span aria-hidden="true"></span>
                    <strong>THREADLAB Account Reset</strong>
                </div>
            </div>
        </section>

        <section class="customer-register-panel" aria-label="Reset password form">
            <div class="customer-register-card">
                <header>
                    <h2>Reset Password</h2>
                    <p>Create a new password for your account.</p>
                </header>

                <form action="{{ route('password.store') }}" method="POST" class="customer-register-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <label>
                        <span>Email Address</span>
                        <input type="email" name="email" placeholder="IDENTITY@THREADLAB.COM" value="{{ old('email', $email) }}" required>
                    </label>
                    <div class="customer-register-grid">
                        <label>
                            <span>Password</span>
                            <input type="password" name="password" placeholder="New Password" required>
                        </label>
                        <label>
                            <span>Confirm</span>
                            <input type="password" name="password_confirmation" placeholder="New Password" required>
                        </label>
                    </div>
                    <button type="submit">Reset Password</button>
                </form>
                @if ($errors->any())
                    <p class="form-error">{{ $errors->first() }}</p>
                @endif

                <footer class="customer-register-footer">
                    <p><a href="{{ route('customer.login') }}">Back to Login</a></p>
                    <div>
                        <span>Encrypted</span>
                        <span>Verified Link</span>
                    </div>
                </footer>
            </div>
        </section>
    </main>
</body>
</html>
