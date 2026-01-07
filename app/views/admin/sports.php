<section class="glass-card p-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="section-heading mb-1">Taxonomy</p>
            <h1 class="h4 fw-bold mb-0">Manage sports</h1>
        </div>
    </div>
    <form method="post" class="row g-3 mb-4">
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
        <input type="hidden" name="id" id="sport-id">
        <input type="hidden" name="form_action" id="sport-action" value="create">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" class="form-control" name="name" id="sport-name" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Description</label>
            <input type="text" class="form-control" name="description" id="sport-description">
        </div>
        <div class="col-md-2 d-flex align-items-end gap-2">
            <button class="btn btn-dark w-100" type="submit">Save sport</button>
            <button class="btn btn-outline-secondary w-100" type="button" id="sport-reset">Reset</button>
        </div>
    </form>

    <div class="table-responsive table-modern">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($sports as $sport): ?>
                <tr data-sport='<?= htmlspecialchars(json_encode($sport), ENT_QUOTES); ?>'>
                    <td class="fw-semibold"><?= htmlspecialchars($sport['name']); ?></td>
                    <td><?= htmlspecialchars($sport['description']); ?></td>
                    <td class="text-end">
                        <button class="btn btn-link p-0 me-3 js-edit-sport">Edit</button>
                        <form method="post" class="inline-form" onsubmit="return confirm('Delete this sport?');">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
                            <input type="hidden" name="id" value="<?= $sport['id']; ?>">
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

