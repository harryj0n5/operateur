<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Dashboard opérateur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <aside class="admin-sidebar">
        <div class="brand"><span class="brand-mark">V</span> Vola</div>
        <nav class="admin-nav">
            <a href="<?= site_url('operateur/dashboard') ?>" class="active"><i class="ti ti-layout-dashboard"></i> Tableau de bord</a>
            <a href="<?= site_url('users') ?>"><i class="ti ti-users"></i> Utilisateurs</a>
            <a href="<?= site_url('configurations') ?>"><i class="ti ti-settings"></i> Configurations</a>
            <a href="<?= site_url('type-operations') ?>"><i class="ti ti-category"></i> Types d'opération</a>
            <a href="<?= site_url('frais-operations') ?>"><i class="ti ti-receipt-2"></i> Frais d'opération</a>
            <a href="<?= site_url('operateur/situation-gain') ?>"><i class="ti ti-chart-bar"></i> Situation des gains</a>
            <a href="<?= site_url('operateur/situation-gain-client') ?>"><i class="ti ti-user-search"></i> Situation client</a>
            <a href="<?= site_url('user/logout') ?>" style="margin-top:16px;"><i class="ti ti-logout"></i> Déconnexion</a>
        </nav>
    </aside>

    <main class="admin-main">

        <div class="admin-topline">
            <div>
                <h1>Tableau de bord opérateur</h1>
                <p style="color:var(--ink-500);font-size:13.5px;margin:0;">Vue d'ensemble de l'activité du jour</p>
            </div>
            <a href="<?= site_url('user/logout') ?>" class="btn btn-outline btn-sm"><i class="ti ti-logout"></i> Déconnexion</a>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-label"><i class="ti ti-users"></i> Utilisateurs</div>
                <div class="stat-value"><?= esc($nbUsers) ?></div>
                <div class="stat-sub">Comptes enregistrés</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="ti ti-coin"></i> Gains du jour</div>
                <div class="stat-value"><?= esc(number_format((float) $gain['total_gain'], 0, ',', ' ')) ?> Ar</div>
                <div class="stat-sub"><?= esc($date) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="ti ti-arrows-exchange"></i> Transactions</div>
                <div class="stat-value"><?= esc($gain['nombre_transaction']) ?></div>
                <div class="stat-sub">Réalisées aujourd'hui</div>
            </div>
        </div>

        <div class="section-title" style="margin:0 0 12px;">
            <h2>Gestion</h2>
        </div>

        <div class="manage-grid">
            <a href="<?= site_url('users') ?>" class="manage-card">
                <span class="mc-icon"><i class="ti ti-users"></i></span>
                <span>
                    <span class="mc-title">Utilisateurs</span>
                    <span class="mc-sub">Comptes clients et opérateurs</span>
                </span>
            </a>
            <a href="<?= site_url('configurations') ?>" class="manage-card">
                <span class="mc-icon"><i class="ti ti-settings"></i></span>
                <span>
                    <span class="mc-title">Configurations</span>
                    <span class="mc-sub">Préfixes téléphoniques</span>
                </span>
            </a>
            <a href="<?= site_url('type-operations') ?>" class="manage-card">
                <span class="mc-icon"><i class="ti ti-category"></i></span>
                <span>
                    <span class="mc-title">Types d'opération</span>
                    <span class="mc-sub">Dépôt, retrait, transfert…</span>
                </span>
            </a>
            <a href="<?= site_url('frais-operations') ?>" class="manage-card">
                <span class="mc-icon"><i class="ti ti-receipt-2"></i></span>
                <span>
                    <span class="mc-title">Frais d'opération</span>
                    <span class="mc-sub">Barèmes par tranche de montant</span>
                </span>
            </a>
            <a href="<?= site_url('operateur/situation-gain') ?>" class="manage-card">
                <span class="mc-icon"><i class="ti ti-chart-bar"></i></span>
                <span>
                    <span class="mc-title">Situation des gains</span>
                    <span class="mc-sub">Bilan sur une période</span>
                </span>
            </a>
            <a href="<?= site_url('operateur/situation-gain-client') ?>" class="manage-card">
                <span class="mc-icon"><i class="ti ti-user-search"></i></span>
                <span>
                    <span class="mc-title">Situation d'un client</span>
                    <span class="mc-sub">Détail par numéro</span>
                </span>
            </a>
        </div>

    </main>

</div>

</body>
</html>
