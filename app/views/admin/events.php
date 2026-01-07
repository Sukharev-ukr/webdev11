<section class="glass-card p-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="section-heading mb-1">Spotlight</p>
            <h1 class="h4 fw-bold mb-0">Manage community events</h1>
        </div>
    </div>
    <form method="post" class="row g-3 mb-4">
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
        <input type="hidden" name="id" id="event-id">
        <input type="hidden" name="form_action" id="event-action" value="create">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Title</label>
            <input type="text" class="form-control" name="title" id="event-title" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Date & time</label>
            <input type="datetime-local" class="form-control" name="start_at" id="event-start" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">City</label>
            <input type="text" class="form-control" name="city" id="event-city">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Venue</label>
            <input type="text" class="form-control" name="venue" id="event-venue">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">External link</label>
            <input type="url" class="form-control" name="link" id="event-link" placeholder="https://">
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Description</label>
            <textarea class="form-control" name="description" id="event-description" rows=2></textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-dark" type="submit">Save event</button>
            <button class="btn btn-outline-secondary" type="button" id="event-reset">Reset</button>
        </div>
    </form>

    <div class="table-responsive table-modern">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Title</th>
                <th>Schedule</th>
                <th>Location</th>
                <th>Link</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($events as $event): ?>
                <tr data-event='<?= htmlspecialchars(json_encode($event), ENT_QUOTES); ?>'>
                    <td class="fw-semibold"><?= htmlspecialchars($event['title']); ?></td>
                    <td><?= date('M d, Y · H:i', strtotime($event['start_at'])); ?></td>
                    <td><?= htmlspecialchars(trim(($event['venue'] ? $event['venue'] . ', ' : '') . ($event['city'] ?? ''))); ?></td>
                    <td>
                        <?php if (!empty($event['link'])): ?>
                            <a href="<?= htmlspecialchars($event['link']); ?>" target="_blank" rel="noreferrer">Open</a>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-link p-0 me-3 js-edit-event" 
                                data-event-id="<?= $event['id']; ?>"
                                data-event-title="<?= htmlspecialchars($event['title'] ?? ''); ?>"
                                data-event-description="<?= htmlspecialchars($event['description'] ?? ''); ?>"
                                data-event-start="<?= htmlspecialchars($event['start_at'] ?? ''); ?>"
                                data-event-venue="<?= htmlspecialchars($event['venue'] ?? ''); ?>"
                                data-event-city="<?= htmlspecialchars($event['city'] ?? ''); ?>"
                                data-event-link="<?= htmlspecialchars($event['link'] ?? ''); ?>"
                                onclick="return window.editEventFromButton && window.editEventFromButton(this)">
                            Edit
                        </button>
                        <form method="post" class="d-inline" onsubmit="return confirm('Delete this event?');">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                            <input type="hidden" name="id" value="<?= $event['id']; ?>">
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

