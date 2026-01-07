<section class="glass-card p-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <p class="section-heading mb-1">Find your spot</p>
            <h1 class="h3 fw-bold mb-0">Upcoming matches</h1>
            <p class="text-muted mb-0">Filter by sport, location, or date to instantly join a session.</p>
        </div>
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <?php if (is_admin()): ?>
                <a href="index.php?route=admin/matches" class="btn btn-dark btn-sm">
                    <i class="bi bi-gear me-1"></i>Manage Matches (Admin)
                </a>
            <?php endif; ?>
            <form id="match-filter-form" class="d-flex flex-wrap gap-3 filters-pills" method="get" onsubmit="return false;">
            <input type="hidden" name="route" value="matches/index">
            <select class="form-select" name="sport" onchange="window.applyMatchFilters && window.applyMatchFilters()">
                <option value="">All sports</option>
                <?php foreach ($sports as $sport): ?>
                    <option value="<?= $sport['id']; ?>" <?= isset($_GET['sport']) && $_GET['sport'] == $sport['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($sport['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select class="form-select" name="location" onchange="window.applyMatchFilters && window.applyMatchFilters()">
                <option value="">All locations</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?= $location['id']; ?>" <?= isset($_GET['location']) && $_GET['location'] == $location['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($location['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input class="form-control" type="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? ''); ?>" onchange="window.applyMatchFilters && window.applyMatchFilters()">
        </form>
        </div>
    </div>

    <div id="match-list" data-user="<?= $currentUser ? '1' : '0'; ?>">
        <?php include base_path('views/matches/partials/list.php'); ?>
    </div>
</section>

