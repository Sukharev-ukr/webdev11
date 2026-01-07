<?php if (empty($matches)): ?>
    <div class="alert alert-light border text-center">No matches meet your filters.</div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($matches as $match): ?>
            <div class="col-md-6 col-xl-4">
                <article class="match-card h-100" data-match-id="<?= $match['id']; ?>" data-max-players="<?= $match['max_players']; ?>">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge bg-dark-subtle text-dark mb-2"><?= htmlspecialchars($match['sport_name']); ?></span>
                            <h3 class="h5 fw-semibold mb-0"><?= htmlspecialchars($match['location_name']); ?></h3>
                            <p class="text-muted small mb-0"><?= htmlspecialchars($match['location_city']); ?></p>
                        </div>
                        <span class="badge rounded-pill text-bg-dark">
                            <?= date('M d · H:i', strtotime($match['date_time'])); ?>
                        </span>
                    </div>
                    <div class="match-meta mb-3">
                        <span><i class="bi bi-graph-up"></i>Skill <?= htmlspecialchars($match['min_skill_level']); ?> - <?= htmlspecialchars($match['max_skill_level']); ?></span>
                        <span><i class="bi bi-people"></i><strong class="js-match-slots"><?= max(0, $match['available_slots']); ?></strong> slots left</span>
                        <span><i class="bi bi-flag"></i><?= htmlspecialchars($match['status']); ?></span>
                    </div>
                    <div class="d-grid">
                        <?php if ($currentUser): ?>
                            <?php if ($match['user_joined']): ?>
                                <button class="btn btn-outline-secondary js-leave-match" 
                                        data-match-id="<?= $match['id']; ?>"
                                        onclick="window.leaveMatch && window.leaveMatch(<?= $match['id']; ?>, this)">
                                    <i class="bi bi-x-circle me-1"></i>Leave match
                                </button>
                            <?php else: ?>
                                <button class="btn btn-dark js-join-match" 
                                        data-match-id="<?= $match['id']; ?>" 
                                        <?= $match['available_slots'] <= 0 ? 'disabled' : ''; ?>
                                        onclick="window.joinMatch && window.joinMatch(<?= $match['id']; ?>, this)">
                                    <i class="bi bi-plus-circle me-1"></i>Join match
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <a class="btn btn-outline-dark" href="index.php?route=auth/login">
                                Login to join
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

