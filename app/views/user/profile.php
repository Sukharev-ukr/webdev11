<section class="glass-card p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="section-heading mb-1">Personal locker</p>
            <h1 class="h3 fw-bold mb-0">My profile</h1>
            <p class="text-muted mb-0">Update your bio, city, and preferred sports to get matched faster.</p>
        </div>
        <span class="admin-pill"><i class="bi bi-shield-lock me-1"></i>Secure profile</span>
    </div>
    <form method="post" id="profile-form" class="row g-4">
        <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($user['name'] ?? ''); ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">City</label>
            <input type="text" class="form-control" name="city" value="<?= htmlspecialchars($user['city'] ?? ''); ?>">
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">About you</label>
            <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($user['description'] ?? ''); ?></textarea>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label fw-semibold">Update password</label>
            <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
        </div>
        <div class="col-12">
            <div class="mb-3">
                <p class="section-heading mb-1">Skills board</p>
                <h2 class="h5 fw-semibold mb-0">Sports & levels</h2>
            </div>
            <div id="sport-list" class="bg-surface">
                <?php if (!empty($userSports)): ?>
                    <?php foreach ($userSports as $row): ?>
                        <div class="sport-row align-items-end">
                            <div>
                                <label class="form-label small text-muted">Sport</label>
                                <select class="form-select" name="sport_id[]">
                                    <option value="">Select sport</option>
                                    <?php foreach ($sports as $sport): ?>
                                        <option value="<?= $sport['id']; ?>" <?= $sport['id'] == $row['sport_id'] ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($sport['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label small text-muted">Skill (1-5)</label>
                                <input type="number" class="form-control" name="skill_level[]" min="1" max="5" value="<?= htmlspecialchars($row['skill_level']); ?>">
                            </div>
                            <button type="button" class="btn-text js-remove-row">Remove</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="sport-row align-items-end">
                        <div>
                            <label class="form-label small text-muted">Sport</label>
                            <select class="form-select" name="sport_id[]">
                                <option value="">Select sport</option>
                                <?php foreach ($sports as $sport): ?>
                                    <option value="<?= $sport['id']; ?>"><?= htmlspecialchars($sport['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label small text-muted">Skill (1-5)</label>
                            <input type="number" class="form-control" name="skill_level[]" min="1" max="5">
                        </div>
                        <button type="button" class="btn-text js-remove-row">Remove</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-dark btn-lg px-5" type="submit">
                <i class="bi bi-save2 me-2"></i>Save profile
            </button>
        </div>
    </form>
</section>

<template id="sport-row-template">
    <div class="sport-row align-items-end">
        <div>
            <label class="form-label small text-muted">Sport</label>
            <select class="form-select" name="sport_id[]">
                <option value="">Select sport</option>
                <?php foreach ($sports as $sport): ?>
                    <option value="<?= $sport['id']; ?>"><?= htmlspecialchars($sport['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="form-label small text-muted">Skill (1-5)</label>
            <input type="number" class="form-control" name="skill_level[]" min="1" max="5">
        </div>
        <button type="button" class="btn-text js-remove-row">Remove</button>
    </div>
</template>

