<section class="auth-card">
    <div class="text-center mb-4">
        <span class="admin-chip mb-2">New here?</span>
        <h1 class="mb-2">Create your SquadSport profile</h1>
        <p class="text-muted mb-0">Track skills, unlock tournaments, and join curated pick-up games.</p>
    </div>
    <form method="post" class="row g-3">
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="regName" placeholder="Alex Morgan" name="name" required>
                <label for="regName">Full name</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="email" class="form-control" id="regEmail" placeholder="you@email.com" name="email" required>
                <label for="regEmail">Email</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="regCity" placeholder="Amsterdam" name="city">
                <label for="regCity">City</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="password" class="form-control" id="regPassword" placeholder="•••••••" name="password" minlength="6" required>
                <label for="regPassword">Password (min 6 chars)</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <textarea class="form-control" placeholder="Tell teams about your play style" id="regDescription" name="description" style="height: 120px"></textarea>
                <label for="regDescription">Describe your play style</label>
            </div>
        </div>
        <div class="col-12 d-grid">
            <button class="btn btn-warning text-dark fw-semibold btn-lg" type="submit">
                <i class="bi bi-person-plus-fill me-2"></i>Create account
            </button>
        </div>
    </form>
    <p class="mt-4 text-center text-muted">Already have an account?
        <a class="fw-semibold" href="index.php?route=auth/login">Login</a>
    </p>
</section>

