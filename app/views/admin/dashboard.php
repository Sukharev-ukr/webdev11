<section class="glass-card p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="section-heading mb-1">Control center</p>
            <h1 class="h4 fw-bold mb-0">Admin dashboard</h1>
            <p class="text-muted mb-0">Monitor activity, manage matches, and curate tournaments.</p>
        </div>
        <span class="badge rounded-pill text-bg-dark">Status · Operational</span>
    </div>
    <div class="stats-grid">
        <div class="stat-card">
            <span>Players</span>
            <strong><?= $stats['users']; ?></strong>
        </div>
        <div class="stat-card">
            <span>Matches</span>
            <strong><?= $stats['matches']; ?></strong>
        </div>
        <div class="stat-card">
            <span>Tournaments</span>
            <strong><?= $stats['tournaments']; ?></strong>
        </div>
    </div>
</section>

<section class="row g-4">
    <div class="col-lg-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-semibold mb-0">Upcoming matches</h2>
                <a class="btn btn-dark btn-sm" href="index.php?route=admin/matches">
                    <i class="bi bi-pencil-square me-1"></i>Manage Matches
                </a>
            </div>
            <ul class="list-group list-group-flush">
                <?php foreach ($matches as $match): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <?= htmlspecialchars($match['sport_name']); ?>
                            <p class="mb-0 text-muted small"><?= htmlspecialchars($match['location_name']); ?></p>
                        </div>
                        <span class="badge rounded-pill text-bg-dark"><?= date('M d H:i', strtotime($match['date_time'])); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-semibold mb-0">Tournaments</h2>
                <a class="btn btn-outline-dark btn-sm" href="index.php?route=admin/tournaments">Manage</a>
            </div>
            <ul class="list-group list-group-flush">
                <?php foreach ($tournaments as $tournament): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($tournament['name']); ?>
                        <span class="badge-soft <?= htmlspecialchars($tournament['status']); ?>"><?= htmlspecialchars($tournament['status']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>

