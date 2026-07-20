<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vola - Situation des gains</title>
    <link rel="stylesheet" href="/assets/css/tabler-icons-fallback.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<div class="admin-shell">

    <aside class="admin-sidebar">
        <div class="brand"><span class="brand-mark">V</span> Vola</div>
        <nav class="admin-nav">
            <a href="/operateur/dashboard"><i class="ti ti-layout-dashboard"></i> Tableau de bord</a>
            <a href="/users"><i class="ti ti-users"></i> Utilisateurs</a>
            <a href="/configurations"><i class="ti ti-settings"></i> Configurations</a>
            <a href="/type-operations"><i class="ti ti-category"></i> Types d'operation</a>
            <a href="/frais-operations"><i class="ti ti-receipt-2"></i> Frais d'operation</a>
            <a href="/operateur/situation-gain" class="active"><i class="ti ti-chart-bar"></i> Situation des gains</a>
            <a href="/operateur/situation-gain-client"><i class="ti ti-user-search"></i> Situation client</a>
            <a href="/user/logout" style="margin-top:16px;"><i class="ti ti-logout"></i> Deconnexion</a>
        </nav>
    </aside>

    <main class="admin-main">

        <a href="/operateur/dashboard" class="back-link"><i class="ti ti-arrow-left"></i> Retour au dashboard</a>
        <h1 style="margin-bottom:20px;">Situation des gains</h1>

        <?php if ($error): ?>
            <div class="alert error" style="margin:0 0 16px;"><i class="ti ti-alert-circle"></i> <?= esc($error) ?></div>
        <?php endif; ?>

        <div class="form-card" style="margin-bottom:24px;">
            <form method="get" action="/operateur/situation-gain" style="display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap;">
                <div class="field" style="margin-bottom:0;flex:1;min-width:180px;">
                    <label>Jusqu'au</label>
                    <input type="date" name="date" value="<?= esc($date) ?>">
                </div>
                <button class="btn btn-secondary" style="width:auto;padding:13px 22px;">
                    <i class="ti ti-search"></i> Afficher
                </button>
            </form>
        </div>

        <?php if ($situation): ?>
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-label"><i class="ti ti-coin"></i> Gain total</div>
                    <div class="stat-value"><?= esc(number_format((float) $situation['total_gain'], 0, ',', ' ')) ?> Ar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label"><i class="ti ti-arrows-exchange"></i> Transactions</div>
                    <div class="stat-value"><?= esc($situation['nombre_transaction']) ?></div>
                </div>
            </div>
        <?php endif; ?>

    </main>

</div>

</body>
</html>


