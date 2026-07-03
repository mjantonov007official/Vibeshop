<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Register | THREADLAB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,700;0,800;1,700;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="admin-login-body">
    <main class="admin-login-page">
        <section class="admin-login-brand">
            <div class="admin-login-media">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBebLjj5yzCcuoC-afBrxgMXDriYKPB6IeXTtF6TUnGK3FA0gA1UUjk_zfGUYOb-ZowmD_0X0iMDKk01x9XcaxtCtJjYBevo-4X9ldM_dN9aZO9V9DTRX4sK31kLxZYGwUq7lRQ2GP1e7CIJ1PsZoTXITFoQkOBRIKowQSlw85dZHC_N5CQHtNQfjNpfxbCADpp2_E6EG0jdNfaB_Fa1sHVHVqVejjSCWN_ZXI36MlygTC771suexUaTqF9qXvesrVx5uzJeHeRkOk" alt="Editorial streetwear fabric texture">
            </div>
            <div class="admin-login-copy">
                <a href="{{ route('home') }}" class="admin-login-mark">THREADLAB</a>
                <p>Kinetic Editorial Systems</p>
                <h1>Manage Your <span>Store</span> with Confidence</h1>
                <div class="admin-login-statement">
                    Track orders, manage products, and monitor performance in one place. The digital flagship for your kinetic brand evolution.
                </div>
                <div class="admin-login-stats" aria-label="System performance">
                    <article>
                        <strong>99.9%</strong>
                        <span>Uptime Performance</span>
                    </article>
                    <article>
                        <strong>0.02s</strong>
                        <span>Global Latency</span>
                    </article>
                </div>
            </div>
        </section>

        <section class="admin-login-panel" aria-label="Admin registration form">
            <div class="admin-login-glow" aria-hidden="true"></div>
            <div class="admin-login-card">
                <header>
                    <h2>Create Admin Account</h2>
                    <p>Set up your THREADLAB admin access</p>
                </header>

                <form action="{{ route('admin.register.store') }}" method="POST" class="admin-login-form admin-register-form">
                    @csrf
                    <label>
                        <span>Full Name</span>
                        <input type="text" name="full_name" placeholder="Alexander McQueen" value="{{ old('full_name') }}" required>
                    </label>
                    <label>
                        <span>Email Address</span>
                        <input type="email" name="email" placeholder="admin@threadlab.com" value="{{ old('email') }}" required>
                    </label>
                    <div class="admin-register-grid">
                        <label>
                            <span>Password</span>
                            <input type="password" name="password" placeholder="Password" required>
                        </label>
                        <label>
                            <span>Confirm Password</span>
                            <input type="password" name="password_confirmation" placeholder="Password" required>
                        </label>
                    </div>
                    <label class="admin-register-terms">
                        <input type="checkbox" name="terms" required>
                        <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</span>
                    </label>
                    <button type="submit">Register</button>
                </form>
                @if ($errors->any())
                    <p class="form-error">{{ $errors->first() }}</p>
                @endif

                <div class="admin-login-register">
                    <p>Already have an account? <a href="{{ route('admin.login') }}">Login</a></p>
                </div>
            </div>

            <footer class="admin-login-footer">
                <p>(c)2026 THREADLAB KINETIC EDITORIAL</p>
                <nav aria-label="Admin register footer">
                    <a href="#">Support</a>
                    <a href="#">System Status</a>
                </nav>
            </footer>
        </section>
    </main>
</body>
</html>
