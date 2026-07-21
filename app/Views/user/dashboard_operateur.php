<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Dashboard operateur</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <?= view('partials/sidebar', ['active' => 'dashboard']) ?>

    <main class="admin-main">

        <div class="admin-topline">
            <div>
                <h1>Tableau de bord operateur</h1>
                <p class="admin-topline-sub">Vue d'ensemble de l'activite du jour</p>
            </div>
            <a href="<?= site_url('user/logout') ?>" class="btn btn-outline btn-sm">
                <i class="bi bi-box-arrow-right"></i> Deconnexion
            </a>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-label"><i class="bi bi-people"></i> Utilisateurs</div>
                <div class="stat-value"><?= esc($nbUsers) ?></div>
                <div class="stat-sub">Comptes enregistres</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="bi bi-coin"></i> Gains du jour</div>
                <div class="stat-value">
                    <?= esc(number_format((float)$gain['total_operateur_principal'], 0, ',', ' ')) ?> Ar
                </div>
                <div class="stat-sub"><?= esc($date) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="bi bi-arrow-left-right"></i> Transactions</div>
                <div class="stat-value"><?= esc($gain['nombre_transaction']) ?></div>
                <div class="stat-sub">Realisees aujourd'hui</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">
                <i class="bi bi-bank"></i>
                Autres operateurs
            </div>
            <div class="stat-value">
                <?= esc(number_format((float)$gain['total_autres_operateurs'], 0, ',', ' ')) ?> Ar
            </div>
            <div class="stat-sub">
                Commissions transferts externes
            </div>
        </div>

        <div class="section-title" style="margin:24px 0 12px;">
            <h2>Gestion</h2>
        </div>

        <div class="manage-grid">
            <a href="<?= site_url('users') ?>" class="manage-card">
                <span class="mc-icon"><i class="bi bi-people"></i></span>
                <span>
                    <span class="mc-title">Utilisateurs</span>
                    <span class="mc-sub">Comptes clients et operateurs</span>
                </span>
            </a>
            <a href="<?= site_url('operateur') ?>" class="manage-card">
                <span class="mc-icon"><i class="bi bi-bank"></i></span>
                <span>
                    <span class="mc-title">Operateurs</span>
                    <span class="mc-sub">Liste des operateurs disponibles</span>
                </span>
            </a>
            <a href="<?= site_url('configurations') ?>" class="manage-card">
                <span class="mc-icon"><i class="bi bi-sliders"></i></span>
                <span>
                    <span class="mc-title">Configurations</span>
                    <span class="mc-sub">Prefixes telephoniques</span>
                </span>
            </a>
            <a href="<?= site_url('type-operations') ?>" class="manage-card">
                <span class="mc-icon"><i class="bi bi-grid"></i></span>
                <span>
                    <span class="mc-title">Types d'operation</span>
                    <span class="mc-sub">Depot, retrait, transfert</span>
                </span>
            </a>
            <a href="<?= site_url('frais-operations') ?>" class="manage-card">
                <span class="mc-icon"><i class="bi bi-receipt"></i></span>
                <span>
                    <span class="mc-title">Frais d'operation</span>
                    <span class="mc-sub">Baremes par tranche de montant</span>
                </span>
            </a>
            <a href="<?= site_url('operateur/situation-gain') ?>" class="manage-card">
                <span class="mc-icon"><i class="bi bi-bar-chart"></i></span>
                <span>
                    <span class="mc-title">Situation des gains</span>
                    <span class="mc-sub">Bilan sur une periode</span>
                </span>
            </a>
            <a href="<?= site_url('operateur/situation-gain-client') ?>" class="manage-card">
                <span class="mc-icon"><i class="bi bi-search"></i></span>
                <span>
                    <span class="mc-title">Situation d'un client</span>
                    <span class="mc-sub">Detail par numero</span>
                </span>
            </a>
        </div>

    </main>

</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>