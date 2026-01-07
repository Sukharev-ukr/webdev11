<section class="glass-card p-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="section-heading mb-1">Community</p>
            <h1 class="h3 fw-bold mb-0">Upcoming events</h1>
            <p class="text-muted mb-0">City scrimmages, training camps, and community meetups curated by SquadSport.</p>
        </div>
        <?php if (is_admin()): ?>
            <a class="btn btn-dark" href="index.php?route=admin/events"><i class="bi bi-plus-circle me-1"></i>Manage events</a>
        <?php endif; ?>
    </div>
    <?php if (empty($events)): ?>
        <p class="text-muted mb-0">No events announced yet. Check back later!</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($events as $event): ?>
                <div class="col-md-6">
                    <article class="tournament-card h-100">
                        <header class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h2 class="h5 fw-semibold mb-0"><?= htmlspecialchars($event['title']); ?></h2>
                                <p class="mb-0 text-muted small"><?= date('M d, Y · H:i', strtotime($event['start_at'])); ?></p>
                            </div>
                            <?php if (!empty($event['link'])): ?>
                                <a class="btn-dark btn-sm" href="<?= htmlspecialchars($event['link']); ?>" target="_blank" rel="noreferrer">
                                    Details
                                </a>
                            <?php endif; ?>
                        </header>
                        <p class="text-muted"><?= htmlspecialchars($event['description']); ?></p>
                        <p class="mb-1"><i class="bi bi-geo-alt-fill me-1"></i><?= htmlspecialchars(trim(($event['venue'] ? $event['venue'] . ', ' : '') . ($event['city'] ?? ''))); ?></p>
                        <?php if (!empty($event['creator_name'])): ?>
                            <p class="text-muted small mb-0">Hosted by <?= htmlspecialchars($event['creator_name']); ?></p>
                        <?php endif; ?>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

