<?php $user = current_user(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token(); ?>">
    <title>SquadSport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css'); ?>">
</head>
<body>
<header class="navbar navbar-expand-lg navbar-dark bg-gradient shadow-sm">
    <div class="container py-2">
        <a class="navbar-brand fw-bold text-uppercase tracking-wide" href="index.php?route=home/index">
            <i class="bi bi-lightning-charge-fill me-2 text-warning"></i>SquadSport
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="btn btn-dark btn-sm" href="index.php?route=matches/index">Matches</a></li>
                <li class="nav-item"><a class="btn btn-dark btn-sm" href="index.php?route=tournament/index">Tournaments</a></li>
                <li class="nav-item"><a class="btn btn-dark btn-sm" href="index.php?route=events/index">Events</a></li>
                <?php if ($user): ?>
                    <li class="nav-item"><a class="btn btn-dark btn-sm" href="index.php?route=user/profile">My Profile</a></li>
                    <li class="nav-item"><a class="btn btn-dark btn-sm" href="index.php?route=matches/history">History</a></li>
                <?php endif; ?>
                <?php if (is_admin()): ?>
                    <li class="nav-item">
                        <a class="btn btn-warning btn-sm text-dark fw-semibold" href="index.php?route=admin/matches">
                            <i class="bi bi-gear me-1"></i>Manage Matches
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold text-warning" href="#" id="adminNav" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #facc15 !important;">
                            <i class="bi bi-shield-lock me-1"></i>Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminNav">
                            <li><a class="dropdown-item" href="index.php?route=admin/dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item fw-semibold" href="index.php?route=admin/matches"><i class="bi bi-calendar-event me-2"></i>Manage Matches</a></li>
                            <li><a class="dropdown-item" href="index.php?route=admin/tournaments"><i class="bi bi-trophy me-2"></i>Tournaments</a></li>
                            <li><a class="dropdown-item" href="index.php?route=admin/events"><i class="bi bi-star me-2"></i>Events</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <li class="nav-item ms-lg-3">
                    <?php if ($user): ?>
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 text-white">
                            <span class="small text-white-50">Hi, <?= htmlspecialchars($user['name']); ?></span>
                            <a class="btn-dark btn-sm" href="index.php?route=auth/logout">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="d-flex gap-2">
                            <a class="btn-dark btn-sm" href="index.php?route=auth/login">Login</a>
                            <a class="btn btn-warning btn-sm text-dark fw-semibold" href="index.php?route=auth/register">Join Squad</a>
                        </div>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</header>

<main class="container py-4">
    <?php if ($message = flash('error')): ?>
        <div class="alert alert-danger shadow-sm"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($message = flash('success')): ?>
        <div class="alert alert-success shadow-sm"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

