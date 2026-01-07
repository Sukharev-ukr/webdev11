<section class="glass-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="section-heading mb-1">Brackets</p>
            <h1 class="h4 fw-bold mb-0">Manage tournaments</h1>
        </div>
    </div>
    <form method="post" class="row g-3 mb-4">
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
        <input type="hidden" name="id" id="tournament-id">
        <input type="hidden" name="form_action" id="tournament-action" value="create">

        <div class="col-md-4">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" class="form-control" name="name" id="tournament-name" required>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Sport</label>
            <select class="form-select" name="sport_id" id="tournament-sport">
                <?php foreach ($sports as $sport): ?>
                    <option value="<?= $sport['id']; ?>"><?= htmlspecialchars($sport['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Status</label>
            <select class="form-select" name="status" id="tournament-status">
                <option value="upcoming">Upcoming</option>
                <option value="ongoing">Ongoing</option>
                <option value="finished">Finished</option>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Description</label>
            <textarea class="form-control" name="description" id="tournament-description" rows="2"></textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Start date</label>
            <input type="date" class="form-control" name="start_date" id="tournament-start">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">End date</label>
            <input type="date" class="form-control" name="end_date" id="tournament-end">
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-dark" type="submit">Save tournament</button>
            <button class="btn btn-outline-secondary" type="button" id="tournament-reset">Reset</button>
        </div>
    </form>

    <div class="table-responsive table-modern">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Name</th>
                <th>Sport</th>
                <th>Status</th>
                <th>Dates</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tournaments as $tournament): ?>
                <tr data-tournament='<?= htmlspecialchars(json_encode($tournament), ENT_QUOTES); ?>'>
                    <td><?= htmlspecialchars($tournament['name']); ?></td>
                    <td><?= htmlspecialchars($tournament['sport_name']); ?></td>
                    <td><span class="badge-soft <?= htmlspecialchars($tournament['status']); ?>"><?= htmlspecialchars($tournament['status']); ?></span></td>
                    <td><?= htmlspecialchars($tournament['start_date'] ?? 'TBD'); ?> - <?= htmlspecialchars($tournament['end_date'] ?? 'TBD'); ?></td>
                    <td class="text-end">
                        <button class="btn btn-link p-0 me-3 js-edit-tournament" 
                                data-tournament-id="<?= $tournament['id']; ?>"
                                data-tournament-name="<?= htmlspecialchars($tournament['name'] ?? ''); ?>"
                                data-tournament-sport="<?= $tournament['sport_id'] ?? ''; ?>"
                                data-tournament-description="<?= htmlspecialchars($tournament['description'] ?? ''); ?>"
                                data-tournament-status="<?= htmlspecialchars($tournament['status'] ?? 'upcoming'); ?>"
                                data-tournament-start="<?= htmlspecialchars($tournament['start_date'] ?? ''); ?>"
                                data-tournament-end="<?= htmlspecialchars($tournament['end_date'] ?? ''); ?>"
                                onclick="return window.editTournamentFromButton && window.editTournamentFromButton(this)">
                            Edit
                        </button>
                        <form method="post" class="d-inline" onsubmit="return confirm('Delete this tournament?');">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                            <input type="hidden" name="id" value="<?= $tournament['id']; ?>">
                            <input type="hidden" name="form_action" value="delete">
                            <button class="btn btn-link text-danger p-0" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

