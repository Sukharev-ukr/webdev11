<section class="glass-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="section-heading mb-1">Track record</p>
            <h1 class="h4 fw-bold mb-0">My match history</h1>
        </div>
        <span class="badge rounded-pill text-bg-dark"><?= count($history); ?> matches played</span>
    </div>
    <?php if (empty($history)): ?>
        <p class="text-muted mb-0">You haven't joined any matches yet.</p>
    <?php else: ?>
        <div class="table-responsive table-modern">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th>Sport</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Result</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($history as $item): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($item['sport_name']); ?></td>
                        <td><?= date('M d, Y · H:i', strtotime($item['date_time'])); ?></td>
                        <td><?= htmlspecialchars($item['location_name']); ?></td>
                        <td>
                            <?php if (!empty($item['result']) && $item['result'] !== 'none'): ?>
                                <span class="badge-soft <?= htmlspecialchars($item['result']); ?>">
                                    <?= htmlspecialchars(ucfirst($item['result'])); ?>
                                </span>
                            <?php else: ?>
                                <span class="badge-soft none text-muted">Pending</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

