<section class="auth-card">
    <div class="text-center mb-4">
        <span class="admin-chip mb-2">Welcome back</span>
        <h1 class="mb-2">Login</h1>
        <p class="text-muted mb-0">Log in to manage squads, track history, and claim your spot.</p>
    </div>
    <form method="post" class="row g-3">
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
        <div class="col-12">
            <div class="form-floating">
                <input type="email" class="form-control" id="loginEmail" placeholder="name@example.com" name="email" required>
                <label for="loginEmail">Email address</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <input type="password" class="form-control" id="loginPassword" placeholder="•••••••" name="password" required>
                <label for="loginPassword">Password</label>
            </div>
        </div>
        <div class="col-12 d-grid">
            <button class="btn btn-warning text-dark fw-semibold btn-lg" type="submit">
                <i class="bi bi-unlock me-2"></i>Login
            </button>
        </div>
    </form>
    <p class="mt-4 text-center text-muted">Don't have an account?
        <a class="fw-semibold" href="index.php?route=auth/register">Register</a>
    </p>
</section>

