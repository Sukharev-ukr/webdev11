<section class="hero-card mb-5">
    <div class="row align-items-center">
        <div class="col-lg-8 position-relative">
            <p class="section-heading text-white-50 mb-2">Play smarter · connect faster</p>
            <h1>Find your next squad and own the court.</h1>
            <p class="fs-5 text-white-75 mb-4">
                Discover perfectly matched pick-up games, track your performance, and jump into curated city tournaments.
            </p>
            <div class="hero-cta d-flex flex-wrap gap-3">
                <a class="btn btn-warning text-dark fw-semibold btn-glow" href="index.php?route=matches/index">
                    <i class="bi bi-calendar-check me-2"></i>Browse matches
                </a>
                <?php if (!current_user()): ?>
                    <a class="btn-dark" href="index.php?route=auth/register">Create account</a>
                <?php else: ?>
                    <a class="btn-dark" href="index.php?route=user/profile">Update profile</a>
                    <?php if (is_admin()): ?>
                        <a class="btn btn-dark text-white fw-semibold" href="index.php?route=admin/matches">
                            <i class="bi bi-gear me-2"></i>Manage Matches
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="glass-card p-4 text-dark">
                <p class="text-uppercase small fw-semibold text-secondary mb-1">Today’s pulse</p>
                <h3 class="fw-bold mb-3"><?= count($matches); ?> spotlight matches</h3>
                <ul class="list-unstyled small text-secondary mb-0">
                    <li class="mb-2"><i class="bi bi-people-fill me-2 text-warning"></i>Compete based on skill windows</li>
                    <li class="mb-2"><i class="bi bi-geo-alt-fill me-2 text-warning"></i>Verified courts & indoor arenas</li>
                    <li><i class="bi bi-cpu-fill me-2 text-warning"></i>Smart filters to join instantly</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="row g-4">
    <div class="col-lg-4">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <p class="section-heading mb-1">Match Feed</p>
                    <h2 class="h4 fw-semibold mb-0">Upcoming matches</h2>
                </div>
                <a class="btn btn-link text-decoration-none" href="index.php?route=matches/index">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <?php if (empty($matches)): ?>
                <p class="text-muted mb-0">No matches scheduled yet. Create one from the admin dashboard.</p>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($matches as $match): ?>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= htmlspecialchars($match['sport_name']); ?></strong>
                                <p class="mb-0 text-muted small"><?= htmlspecialchars($match['location_name']); ?></p>
                            </div>
                            <span class="badge rounded-pill text-bg-dark">
                                <?= date('M d · H:i', strtotime($match['date_time'])); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <p class="section-heading mb-1">Elite brackets</p>
                    <h2 class="h4 fw-semibold mb-0">Upcoming tournaments</h2>
                </div>
                <a class="btn btn-link text-decoration-none" href="index.php?route=tournament/index">
                    Explore <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <?php if (empty($tournaments)): ?>
                <p class="text-muted mb-0">No tournaments yet. Check back soon!</p>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($tournaments as $tournament): ?>
                        <div class="list-group-item px-0 d-flex justify-content-between">
                            <div>
                                <strong><?= htmlspecialchars($tournament['name']); ?></strong>
                                <p class="mb-0 text-muted small"><?= htmlspecialchars($tournament['sport_name']); ?></p>
                            </div>
                            <span class="badge-soft <?= htmlspecialchars($tournament['status']); ?>">
                                <?= htmlspecialchars($tournament['status']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <p class="section-heading mb-1">Community</p>
                    <h2 class="h4 fw-semibold mb-0">Featured events</h2>
                </div>
                <?php if (is_admin()): ?>
                    <a class="btn btn-link text-decoration-none" href="index.php?route=admin/events">
                        Manage <i class="bi bi-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php if (empty($events)): ?>
                <p class="text-muted mb-0">No events announced yet.</p>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($events as $event): ?>
                        <div class="list-group-item px-0">
                            <strong><?= htmlspecialchars($event['title']); ?></strong>
                            <p class="mb-1 text-muted small">
                                <?= date('M d · H:i', strtotime($event['start_at'])); ?> — <?= htmlspecialchars($event['city'] ?? $event['venue'] ?? ''); ?>
                            </p>
                            <p class="mb-0 small"><?= htmlspecialchars($event['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

