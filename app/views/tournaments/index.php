<section class="glass-card p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <p class="section-heading mb-1">Elite brackets</p>
            <h1 class="h4 fw-bold mb-0">Tournaments</h1>
        </div>
        <span class="badge rounded-pill text-bg-dark"><?= count($tournaments); ?> scheduled</span>
    </div>
    <?php if (empty($tournaments)): ?>
        <p class="text-muted mb-0">No tournaments to display.</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($tournaments as $tournament): ?>
                <div class="col-md-6">
                    <article class="tournament-card h-100">
                        <header class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-muted small mb-1"><?= htmlspecialchars($tournament['sport_name']); ?></p>
                                <h2 class="h5 fw-semibold mb-0"><?= htmlspecialchars($tournament['name']); ?></h2>
                            </div>
                            <span class="badge-soft <?= htmlspecialchars($tournament['status']); ?>">
                                <?= htmlspecialchars($tournament['status']); ?>
                            </span>
                        </header>
                        <p class="text-muted"><?= htmlspecialchars($tournament['description']); ?></p>
                        <div class="d-flex gap-3 flex-wrap small text-muted mb-3">
                            <span><i class="bi bi-calendar-range me-1"></i><?= htmlspecialchars($tournament['start_date'] ?? 'TBD'); ?></span>
                            <span><i class="bi bi-flag me-1"></i><?= htmlspecialchars($tournament['end_date'] ?? 'TBD'); ?></span>
                        </div>
                        <?php $tournamentRounds = $rounds[$tournament['id']] ?? []; ?>
                        <?php if ($tournamentRounds): ?>
                            <div class="bg-surface">
                                <p class="mb-2 fw-semibold">Rounds</p>
                                <ul class="list-unstyled small mb-0">
                                    <?php foreach ($tournamentRounds as $round): ?>
                                        <li class="d-flex justify-content-between align-items-center border-bottom py-2">
                                            <span>Round <?= $round['round_number']; ?> · Match #<?= $round['match_id']; ?></span>
                                            <span><?= date('M d, H:i', strtotime($round['date_time'])); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

