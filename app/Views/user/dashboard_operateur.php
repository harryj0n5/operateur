<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Dashboard operateur</title>
    <link rel="stylesheet" href="/assets/css/tabler-icons-fallback.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <aside class="admin-sidebar">
        <div class="brand"><span class="brand-mark">V</span> Vola</div>
        <nav class="admin-nav">
            <a href="<?= site_url('operateur/dashboard') ?>" class="active"><i class="ti ti-layout-dashboard"></i>
                Tableau de bord</a>
            <a href="<?= site_url('users') ?>"><i class="ti ti-users"></i> Utilisateurs</a>
            <a href="<?= site_url('operateur') ?>"><i class="ti ti-building-bank"></i> Opérateurs</a>
            <a href="<?= site_url('configurations') ?>"><i class="ti ti-settings"></i> Configurations</a>
            <a href="<?= site_url('type-operations') ?>"><i class="ti ti-category"></i> Types d'operation</a>
            <a href="<?= site_url('frais-operations') ?>"><i class="ti ti-receipt-2"></i> Frais d'operation</a>
            <a href="<?= site_url('operateur/situation-gain') ?>"><i class="ti ti-chart-bar"></i> Situation des
                gains</a>
            <a href="<?= site_url('operateur/situation-gain-client') ?>"><i class="ti ti-user-search"></i> Situation
                client</a>
            <a href="<?= site_url('user/logout') ?>" style="margin-top:16px;"><i class="ti ti-logout"></i>
                Deconnexion</a>
        </nav>
    </aside>

    <main class="admin-main">

        <div class="admin-topline">
            <div>
                <h1>Tableau de bord operateur</h1>
                <p style="color:var(--ink-500);font-size:13.5px;margin:0;">Vue d'ensemble de l'activite du jour</p>
            </div>
            <a href="<?= site_url('user/logout') ?>" class="btn btn-outline btn-sm"><i class="ti ti-logout"></i>
                Deconnexion</a>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-label"><i class="ti ti-users"></i> Utilisateurs</div>
                <div class="stat-value"><?= esc($nbUsers) ?></div>
                <div class="stat-sub">Comptes enregistres</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="ti ti-coin"></i> Gains du jour</div>
                <div class="stat-value"><?= esc(number_format((float)$gain['total_gain'], 0, ',', ' ')) ?> Ar</div>
                <div class="stat-sub"><?= esc($date) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="ti ti-arrows-exchange"></i> Transactions</div>
                <div class="stat-value"><?= esc($gain['nombre_transaction']) ?></div>
                <div class="stat-sub">Realisees aujourd'hui</div>
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
                    <span class="mc-sub">Comptes clients et operateurs</span>
                </span>
            </a>
            <a href="<?= site_url('operateur') ?>" class="manage-card">
                <span class="mc-icon"><i class="ti ti-settings"></i></span>
                <span>
                    <span class="mc-title">Operateurs</span>
                    <span class="mc-sub">Liste des operateurs disponibles</span>
                </span>
            </a>
            <a href="<?= site_url('configurations') ?>" class="manage-card">
                <span class="mc-icon"><i class="ti ti-settings"></i></span>
                <span>
                    <span class="mc-title">Configurations</span>
                    <span class="mc-sub">Prefixes telephoniques</span>
                </span>
            </a>
            <a href="<?= site_url('type-operations') ?>" class="manage-card">
                <span class="mc-icon"><i class="ti ti-category"></i></span>
                <span>
                    <span class="mc-title">Types d'operation</span>
                    <span class="mc-sub">Depot, retrait, transfert</span>
                </span>
            </a>
            <a href="<?= site_url('frais-operations') ?>" class="manage-card">
                <span class="mc-icon"><i class="ti ti-receipt-2"></i></span>
                <span>
                    <span class="mc-title">Frais d'operation</span>
                    <span class="mc-sub">Baremes par tranche de montant</span>
                </span>
            </a>
            <a href="<?= site_url('operateur/situation-gain') ?>" class="manage-card">
                <span class="mc-icon"><i class="ti ti-chart-bar"></i></span>
                <span>
                    <span class="mc-title">Situation des gains</span>
                    <span class="mc-sub">Bilan sur une periode</span>
                </span>
            </a>
            <a href="<?= site_url('operateur/situation-gain-client') ?>" class="manage-card">
                <span class="mc-icon"><i class="ti ti-user-search"></i></span>
                <span>
                    <span class="mc-title">Situation d'un client</span>
                    <span class="mc-sub">Detail par numero</span>
                </span>
            </a>
        </div>

    </main>

</div>

</body>
</html>


