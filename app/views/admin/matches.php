<section class="glass-card p-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="section-heading mb-1">Admin Panel</p>
            <h1 class="h3 fw-bold mb-0">Manage Matches</h1>
            <p class="text-muted mb-0">Add new matches, edit existing ones, or delete matches you no longer need.</p>
        </div>
        <a href="index.php?route=matches/index" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Matches
        </a>
    </div>
    <form method="post" id="match-admin-form" class="row g-3 mb-4">
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
        <input type="hidden" name="id" id="match-id">
        <input type="hidden" name="form_action" id="match-action" value="create">

        <div class="col-md-3">
            <label class="form-label fw-semibold">Sport</label>
            <select class="form-select" name="sport_id" id="match-sport" required>
                <?php foreach ($sports as $sport): ?>
                    <option value="<?= $sport['id']; ?>"><?= htmlspecialchars($sport['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Location</label>
            <select class="form-select" name="location_id" id="match-location" required>
                <?php foreach ($locations as $location): ?>
                    <option value="<?= $location['id']; ?>"><?= htmlspecialchars($location['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Date & time</label>
            <input type="datetime-local" class="form-control" name="date_time" id="match-date" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Tournament (optional)</label>
            <select class="form-select" name="tournament_id" id="match-tournament">
                <option value="">--</option>
                <?php foreach ($tournaments as $tournament): ?>
                    <option value="<?= $tournament['id']; ?>"><?= htmlspecialchars($tournament['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Max players</label>
            <input type="number" class="form-control" name="max_players" id="match-max" min="2" value="10">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Min skill</label>
            <input type="number" class="form-control" name="min_skill_level" id="match-min-skill" min="1" max="5" value="1">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Max skill</label>
            <input type="number" class="form-control" name="max_skill_level" id="match-max-skill" min="1" max="5" value="5">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Status</label>
            <select class="form-select" name="status" id="match-status">
                <option value="open">Open</option>
                <option value="full">Full</option>
                <option value="finished">Finished</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-dark" type="submit">Save match</button>
            <button class="btn btn-outline-secondary" type="button" id="match-reset">Reset</button>
        </div>
    </form>

    <div class="table-responsive table-modern">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Sport</th>
                <th>Date</th>
                <th>Location</th>
                <th>Status</th>
                <th>Participants</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($matches)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No matches found. Create your first match using the form above.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($matches as $match): ?>
                    <tr data-match='<?= htmlspecialchars(json_encode($match), ENT_QUOTES); ?>'>
                        <td><?= htmlspecialchars($match['sport_name'] ?? 'Unknown'); ?></td>
                        <td><?= date('M d H:i', strtotime($match['date_time'])); ?></td>
                        <td><?= htmlspecialchars($match['location_name'] ?? 'Unknown'); ?></td>
                        <td><span class="badge-soft <?= htmlspecialchars($match['status']); ?>"><?= htmlspecialchars($match['status']); ?></span></td>
                        <td>
                            <?php $matchParticipants = $participants[$match['id']] ?? []; ?>
                            <?php if (!$matchParticipants): ?>
                                <em class="text-muted">No participants</em>
                            <?php else: ?>
                                <ul class="list-unstyled small mb-0">
                                    <?php foreach ($matchParticipants as $participant): ?>
                                        <li class="d-flex align-items-center gap-2 mb-2">
                                            <span class="fw-semibold"><?= htmlspecialchars($participant['name']); ?></span>
                                            <select class="form-select form-select-sm participant-result" style="max-width: 130px;"
                                                    data-participant-id="<?= $participant['id']; ?>"
                                                    data-original-value="<?= htmlspecialchars($participant['result']); ?>"
                                                    onchange="window.updateParticipantResult && window.updateParticipantResult(this)">
                                                <?php foreach (['none' => 'Pending', 'win' => 'Win', 'loss' => 'Loss'] as $value => $label): ?>
                                                    <option value="<?= $value; ?>" <?= $participant['result'] === $value ? 'selected' : ''; ?>>
                                                        <?= $label; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-link p-0 me-3 js-edit-match" 
                                    data-match-id="<?= $match['id']; ?>"
                                    data-match-sport="<?= $match['sport_id']; ?>"
                                    data-match-location="<?= $match['location_id']; ?>"
                                    data-match-date="<?= htmlspecialchars($match['date_time']); ?>"
                                    data-match-max="<?= $match['max_players']; ?>"
                                    data-match-min-skill="<?= $match['min_skill_level']; ?>"
                                    data-match-max-skill="<?= $match['max_skill_level']; ?>"
                                    data-match-status="<?= htmlspecialchars($match['status']); ?>"
                                    data-match-tournament="<?= $match['tournament_id'] ?? ''; ?>"
                                    onclick="return window.editMatchFromButton && window.editMatchFromButton(this)">
                                Edit
                            </button>
                            <form method="post" class="d-inline" onsubmit="return confirm('Delete this match?');">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                                <input type="hidden" name="id" value="<?= $match['id']; ?>">
                                <input type="hidden" name="form_action" value="delete">
                                <button class="btn btn-link text-danger p-0" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

