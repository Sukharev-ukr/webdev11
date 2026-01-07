<section class="glass-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="section-heading mb-1">Courts & venues</p>
            <h1 class="h4 fw-bold mb-0">Manage locations</h1>
        </div>
    </div>
    <form method="post" class="row g-3 mb-4">
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
        <input type="hidden" name="id" id="location-id">
        <input type="hidden" name="form_action" id="location-action" value="create">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" class="form-control" name="name" id="location-name" required>
        </div>
        <div class="col-md-5">
            <label class="form-label fw-semibold">Address</label>
            <input type="text" class="form-control" name="address" id="location-address">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">City</label>
            <input type="text" class="form-control" name="city" id="location-city">
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-dark" type="submit">Save location</button>
            <button class="btn btn-outline-secondary" type="button" id="location-reset">Reset</button>
        </div>
    </form>

    <div class="table-responsive table-modern">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>City</th>
                <th class="text-end"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($locations as $location): ?>
                <tr data-location='<?= htmlspecialchars(json_encode($location), ENT_QUOTES); ?>'>
                    <td class="fw-semibold"><?= htmlspecialchars($location['name']); ?></td>
                    <td><?= htmlspecialchars($location['address']); ?></td>
                    <td><?= htmlspecialchars($location['city']); ?></td>
                    <td class="text-end">
                        <button class="btn btn-link p-0 me-3 js-edit-location">Edit</button>
                        <form method="post" class="inline-form" onsubmit="return confirm('Delete this location?');">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                            <input type="hidden" name="id" value="<?= $location['id']; ?>">
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

